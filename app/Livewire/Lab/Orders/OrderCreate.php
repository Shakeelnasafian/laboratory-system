<?php

namespace App\Livewire\Lab\Orders;

use App\Models\Invoice;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Patient;
use App\Models\Test;
use Livewire\Component;

class OrderCreate extends Component
{
    // Patient
    public string  $patient_search  = '';
    public ?int    $patient_id      = null;
    public ?object $selectedPatient = null;
    public array   $patientResults  = [];

    // Order
    public array  $selectedTests = []; // [test_id => ['test' => Test, 'price' => xx]]
    public string $test_search   = '';
    public array  $testResults   = [];
    public string $discount      = '0';
    public string $referred_by   = '';
    public bool   $is_urgent     = false;
    public string $notes         = '';

    // Payment
    public string $paid_amount     = '0';
    public string $payment_method  = 'cash';

    public function updatedPatientSearch(): void
    {
        if (strlen($this->patient_search) >= 2) {
            $this->patientResults = Patient::where(function($q) {
                    $q->where('name', 'like', "%{$this->patient_search}%")
                      ->orWhere('cnic', 'like', "%{$this->patient_search}%")
                      ->orWhere('phone', 'like', "%{$this->patient_search}%");
                })
                ->take(6)->get()->toArray();
        } else {
            $this->patientResults = [];
        }
    }

    public function updatedTestSearch(): void
    {
        if (strlen($this->test_search) >= 2) {
            $this->testResults = Test::where('is_active', true)
                ->where(fn($q) => $q->where('name', 'like', "%{$this->test_search}%")
                    ->orWhere('code', 'like', "%{$this->test_search}%"))
                ->take(8)->get()->toArray();
        } else {
            $this->testResults = [];
        }
    }

    public function selectPatient(int $id): void
    {
        $this->selectedPatient  = Patient::find($id);
        $this->patient_id       = $id;
        $this->patient_search   = $this->selectedPatient->name;
        $this->patientResults   = [];
    }

    public function addTest(int $testId): void
    {
        if (!isset($this->selectedTests[$testId])) {
            $test = Test::find($testId);
            $this->selectedTests[$testId] = [
                'id'    => $test->id,
                'name'  => $test->name,
                'price' => $test->price,
            ];
        }
        $this->test_search  = '';
        $this->testResults  = [];
    }

    public function removeTest(int $testId): void
    {
        unset($this->selectedTests[$testId]);
    }

    public function getSubtotalProperty(): float
    {
        return collect($this->selectedTests)->sum('price');
    }

    public function getNetAmountProperty(): float
    {
        return max(0, $this->subtotal - floatval($this->discount));
    }

    public function placeOrder(): void
    {
        $this->validate([
            'patient_id'     => 'required|exists:patients,id',
            'selectedTests'  => 'required|min:1',
            'paid_amount'    => 'required|numeric|min:0',
            'payment_method' => 'required',
        ]);

        $order = Order::create([
            'patient_id'   => $this->patient_id,
            'created_by'   => auth()->id(),
            'status'       => 'pending',
            'is_urgent'    => $this->is_urgent,
            'total_amount' => $this->subtotal,
            'discount'     => floatval($this->discount),
            'net_amount'   => $this->netAmount,
            'referred_by'  => $this->referred_by,
            'notes'        => $this->notes,
        ]);

        foreach ($this->selectedTests as $testId => $testData) {
            OrderItem::create([
                'order_id' => $order->id,
                'test_id'  => $testId,
                'price'    => $testData['price'],
                'status'   => 'pending',
            ]);
        }

        // Create invoice
        $paid    = floatval($this->paid_amount);
        $balance = $this->netAmount - $paid;
        Invoice::create([
            'lab_id'          => auth()->user()->lab_id,
            'order_id'        => $order->id,
            'subtotal'        => $this->subtotal,
            'discount'        => floatval($this->discount),
            'total'           => $this->netAmount,
            'paid_amount'     => $paid,
            'balance'         => $balance,
            'payment_status'  => $balance <= 0 ? 'paid' : ($paid > 0 ? 'partial' : 'unpaid'),
            'payment_method'  => $this->payment_method,
        ]);

        session()->flash('success', "Order #{$order->order_number} created successfully.");
        $this->redirect(route('lab.orders.show', $order), navigate: true);
    }

    public function render()
    {
        return view('livewire.lab.orders.create')
            ->layout('layouts.lab', ['title' => 'New Order']);
    }
}
