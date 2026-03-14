<?php

namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\Lab;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Patient;
use App\Models\Result;
use App\Models\Sample;
use App\Models\Test as LabTest;
use App\Models\TestCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DemoLabShowcaseSeeder extends Seeder
{
    public function run(): void
    {
        $demoLabs = Lab::query()
            ->whereIn('slug', DemoLabsSeeder::DEMO_LAB_SLUGS)
            ->get();

        foreach ($demoLabs as $lab) {
            $staff = $this->resolveStaff($lab);

            if (in_array(null, $staff, true)) {
                $this->command?->warn("Skipping {$lab->name}: required demo staff accounts are missing.");
                continue;
            }

            $categories = $this->seedCategories($lab);
            $tests = $this->seedTests($lab, $categories);
            $patients = $this->seedPatients($lab);

            $this->seedOrders($lab, $staff, $patients, $tests);
        }

        $this->command?->info('Demo showcase data for patients, tests, orders, samples, results, and invoices seeded.');
    }

    private function resolveStaff(Lab $lab): array
    {
        $users = $lab->users()->with('roles')->get();

        return [
            'admin' => $users->first(fn (User $user) => $user->hasRole(User::ROLE_LAB_ADMIN)),
            'incharge' => $users->first(fn (User $user) => $user->hasRole(User::ROLE_LAB_INCHARGE)),
            'receptionist' => $users->first(fn (User $user) => $user->hasRole(User::ROLE_RECEPTIONIST)),
            'technician' => $users->first(fn (User $user) => $user->hasRole(User::ROLE_TECHNICIAN)),
        ];
    }

    private function seedCategories(Lab $lab): array
    {
        $categories = [];

        foreach ([
            'hematology' => ['name' => 'Hematology', 'description' => 'CBC, hemoglobin, and blood count profiles.'],
            'chemistry' => ['name' => 'Clinical Chemistry', 'description' => 'Routine serum and metabolic chemistry testing.'],
            'diabetes' => ['name' => 'Diabetes', 'description' => 'Glucose monitoring and glycemic control markers.'],
            'hormones' => ['name' => 'Hormones', 'description' => 'Thyroid and endocrine screening assays.'],
            'serology' => ['name' => 'Serology', 'description' => 'Screening and infection marker panels.'],
            'urinalysis' => ['name' => 'Urinalysis', 'description' => 'Urine routine examination and microscopy.'],
        ] as $key => $definition) {
            $category = TestCategory::updateOrCreate(
                [
                    'lab_id' => $lab->id,
                    'name' => $definition['name'],
                ],
                [
                    'description' => $definition['description'],
                    'is_active' => true,
                ]
            );

            $categories[$key] = $category;
        }

        return $categories;
    }

    private function seedTests(Lab $lab, array $categories): array
    {
        $tests = [];

        foreach ([
            [
                'code' => 'HB',
                'name' => 'Hemoglobin',
                'short_name' => 'Hb',
                'category' => 'hematology',
                'price' => 450,
                'unit' => 'g/dL',
                'normal_range' => '12-16',
                'sample_type' => 'Blood',
                'turnaround_hours' => 4,
                'description' => 'Single-parameter hemoglobin screening.',
            ],
            [
                'code' => 'WBC',
                'name' => 'White Blood Cell Count',
                'short_name' => 'WBC',
                'category' => 'hematology',
                'price' => 650,
                'unit' => 'x10^9/L',
                'normal_range' => '4-11',
                'sample_type' => 'Blood',
                'turnaround_hours' => 4,
                'description' => 'Leukocyte count for infection and inflammation review.',
            ],
            [
                'code' => 'PLT',
                'name' => 'Platelet Count',
                'short_name' => 'Platelet',
                'category' => 'hematology',
                'price' => 600,
                'unit' => 'x10^9/L',
                'normal_range' => '150-450',
                'sample_type' => 'Blood',
                'turnaround_hours' => 4,
                'description' => 'Platelet estimation for bleeding and clotting risk.',
            ],
            [
                'code' => 'FBS',
                'name' => 'Fasting Blood Sugar',
                'short_name' => 'FBS',
                'category' => 'diabetes',
                'price' => 700,
                'unit' => 'mg/dL',
                'normal_range' => '70-100',
                'sample_type' => 'Fluoride Plasma',
                'turnaround_hours' => 6,
                'description' => 'Fasting glucose assessment.',
            ],
            [
                'code' => 'CR',
                'name' => 'Serum Creatinine',
                'short_name' => 'Creatinine',
                'category' => 'chemistry',
                'price' => 900,
                'unit' => 'mg/dL',
                'normal_range' => '0.7-1.3',
                'sample_type' => 'Serum',
                'turnaround_hours' => 6,
                'description' => 'Renal function screening parameter.',
            ],
            [
                'code' => 'ALT',
                'name' => 'ALT / SGPT',
                'short_name' => 'ALT',
                'category' => 'chemistry',
                'price' => 1100,
                'unit' => 'U/L',
                'normal_range' => '7-56',
                'sample_type' => 'Serum',
                'turnaround_hours' => 8,
                'description' => 'Liver enzyme review for hepatocellular injury.',
            ],
            [
                'code' => 'HBA1C',
                'name' => 'HbA1c',
                'short_name' => 'HbA1c',
                'category' => 'diabetes',
                'price' => 1800,
                'unit' => '%',
                'normal_range' => '4.0-5.6',
                'sample_type' => 'Whole Blood',
                'turnaround_hours' => 12,
                'description' => 'Three-month glucose control indicator.',
            ],
            [
                'code' => 'TSH',
                'name' => 'Thyroid Stimulating Hormone',
                'short_name' => 'TSH',
                'category' => 'hormones',
                'price' => 2200,
                'unit' => 'uIU/mL',
                'normal_range' => '0.4-4.2',
                'sample_type' => 'Serum',
                'turnaround_hours' => 12,
                'description' => 'Primary thyroid screening assay.',
            ],
            [
                'code' => 'HBSAG',
                'name' => 'HBsAg',
                'short_name' => 'HBsAg',
                'category' => 'serology',
                'price' => 1600,
                'unit' => 'Index',
                'normal_range' => 'Non-reactive',
                'sample_type' => 'Serum',
                'turnaround_hours' => 24,
                'description' => 'Hepatitis B surface antigen screening.',
            ],
            [
                'code' => 'URINE-RE',
                'name' => 'Urine Routine Examination',
                'short_name' => 'Urine R/E',
                'category' => 'urinalysis',
                'price' => 800,
                'unit' => 'Report',
                'normal_range' => 'Normal',
                'sample_type' => 'Urine',
                'turnaround_hours' => 6,
                'description' => 'Urinalysis with routine microscopy findings.',
            ],
        ] as $definition) {
            $test = LabTest::updateOrCreate(
                [
                    'lab_id' => $lab->id,
                    'code' => $definition['code'],
                ],
                [
                    'category_id' => $categories[$definition['category']]->id,
                    'name' => $definition['name'],
                    'short_name' => $definition['short_name'],
                    'price' => $definition['price'],
                    'unit' => $definition['unit'],
                    'normal_range' => $definition['normal_range'],
                    'description' => $definition['description'],
                    'sample_type' => $definition['sample_type'],
                    'turnaround_hours' => $definition['turnaround_hours'],
                    'is_active' => true,
                ]
            );

            $tests[$definition['code']] = $test;
        }

        return $tests;
    }

    private function seedPatients(Lab $lab): array
    {
        $prefix = strtoupper(substr($lab->slug, 0, 3));
        $today = now()->startOfDay();

        $patients = [];

        foreach ([
            'sara' => [
                'patient_id' => "{$prefix}-P-001",
                'name' => 'Sara Ali',
                'cnic' => '35202-1000001-2',
                'phone' => '0301-5551001',
                'email' => strtolower("sara.{$prefix}@patients.demo"),
                'gender' => 'female',
                'age' => 29,
                'address' => 'Model Town',
                'referred_by' => 'Dr. Hina Saeed',
                'created_at' => $today->copy()->addHours(7)->addMinutes(30),
            ],
            'umar' => [
                'patient_id' => "{$prefix}-P-002",
                'name' => 'Umar Farooq',
                'cnic' => '35202-1000002-4',
                'phone' => '0301-5551002',
                'email' => strtolower("umar.{$prefix}@patients.demo"),
                'gender' => 'male',
                'age' => 42,
                'address' => 'Johar Town',
                'referred_by' => 'Dr. Kamran Akhtar',
                'created_at' => $today->copy()->addHours(8)->addMinutes(45),
            ],
            'hina' => [
                'patient_id' => "{$prefix}-P-003",
                'name' => 'Hina Noor',
                'cnic' => '35202-1000003-6',
                'phone' => '0301-5551003',
                'email' => strtolower("hina.{$prefix}@patients.demo"),
                'gender' => 'female',
                'age' => 35,
                'address' => 'DHA Phase 4',
                'referred_by' => 'Dr. Salman Qazi',
                'created_at' => $today->copy()->addHours(9)->addMinutes(20),
            ],
            'ali' => [
                'patient_id' => "{$prefix}-P-004",
                'name' => 'Ali Hassan',
                'cnic' => '35202-1000004-8',
                'phone' => '0301-5551004',
                'email' => strtolower("ali.{$prefix}@patients.demo"),
                'gender' => 'male',
                'age' => 31,
                'address' => 'Bahria Town',
                'referred_by' => 'Dr. Saba Aamir',
                'created_at' => $today->copy()->addHours(10)->addMinutes(5),
            ],
            'noor' => [
                'patient_id' => "{$prefix}-P-005",
                'name' => 'Noor Fatima',
                'cnic' => '35202-1000005-0',
                'phone' => '0301-5551005',
                'email' => strtolower("noor.{$prefix}@patients.demo"),
                'gender' => 'female',
                'age' => 27,
                'address' => 'PECHS',
                'referred_by' => 'Dr. Hamid Raza',
                'created_at' => $today->copy()->subDay()->addHours(11),
            ],
            'hamza' => [
                'patient_id' => "{$prefix}-P-006",
                'name' => 'Hamza Tariq',
                'cnic' => '35202-1000006-1',
                'phone' => '0301-5551006',
                'email' => strtolower("hamza.{$prefix}@patients.demo"),
                'gender' => 'male',
                'age' => 46,
                'address' => 'Nazimabad',
                'referred_by' => 'Dr. Tehmina Yusuf',
                'created_at' => $today->copy()->subDay()->addHours(12)->addMinutes(20),
            ],
            'maryam' => [
                'patient_id' => "{$prefix}-P-007",
                'name' => 'Maryam Zahid',
                'cnic' => '35202-1000007-3',
                'phone' => '0301-5551007',
                'email' => strtolower("maryam.{$prefix}@patients.demo"),
                'gender' => 'female',
                'age' => 38,
                'address' => 'Gulistan-e-Jauhar',
                'referred_by' => 'Dr. Nabeel Shah',
                'created_at' => $today->copy()->subDays(2)->addHours(10)->addMinutes(30),
            ],
            'bilal' => [
                'patient_id' => "{$prefix}-P-008",
                'name' => 'Bilal Khan',
                'cnic' => '35202-1000008-5',
                'phone' => '0301-5551008',
                'email' => strtolower("bilal.{$prefix}@patients.demo"),
                'gender' => 'male',
                'age' => 51,
                'address' => 'Cantt',
                'referred_by' => 'Dr. Rabia Adeel',
                'created_at' => $today->copy()->subDays(3)->addHours(9)->addMinutes(15),
            ],
            'sana' => [
                'patient_id' => "{$prefix}-P-009",
                'name' => 'Sana Javed',
                'cnic' => '35202-1000009-7',
                'phone' => '0301-5551009',
                'email' => strtolower("sana.{$prefix}@patients.demo"),
                'gender' => 'female',
                'age' => 24,
                'address' => 'Gulshan Block 7',
                'referred_by' => 'Dr. Hina Saeed',
                'created_at' => $today->copy()->subDays(4)->addHours(8)->addMinutes(50),
            ],
            'danish' => [
                'patient_id' => "{$prefix}-P-010",
                'name' => 'Danish Iqbal',
                'cnic' => '35202-1000010-1',
                'phone' => '0301-5551010',
                'email' => strtolower("danish.{$prefix}@patients.demo"),
                'gender' => 'male',
                'age' => 33,
                'address' => 'Wapda Town',
                'referred_by' => 'Dr. Salman Qazi',
                'created_at' => $today->copy()->subDays(6)->addHours(11)->addMinutes(10),
            ],
        ] as $key => $definition) {
            $createdAt = $definition['created_at'];
            unset($definition['created_at']);

            $patient = Patient::updateOrCreate(
                [
                    'lab_id' => $lab->id,
                    'patient_id' => $definition['patient_id'],
                ],
                array_merge($definition, [
                    'lab_id' => $lab->id,
                    'age_unit' => 'years',
                ])
            );

            $this->touchModel($patient, [
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            $patients[$key] = $patient;
        }

        return $patients;
    }

    private function seedOrders(Lab $lab, array $staff, array $patients, array $tests): void
    {
        $prefix = strtoupper(substr($lab->slug, 0, 3));
        $today = now()->startOfDay();
        $yesterday = $today->copy()->subDay();
        $twoDaysAgo = $today->copy()->subDays(2);

        $orderBlueprints = [
            [
                'sequence' => '001',
                'patient' => 'sara',
                'creator' => 'receptionist',
                'test_code' => 'FBS',
                'workflow' => 'pending_collection',
                'created_at' => $today->copy()->addHours(8)->addMinutes(10),
                'urgent' => false,
                'discount' => 50,
                'paid_amount' => 0,
                'payment_method' => 'cash',
                'referred_by' => 'Dr. Hina Saeed',
                'order_notes' => 'Patient asked to return after fasting period confirmation.',
                'invoice_notes' => 'Awaiting sample collection.',
            ],
            [
                'sequence' => '002',
                'patient' => 'umar',
                'creator' => 'receptionist',
                'test_code' => 'URINE-RE',
                'workflow' => 'rejected',
                'created_at' => $today->copy()->addHours(9)->addMinutes(5),
                'urgent' => false,
                'discount' => 0,
                'paid_amount' => 300,
                'payment_method' => 'cash',
                'referred_by' => 'Dr. Kamran Akhtar',
                'order_notes' => 'Initial sample leaked during transport.',
                'invoice_notes' => 'Partially collected at front desk.',
                'rejection_reason' => 'Leaking sample container.',
            ],
            [
                'sequence' => '003',
                'patient' => 'hina',
                'creator' => 'receptionist',
                'test_code' => 'HBSAG',
                'workflow' => 'collected',
                'created_at' => $today->copy()->addHours(10)->addMinutes(20),
                'urgent' => false,
                'discount' => 100,
                'paid_amount' => 1500,
                'payment_method' => 'jazzcash',
                'referred_by' => 'Dr. Salman Qazi',
                'order_notes' => 'Collected at collection desk, pending bench receipt.',
                'invoice_notes' => 'Paid online before collection.',
                'accession' => "{$prefix}-ACC-003",
            ],
            [
                'sequence' => '004',
                'patient' => 'ali',
                'creator' => 'receptionist',
                'test_code' => 'CR',
                'workflow' => 'received_unassigned',
                'created_at' => $today->copy()->addHours(11)->addMinutes(10),
                'urgent' => false,
                'discount' => 0,
                'paid_amount' => 450,
                'payment_method' => 'cash',
                'referred_by' => 'Dr. Saba Aamir',
                'order_notes' => 'Sample received and waiting for technician assignment.',
                'invoice_notes' => 'Half payment received.',
                'accession' => "{$prefix}-ACC-004",
            ],
            [
                'sequence' => '005',
                'patient' => 'noor',
                'creator' => 'admin',
                'test_code' => 'ALT',
                'workflow' => 'processing',
                'created_at' => $yesterday->copy()->addHours(14)->addMinutes(15),
                'urgent' => true,
                'discount' => 100,
                'paid_amount' => 0,
                'payment_method' => 'bank',
                'referred_by' => 'Dr. Hamid Raza',
                'order_notes' => 'Urgent liver profile, analyzer rerun pending.',
                'invoice_notes' => 'Corporate client, payment pending.',
                'accession' => "{$prefix}-ACC-005",
                'processing_notes' => 'Analyzer maintenance interrupted run; priority rerun flagged.',
            ],
            [
                'sequence' => '006',
                'patient' => 'hamza',
                'creator' => 'receptionist',
                'test_code' => 'HBA1C',
                'workflow' => 'draft',
                'created_at' => $today->copy()->addHours(12)->addMinutes(25),
                'urgent' => false,
                'discount' => 100,
                'paid_amount' => 1700,
                'payment_method' => 'easypaisa',
                'referred_by' => 'Dr. Tehmina Yusuf',
                'order_notes' => 'Draft result entered, awaiting review.',
                'invoice_notes' => 'Paid in full.',
                'accession' => "{$prefix}-ACC-006",
                'result' => [
                    'value' => '6.8',
                    'flag' => Result::FLAG_HIGH,
                    'remarks' => 'Elevated HbA1c, correlate with glucose log.',
                ],
            ],
            [
                'sequence' => '007',
                'patient' => 'maryam',
                'creator' => 'receptionist',
                'test_code' => 'HB',
                'workflow' => 'verified',
                'created_at' => $today->copy()->addHours(13)->addMinutes(10),
                'urgent' => false,
                'discount' => 0,
                'paid_amount' => 450,
                'payment_method' => 'cash',
                'referred_by' => 'Dr. Nabeel Shah',
                'order_notes' => 'Ready for report release.',
                'invoice_notes' => 'Paid at sample counter.',
                'accession' => "{$prefix}-ACC-007",
                'result' => [
                    'value' => '13.4',
                    'flag' => Result::FLAG_NORMAL,
                    'remarks' => 'Within expected range.',
                ],
            ],
            [
                'sequence' => '008',
                'patient' => 'bilal',
                'creator' => 'admin',
                'test_code' => 'WBC',
                'workflow' => 'released',
                'created_at' => $twoDaysAgo->copy()->addHours(15),
                'urgent' => true,
                'discount' => 50,
                'paid_amount' => 600,
                'payment_method' => 'cash',
                'referred_by' => 'Dr. Rabia Adeel',
                'order_notes' => 'Critical result released after consultant intimation.',
                'invoice_notes' => 'Balance due on pickup.',
                'accession' => "{$prefix}-ACC-008",
                'result' => [
                    'value' => '22.8',
                    'flag' => Result::FLAG_CRITICAL,
                    'remarks' => 'Critical value communicated to consultant.',
                ],
            ],
        ];

        foreach ($orderBlueprints as $blueprint) {
            $this->seedOrder($lab, $staff, $patients[$blueprint['patient']], $tests[$blueprint['test_code']], $prefix, $blueprint);
        }
    }

    private function seedOrder(Lab $lab, array $staff, Patient $patient, LabTest $test, string $prefix, array $blueprint): void
    {
        $createdAt = Carbon::parse($blueprint['created_at']);
        $creator = $staff[$blueprint['creator']];
        $totalAmount = (float) $test->price;
        $discount = (float) $blueprint['discount'];
        $netAmount = max($totalAmount - $discount, 0);
        $paidAmount = min((float) $blueprint['paid_amount'], $netAmount);

        $order = Order::updateOrCreate(
            ['order_number' => "{$prefix}-ORD-{$blueprint['sequence']}"],
            [
                'lab_id' => $lab->id,
                'patient_id' => $patient->id,
                'created_by' => $creator->id,
                'status' => Order::STATUS_PENDING,
                'is_urgent' => $blueprint['urgent'],
                'total_amount' => $totalAmount,
                'discount' => $discount,
                'net_amount' => $netAmount,
                'referred_by' => $blueprint['referred_by'],
                'notes' => $blueprint['order_notes'],
            ]
        );

        $this->touchModel($order, [
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ]);

        $order->items()
            ->where('test_id', '!=', $test->id)
            ->get()
            ->each
            ->delete();

        $item = OrderItem::updateOrCreate(
            [
                'order_id' => $order->id,
                'test_id' => $test->id,
            ],
            [
                'price' => $test->price,
                'status' => OrderItem::STATUS_PENDING,
                'assigned_to' => null,
                'started_at' => null,
                'due_at' => $createdAt->copy()->addHours((int) $test->turnaround_hours),
                'completed_at' => null,
                'processing_notes' => null,
            ]
        );

        $this->touchModel($item, [
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ]);

        $this->applyWorkflowState($order, $item, $test, $staff, $blueprint, $createdAt);

        $invoice = Invoice::updateOrCreate(
            ['order_id' => $order->id],
            [
                'lab_id' => $lab->id,
                'invoice_number' => "{$prefix}-INV-{$blueprint['sequence']}",
                'subtotal' => $totalAmount,
                'discount' => $discount,
                'total' => $netAmount,
                'paid_amount' => $paidAmount,
                'balance' => max($netAmount - $paidAmount, 0),
                'payment_status' => $this->resolvePaymentStatus($netAmount, $paidAmount),
                'payment_method' => $blueprint['payment_method'],
                'notes' => $blueprint['invoice_notes'],
            ]
        );

        $this->touchModel($invoice, [
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ]);
    }

    private function applyWorkflowState(Order $order, OrderItem $item, LabTest $test, array $staff, array $blueprint, Carbon $createdAt): void
    {
        $sampleType = $test->sample_type ?: 'General';
        $container = $this->defaultContainerFor($sampleType);
        $collectedAt = $createdAt->copy()->addMinutes(35);
        $receivedAt = $collectedAt->copy()->addMinutes(30);
        $startedAt = $receivedAt->copy()->addMinutes(20);
        $completedAt = $startedAt->copy()->addHours(2);
        $verifiedAt = $completedAt->copy()->addMinutes(25);
        $releasedAt = $verifiedAt->copy()->addMinutes(20);

        $sampleData = null;
        $resultData = null;
        $itemData = [
            'status' => OrderItem::STATUS_PENDING,
            'assigned_to' => null,
            'started_at' => null,
            'due_at' => $createdAt->copy()->addHours((int) $test->turnaround_hours),
            'completed_at' => null,
            'processing_notes' => null,
        ];
        $orderData = [
            'status' => Order::STATUS_PENDING,
            'collected_at' => null,
            'completed_at' => null,
        ];

        switch ($blueprint['workflow']) {
            case 'pending_collection':
                break;

            case 'rejected':
                $sampleData = [
                    'accession_number' => "{$order->order_number}-RJ",
                    'sample_type' => $sampleType,
                    'container' => $container,
                    'status' => Sample::STATUS_REJECTED,
                    'collected_by' => $staff['receptionist']->id,
                    'collected_at' => $collectedAt,
                    'received_by' => null,
                    'received_at' => null,
                    'rejection_reason' => $blueprint['rejection_reason'],
                    'is_recollection' => false,
                    'label_printed_at' => $collectedAt,
                ];
                break;

            case 'collected':
                $sampleData = [
                    'accession_number' => $blueprint['accession'],
                    'sample_type' => $sampleType,
                    'container' => $container,
                    'status' => Sample::STATUS_COLLECTED,
                    'collected_by' => $staff['receptionist']->id,
                    'collected_at' => $collectedAt,
                    'received_by' => null,
                    'received_at' => null,
                    'rejection_reason' => null,
                    'is_recollection' => false,
                    'label_printed_at' => $collectedAt,
                ];
                $itemData['status'] = OrderItem::STATUS_SAMPLE_COLLECTED;
                $orderData['status'] = Order::STATUS_SAMPLE_COLLECTED;
                $orderData['collected_at'] = $collectedAt;
                break;

            case 'received_unassigned':
                $sampleData = [
                    'accession_number' => $blueprint['accession'],
                    'sample_type' => $sampleType,
                    'container' => $container,
                    'status' => Sample::STATUS_RECEIVED,
                    'collected_by' => $staff['receptionist']->id,
                    'collected_at' => $collectedAt,
                    'received_by' => $staff['technician']->id,
                    'received_at' => $receivedAt,
                    'rejection_reason' => null,
                    'is_recollection' => false,
                    'label_printed_at' => $collectedAt,
                ];
                $itemData['status'] = OrderItem::STATUS_SAMPLE_COLLECTED;
                $orderData['status'] = Order::STATUS_SAMPLE_COLLECTED;
                $orderData['collected_at'] = $collectedAt;
                break;

            case 'processing':
                $sampleData = [
                    'accession_number' => $blueprint['accession'],
                    'sample_type' => $sampleType,
                    'container' => $container,
                    'status' => Sample::STATUS_RECEIVED,
                    'collected_by' => $staff['receptionist']->id,
                    'collected_at' => $collectedAt,
                    'received_by' => $staff['technician']->id,
                    'received_at' => $receivedAt,
                    'rejection_reason' => null,
                    'is_recollection' => false,
                    'label_printed_at' => $collectedAt,
                ];
                $itemData['status'] = OrderItem::STATUS_PROCESSING;
                $itemData['assigned_to'] = $staff['technician']->id;
                $itemData['started_at'] = $startedAt;
                $itemData['due_at'] = now()->subHours(3);
                $itemData['processing_notes'] = $blueprint['processing_notes'];
                $orderData['status'] = Order::STATUS_PROCESSING;
                $orderData['collected_at'] = $collectedAt;
                break;

            case 'draft':
                $sampleData = [
                    'accession_number' => $blueprint['accession'],
                    'sample_type' => $sampleType,
                    'container' => $container,
                    'status' => Sample::STATUS_RECEIVED,
                    'collected_by' => $staff['receptionist']->id,
                    'collected_at' => $collectedAt,
                    'received_by' => $staff['technician']->id,
                    'received_at' => $receivedAt,
                    'rejection_reason' => null,
                    'is_recollection' => false,
                    'label_printed_at' => $collectedAt,
                ];
                $resultData = [
                    'entered_by' => $staff['technician']->id,
                    'value' => $blueprint['result']['value'],
                    'unit' => $test->unit,
                    'normal_range' => $test->normal_range,
                    'is_abnormal' => $blueprint['result']['flag'] !== Result::FLAG_NORMAL,
                    'flag' => $blueprint['result']['flag'],
                    'remarks' => $blueprint['result']['remarks'],
                    'status' => Result::STATUS_DRAFT,
                    'is_verified' => false,
                    'verified_by' => null,
                    'verified_at' => null,
                    'released_by' => null,
                    'released_at' => null,
                    'critical_alerted_at' => null,
                ];
                $itemData['status'] = OrderItem::STATUS_COMPLETED;
                $itemData['assigned_to'] = $staff['technician']->id;
                $itemData['started_at'] = $startedAt;
                $itemData['completed_at'] = $completedAt;
                $itemData['processing_notes'] = 'Result entered and awaiting verification.';
                $orderData['status'] = Order::STATUS_PROCESSING;
                $orderData['collected_at'] = $collectedAt;
                break;

            case 'verified':
                $sampleData = [
                    'accession_number' => $blueprint['accession'],
                    'sample_type' => $sampleType,
                    'container' => $container,
                    'status' => Sample::STATUS_RECEIVED,
                    'collected_by' => $staff['receptionist']->id,
                    'collected_at' => $collectedAt,
                    'received_by' => $staff['technician']->id,
                    'received_at' => $receivedAt,
                    'rejection_reason' => null,
                    'is_recollection' => false,
                    'label_printed_at' => $collectedAt,
                ];
                $resultData = [
                    'entered_by' => $staff['technician']->id,
                    'value' => $blueprint['result']['value'],
                    'unit' => $test->unit,
                    'normal_range' => $test->normal_range,
                    'is_abnormal' => $blueprint['result']['flag'] !== Result::FLAG_NORMAL,
                    'flag' => $blueprint['result']['flag'],
                    'remarks' => $blueprint['result']['remarks'],
                    'status' => Result::STATUS_VERIFIED,
                    'is_verified' => true,
                    'verified_by' => $staff['incharge']->id,
                    'verified_at' => $verifiedAt,
                    'released_by' => null,
                    'released_at' => null,
                    'critical_alerted_at' => null,
                ];
                $itemData['status'] = OrderItem::STATUS_COMPLETED;
                $itemData['assigned_to'] = $staff['technician']->id;
                $itemData['started_at'] = $startedAt;
                $itemData['completed_at'] = $completedAt;
                $itemData['processing_notes'] = 'Verified and ready for release.';
                $orderData['status'] = Order::STATUS_PROCESSING;
                $orderData['collected_at'] = $collectedAt;
                break;

            case 'released':
                $sampleData = [
                    'accession_number' => $blueprint['accession'],
                    'sample_type' => $sampleType,
                    'container' => $container,
                    'status' => Sample::STATUS_RECEIVED,
                    'collected_by' => $staff['receptionist']->id,
                    'collected_at' => $collectedAt,
                    'received_by' => $staff['technician']->id,
                    'received_at' => $receivedAt,
                    'rejection_reason' => null,
                    'is_recollection' => false,
                    'label_printed_at' => $collectedAt,
                ];
                $resultData = [
                    'entered_by' => $staff['technician']->id,
                    'value' => $blueprint['result']['value'],
                    'unit' => $test->unit,
                    'normal_range' => $test->normal_range,
                    'is_abnormal' => $blueprint['result']['flag'] !== Result::FLAG_NORMAL,
                    'flag' => $blueprint['result']['flag'],
                    'remarks' => $blueprint['result']['remarks'],
                    'status' => Result::STATUS_RELEASED,
                    'is_verified' => true,
                    'verified_by' => $staff['incharge']->id,
                    'verified_at' => $verifiedAt,
                    'released_by' => $staff['incharge']->id,
                    'released_at' => $releasedAt,
                    'critical_alerted_at' => $blueprint['result']['flag'] === Result::FLAG_CRITICAL ? $releasedAt : null,
                ];
                $itemData['status'] = OrderItem::STATUS_COMPLETED;
                $itemData['assigned_to'] = $staff['technician']->id;
                $itemData['started_at'] = $startedAt;
                $itemData['completed_at'] = $completedAt;
                $itemData['processing_notes'] = 'Released to patient portal/print queue.';
                $orderData['status'] = Order::STATUS_COMPLETED;
                $orderData['collected_at'] = $collectedAt;
                $orderData['completed_at'] = $releasedAt;
                break;
        }

        $item->update($itemData);
        $this->touchModel($item, [
            'created_at' => $createdAt,
            'updated_at' => $orderData['completed_at'] ?? $itemData['completed_at'] ?? $itemData['started_at'] ?? $orderData['collected_at'] ?? $createdAt,
        ]);

        if ($sampleData) {
            $sample = Sample::updateOrCreate(
                ['order_item_id' => $item->id],
                array_merge($sampleData, ['lab_id' => $order->lab_id])
            );

            $sampleUpdatedAt = $sampleData['received_at']
                ?? $sampleData['collected_at']
                ?? $createdAt;

            $this->touchModel($sample, [
                'created_at' => $sampleData['collected_at'] ?? $createdAt,
                'updated_at' => $sampleData['status'] === Sample::STATUS_REJECTED ? $sampleUpdatedAt->copy()->addMinutes(10) : $sampleUpdatedAt,
            ]);
        } else {
            $item->sample()?->delete();
        }

        if ($resultData) {
            $result = Result::updateOrCreate(
                ['order_item_id' => $item->id],
                $resultData
            );

            $resultUpdatedAt = $resultData['released_at']
                ?? $resultData['verified_at']
                ?? $itemData['completed_at']
                ?? $createdAt;

            $this->touchModel($result, [
                'created_at' => $itemData['completed_at'] ?? $createdAt,
                'updated_at' => $resultUpdatedAt,
            ]);
        } else {
            $item->result()?->delete();
        }

        $order->update($orderData);
        $this->touchModel($order, [
            'created_at' => $createdAt,
            'updated_at' => $orderData['completed_at'] ?? $itemData['completed_at'] ?? $itemData['started_at'] ?? $orderData['collected_at'] ?? $createdAt,
        ]);
    }

    private function resolvePaymentStatus(float $total, float $paidAmount): string
    {
        if ($paidAmount <= 0) {
            return 'unpaid';
        }

        if ($paidAmount >= $total) {
            return 'paid';
        }

        return 'partial';
    }

    private function defaultContainerFor(string $sampleType): string
    {
        return match ($sampleType) {
            'Blood', 'Whole Blood' => 'EDTA',
            'Serum' => 'SST',
            'Fluoride Plasma' => 'Gray Top',
            'Urine' => 'Sterile Cup',
            default => 'Standard Container',
        };
    }

    private function touchModel(Model $model, array $attributes): void
    {
        $model->timestamps = false;
        $model->forceFill($attributes)->saveQuietly();
        $model->timestamps = true;
    }
}
