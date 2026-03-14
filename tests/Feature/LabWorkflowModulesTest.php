<?php

namespace Tests\Feature;

use App\Livewire\Lab\Results\ReleaseIndex;
use App\Livewire\Lab\Results\ResultIndex;
use App\Livewire\Lab\Orders\OrderCreate;
use App\Livewire\Lab\Samples\CollectionIndex;
use App\Livewire\Lab\Samples\ReceiveIndex;
use App\Livewire\Lab\Samples\RejectedIndex;
use App\Livewire\Lab\Worklists\WorklistIndex;
use App\Models\Lab;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Patient;
use App\Models\Sample;
use App\Models\Test;
use App\Models\User;
use App\Services\LabWorkflowService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class LabWorkflowModulesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        foreach ([
            User::ROLE_SUPERADMIN,
            User::ROLE_LAB_ADMIN,
            User::ROLE_LAB_INCHARGE,
            User::ROLE_RECEPTIONIST,
            User::ROLE_TECHNICIAN,
        ] as $role) {
            Role::firstOrCreate([
                'name' => $role,
                'guard_name' => 'web',
            ]);
        }
    }

    public function test_sample_collection_creates_accession_and_moves_item_to_collection_queue(): void
    {
        $receptionist = $this->createLabUser(User::ROLE_RECEPTIONIST);
        $item = $this->createOrderItem($receptionist->lab, $receptionist);

        Livewire::actingAs($receptionist)
            ->test(CollectionIndex::class)
            ->call('openCollect', $item->id)
            ->set('sample_type', 'Blood')
            ->set('container', 'EDTA')
            ->call('saveCollection')
            ->assertHasNoErrors();

        $item->refresh()->load('sample');

        $this->assertSame(OrderItem::STATUS_SAMPLE_COLLECTED, $item->status);
        $this->assertNotNull($item->sample);
        $this->assertSame('collected', $item->sample->status);
        $this->assertSame('Blood', $item->sample->sample_type);
    }

    public function test_received_samples_can_be_assigned_and_started_from_worklists(): void
    {
        $receptionist = $this->createLabUser(User::ROLE_RECEPTIONIST);
        $technician = $this->createLabUser(User::ROLE_TECHNICIAN, $receptionist->lab);
        $item = $this->createOrderItem($receptionist->lab, $receptionist);

        app(LabWorkflowService::class)->collectSample($item, $receptionist, [
            'sample_type' => 'Blood',
            'container' => 'EDTA',
        ]);

        Livewire::actingAs($technician)
            ->test(ReceiveIndex::class)
            ->call('receive', $item->fresh()->sample->id)
            ->assertHasNoErrors();

        Livewire::actingAs($technician)
            ->test(WorklistIndex::class)
            ->call('assignToMe', $item->id)
            ->call('startProcessing', $item->id)
            ->assertHasNoErrors();

        $item->refresh()->load('sample');

        $this->assertSame('received', $item->sample->status);
        $this->assertSame($technician->id, $item->assigned_to);
        $this->assertSame(OrderItem::STATUS_PROCESSING, $item->status);
    }

    public function test_results_require_remarks_for_critical_values_then_can_be_verified_and_released(): void
    {
        $receptionist = $this->createLabUser(User::ROLE_RECEPTIONIST);
        $technician = $this->createLabUser(User::ROLE_TECHNICIAN, $receptionist->lab);
        $incharge = $this->createLabUser(User::ROLE_LAB_INCHARGE, $receptionist->lab);
        $item = $this->createReadyBenchItem($receptionist, $technician);
        $order = $item->order()->first();

        Livewire::actingAs($technician)
            ->test(ResultIndex::class)
            ->call('openResultEntry', $item->id)
            ->set('value', '12.4')
            ->set('unit', 'g/dL')
            ->set('normal_range', '12-16')
            ->set('flag', 'critical')
            ->set('remarks', '')
            ->call('saveResult')
            ->assertHasErrors(['remarks']);

        Livewire::actingAs($technician)
            ->test(ResultIndex::class)
            ->call('openResultEntry', $item->id)
            ->set('value', '12.4')
            ->set('unit', 'g/dL')
            ->set('normal_range', '12-16')
            ->set('flag', 'critical')
            ->set('remarks', 'Escalated to supervisor')
            ->call('saveResult')
            ->assertHasNoErrors();

        Livewire::actingAs($incharge)
            ->test(ResultIndex::class)
            ->call('verify', $item->id)
            ->assertHasNoErrors();

        $this->actingAs($incharge)
            ->get(route('lab.orders.report', $order))
            ->assertForbidden();

        Livewire::actingAs($incharge)
            ->test(ReleaseIndex::class)
            ->call('release', $order->id)
            ->assertHasNoErrors();

        $item->refresh()->load('result');
        $order->refresh()->load('items.result');

        $this->assertSame('released', $item->result->status);
        $this->assertTrue($order->canPrintReport());

        $this->actingAs($incharge)
            ->get(route('lab.orders.report', $order))
            ->assertOk();
    }

    public function test_editing_a_released_result_creates_revision_and_returns_it_to_draft(): void
    {
        $receptionist = $this->createLabUser(User::ROLE_RECEPTIONIST);
        $technician = $this->createLabUser(User::ROLE_TECHNICIAN, $receptionist->lab);
        $incharge = $this->createLabUser(User::ROLE_LAB_INCHARGE, $receptionist->lab);
        $item = $this->createReadyBenchItem($receptionist, $technician);

        app(LabWorkflowService::class)->saveResult($item, $technician, [
            'value' => '13.0',
            'unit' => 'g/dL',
            'normal_range' => '12-16',
            'flag' => 'normal',
            'remarks' => 'Initial entry',
        ]);
        app(LabWorkflowService::class)->verifyResult($item->fresh(), $incharge);
        app(LabWorkflowService::class)->releaseOrder($item->order()->first()->fresh('items.result'), $incharge);

        Livewire::actingAs($technician)
            ->test(ResultIndex::class)
            ->call('openResultEntry', $item->id)
            ->set('value', '13.6')
            ->set('unit', 'g/dL')
            ->set('normal_range', '12-16')
            ->set('flag', 'normal')
            ->set('remarks', 'Analyzer rerun')
            ->call('saveResult')
            ->assertHasNoErrors();

        $item->refresh()->load('result.revisions', 'order.items.result');

        $this->assertSame('draft', $item->result->status);
        $this->assertCount(1, $item->result->revisions);
        $this->assertFalse($item->order->canPrintReport());
    }

    public function test_order_creation_recomputes_amounts_from_server_side_test_records(): void
    {
        $receptionist = $this->createLabUser(User::ROLE_RECEPTIONIST);
        $patient = Patient::create([
            'lab_id' => $receptionist->lab_id,
            'name' => 'Pricing Patient',
            'phone' => '0500000001',
            'gender' => 'female',
            'age' => 28,
        ]);
        $test = Test::create([
            'lab_id' => $receptionist->lab_id,
            'name' => 'Glucose',
            'code' => 'GLU',
            'price' => 500,
            'sample_type' => 'Serum',
            'turnaround_hours' => 6,
            'is_active' => true,
        ]);

        Livewire::actingAs($receptionist)
            ->test(OrderCreate::class)
            ->set('patient_id', $patient->id)
            ->set('selectedTests', [
                $test->id => [
                    'id' => $test->id,
                    'name' => $test->name,
                    'price' => 1,
                    'turnaround_hours' => 1,
                ],
            ])
            ->set('paid_amount', '100')
            ->set('payment_method', 'cash')
            ->call('placeOrder')
            ->assertHasNoErrors();

        $order = Order::with(['items', 'invoice'])->latest('id')->first();

        $this->assertNotNull($order);
        $this->assertSame('500.00', (string) $order->total_amount);
        $this->assertSame('500.00', (string) $order->net_amount);
        $this->assertSame('500.00', (string) $order->items->first()->price);
        $this->assertSame('500.00', (string) $order->invoice->total);
        $this->assertSame('100.00', (string) $order->invoice->paid_amount);
        $this->assertSame('400.00', (string) $order->invoice->balance);
    }

    public function test_order_creation_rejects_discount_above_server_side_subtotal(): void
    {
        $receptionist = $this->createLabUser(User::ROLE_RECEPTIONIST);
        $patient = Patient::create([
            'lab_id' => $receptionist->lab_id,
            'name' => 'Discount Patient',
            'phone' => '0500000002',
            'gender' => 'male',
            'age' => 30,
        ]);
        $test = Test::create([
            'lab_id' => $receptionist->lab_id,
            'name' => 'Calcium',
            'code' => 'CA',
            'price' => 250,
            'sample_type' => 'Serum',
            'turnaround_hours' => 4,
            'is_active' => true,
        ]);

        Livewire::actingAs($receptionist)
            ->test(OrderCreate::class)
            ->set('patient_id', $patient->id)
            ->set('selectedTests', [
                $test->id => [
                    'id' => $test->id,
                    'name' => $test->name,
                    'price' => 1,
                    'turnaround_hours' => 1,
                ],
            ])
            ->set('discount', '999')
            ->set('paid_amount', '0')
            ->set('payment_method', 'cash')
            ->call('placeOrder')
            ->assertHasErrors(['discount']);

        $this->assertDatabaseCount('orders', 0);
    }

    public function test_other_lab_users_cannot_load_results_for_a_foreign_order_item(): void
    {
        $receptionist = $this->createLabUser(User::ROLE_RECEPTIONIST);
        $outsider = $this->createLabUser(User::ROLE_TECHNICIAN);
        $item = $this->createReadyBenchItem($receptionist, $this->createLabUser(User::ROLE_TECHNICIAN, $receptionist->lab));

        try {
            Livewire::actingAs($outsider)
                ->test(ResultIndex::class)
                ->call('openResultEntry', $item->id);

            $this->fail('Expected a model not found exception for a foreign lab order item.');
        } catch (ModelNotFoundException $exception) {
            $this->assertTrue(true);
        }
    }

    public function test_other_lab_users_cannot_receive_or_recollect_foreign_samples(): void
    {
        $receptionist = $this->createLabUser(User::ROLE_RECEPTIONIST);
        $ownerTechnician = $this->createLabUser(User::ROLE_TECHNICIAN, $receptionist->lab);
        $outsiderTechnician = $this->createLabUser(User::ROLE_TECHNICIAN);
        $outsiderReceptionist = $this->createLabUser(User::ROLE_RECEPTIONIST);
        $item = $this->createOrderItem($receptionist->lab, $receptionist);

        $sample = app(LabWorkflowService::class)->collectSample($item, $receptionist, [
            'sample_type' => 'Blood',
            'container' => 'EDTA',
        ]);

        try {
            Livewire::actingAs($outsiderTechnician)
                ->test(ReceiveIndex::class)
                ->call('receive', $sample->id);

            $this->fail('Expected a model not found exception for a foreign lab sample receive.');
        } catch (ModelNotFoundException $exception) {
            $this->assertSame(Sample::STATUS_COLLECTED, $sample->fresh()->status);
        }

        app(LabWorkflowService::class)->rejectSample($sample->fresh(), 'Insufficient sample');

        try {
            Livewire::actingAs($outsiderReceptionist)
                ->test(RejectedIndex::class)
                ->call('openRecollect', $sample->id);

            $this->fail('Expected a model not found exception for a foreign lab rejected sample.');
        } catch (ModelNotFoundException $exception) {
            $this->assertSame(Sample::STATUS_REJECTED, $sample->fresh()->status);
        }

        $this->assertNotSame($ownerTechnician->lab_id, $outsiderTechnician->lab_id);
    }

    public function test_other_lab_users_cannot_stream_a_foreign_released_report(): void
    {
        $receptionist = $this->createLabUser(User::ROLE_RECEPTIONIST);
        $technician = $this->createLabUser(User::ROLE_TECHNICIAN, $receptionist->lab);
        $incharge = $this->createLabUser(User::ROLE_LAB_INCHARGE, $receptionist->lab);
        $outsider = $this->createLabUser(User::ROLE_LAB_INCHARGE);
        $item = $this->createReadyBenchItem($receptionist, $technician);
        $order = $item->order()->first();

        app(LabWorkflowService::class)->saveResult($item, $technician, [
            'value' => '13.2',
            'unit' => 'g/dL',
            'normal_range' => '12-16',
            'flag' => 'normal',
            'remarks' => 'Finalized',
        ]);
        app(LabWorkflowService::class)->verifyResult($item->fresh(), $incharge);
        app(LabWorkflowService::class)->releaseOrder($order->fresh('items.result'), $incharge);

        $this->actingAs($outsider)
            ->get(route('lab.orders.report', $order))
            ->assertNotFound();
    }

    private function createReadyBenchItem(User $receptionist, User $technician): OrderItem
    {
        $item = $this->createOrderItem($receptionist->lab, $receptionist);

        app(LabWorkflowService::class)->collectSample($item, $receptionist, [
            'sample_type' => 'Blood',
            'container' => 'EDTA',
        ]);
        app(LabWorkflowService::class)->receiveSample($item->fresh()->sample, $technician);
        app(LabWorkflowService::class)->assignWorklist($item->fresh(), $technician);
        app(LabWorkflowService::class)->startProcessing($item->fresh(), $technician);

        return $item->fresh();
    }

    private function createOrderItem(Lab $lab, User $creator): OrderItem
    {
        $patient = Patient::create([
            'lab_id' => $lab->id,
            'name' => 'Test Patient',
            'phone' => '0500000000',
            'gender' => 'male',
            'age' => 32,
        ]);

        $test = Test::create([
            'lab_id' => $lab->id,
            'name' => 'Hemoglobin',
            'code' => 'HB',
            'price' => 500,
            'unit' => 'g/dL',
            'normal_range' => '12-16',
            'sample_type' => 'Blood',
            'turnaround_hours' => 4,
            'is_active' => true,
        ]);

        $order = Order::create([
            'lab_id' => $lab->id,
            'patient_id' => $patient->id,
            'created_by' => $creator->id,
            'status' => Order::STATUS_PENDING,
            'total_amount' => 500,
            'discount' => 0,
            'net_amount' => 500,
        ]);

        $item = OrderItem::create([
            'order_id' => $order->id,
            'test_id' => $test->id,
            'price' => 500,
            'status' => OrderItem::STATUS_PENDING,
        ]);

        $item->update([
            'due_at' => now()->addHours(4),
        ]);

        return $item->fresh();
    }

    private function createLabUser(string $role, ?Lab $lab = null): User
    {
        $lab ??= Lab::create([
            'name' => 'Acme Lab',
            'slug' => 'acme-lab-' . str()->random(5),
            'is_active' => true,
        ]);

        $user = User::factory()->create([
            'lab_id' => $lab->id,
            'is_active' => true,
        ]);
        $user->assignRole($role);

        return $user->fresh();
    }
}
