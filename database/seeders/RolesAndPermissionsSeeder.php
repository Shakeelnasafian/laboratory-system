<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create roles
        $roles = [
            User::ROLE_SUPERADMIN,
            User::ROLE_LAB_ADMIN,
            User::ROLE_LAB_INCHARGE,
            User::ROLE_RECEPTIONIST,
            User::ROLE_TECHNICIAN,
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // Create Super Admin user
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@labsystem.pk'],
            [
                'name'      => 'Super Admin',
                'password'  => bcrypt('admin@12345'),
                'lab_id'    => null,
                'is_active' => true,
            ]
        );

        $superAdmin->assignRole(User::ROLE_SUPERADMIN);

        $this->command->info('✅ Roles created and Super Admin seeded.');
        $this->command->info('   Email: admin@labsystem.pk');
        $this->command->info('   Password: admin@12345');
    }
}
