<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Models\Role;

class PermissionsAndUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()->make(PermissionRegistrar::class)->forgetCachedPermissions();

        // create roles
        // admin
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'api']);
        // customer
        $customerRole = Role::firstOrCreate(['name' => 'customer', 'guard_name' => 'api']);

        User::truncate();

        // create admin user
        $adminUser = User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@admin.com'
        ]);
        $adminUser->assignRole($adminRole);

        // create customer users
        for ($i = 0; $i < 3; $i++) {
            $customerUser = User::factory()->create();
            $customerUser->assignRole($customerRole);
        }
    }
}
