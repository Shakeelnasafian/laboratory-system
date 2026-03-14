<?php

namespace Database\Seeders;

use App\Models\Lab;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DemoLabsSeeder extends Seeder
{
    public const DEMO_LAB_SLUGS = [
        'city-diagnostic-lab',
        'prime-care-laboratory',
    ];

    public function run(): void
    {
        $labs = [
            [
                'lab' => [
                    'name' => 'City Diagnostic Lab',
                    'slug' => 'city-diagnostic-lab',
                    'email' => 'info@citydiagnostic.demo',
                    'phone' => '0300-1112233',
                    'address' => '12 Main Boulevard, Gulberg',
                    'city' => 'Lahore',
                    'license_number' => 'CDL-2026-001',
                    'owner_name' => 'Dr. Ayesha Khan',
                    'header_text' => 'Accurate diagnostics for everyday care.',
                    'footer_text' => 'City Diagnostic Lab | ISO aligned internal processes',
                    'is_active' => true,
                    'subscription_expires_at' => now()->addYear()->toDateString(),
                ],
                'staff' => [
                    [
                        'role' => User::ROLE_LAB_ADMIN,
                        'name' => 'City Lab Admin',
                        'email' => 'admin.city@labsystem.demo',
                        'phone' => '0300-1112201',
                        'password' => 'password123',
                    ],
                    [
                        'role' => User::ROLE_LAB_INCHARGE,
                        'name' => 'City Lab Incharge',
                        'email' => 'incharge.city@labsystem.demo',
                        'phone' => '0300-1112202',
                        'password' => 'password123',
                    ],
                    [
                        'role' => User::ROLE_RECEPTIONIST,
                        'name' => 'City Reception',
                        'email' => 'reception.city@labsystem.demo',
                        'phone' => '0300-1112203',
                        'password' => 'password123',
                    ],
                    [
                        'role' => User::ROLE_TECHNICIAN,
                        'name' => 'City Technician',
                        'email' => 'tech.city@labsystem.demo',
                        'phone' => '0300-1112204',
                        'password' => 'password123',
                    ],
                ],
            ],
            [
                'lab' => [
                    'name' => 'Prime Care Laboratory',
                    'slug' => 'prime-care-laboratory',
                    'email' => 'info@primecare.demo',
                    'phone' => '0311-4445566',
                    'address' => '44 Shahrah-e-Faisal',
                    'city' => 'Karachi',
                    'license_number' => 'PCL-2026-002',
                    'owner_name' => 'Dr. Bilal Ahmed',
                    'header_text' => 'Timely lab reports with dependable turnaround.',
                    'footer_text' => 'Prime Care Laboratory | Serving clinics and walk-ins',
                    'is_active' => true,
                    'subscription_expires_at' => now()->addMonths(10)->toDateString(),
                ],
                'staff' => [
                    [
                        'role' => User::ROLE_LAB_ADMIN,
                        'name' => 'Prime Lab Admin',
                        'email' => 'admin.prime@labsystem.demo',
                        'phone' => '0311-4445501',
                        'password' => 'password123',
                    ],
                    [
                        'role' => User::ROLE_LAB_INCHARGE,
                        'name' => 'Prime Lab Incharge',
                        'email' => 'incharge.prime@labsystem.demo',
                        'phone' => '0311-4445502',
                        'password' => 'password123',
                    ],
                    [
                        'role' => User::ROLE_RECEPTIONIST,
                        'name' => 'Prime Reception',
                        'email' => 'reception.prime@labsystem.demo',
                        'phone' => '0311-4445503',
                        'password' => 'password123',
                    ],
                    [
                        'role' => User::ROLE_TECHNICIAN,
                        'name' => 'Prime Technician',
                        'email' => 'tech.prime@labsystem.demo',
                        'phone' => '0311-4445504',
                        'password' => 'password123',
                    ],
                ],
            ],
        ];

        foreach ($labs as $demoLab) {
            $lab = Lab::updateOrCreate(
                ['slug' => $demoLab['lab']['slug']],
                $demoLab['lab']
            );

            foreach ($demoLab['staff'] as $staffMember) {
                $user = User::updateOrCreate(
                    ['email' => $staffMember['email']],
                    [
                        'lab_id' => $lab->id,
                        'name' => $staffMember['name'],
                        'email' => $staffMember['email'],
                        'phone' => $staffMember['phone'],
                        'password' => Hash::make($staffMember['password']),
                        'email_verified_at' => now(),
                        'is_active' => true,
                        'remember_token' => Str::random(10),
                    ]
                );

                $user->syncRoles([$staffMember['role']]);
            }
        }

        if ($this->command) {
            $this->command->info('Demo laboratories and staff accounts seeded.');
            $this->command->table(
                ['Lab', 'Role', 'Email', 'Password'],
                collect($labs)
                    ->flatMap(fn (array $demoLab) => collect($demoLab['staff'])->map(
                        fn (array $staffMember) => [
                            $demoLab['lab']['name'],
                            $staffMember['role'],
                            $staffMember['email'],
                            $staffMember['password'],
                        ]
                    ))
                    ->all()
            );
        }
    }
}
