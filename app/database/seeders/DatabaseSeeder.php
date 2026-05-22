<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Customer;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $adminRole = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $managerRole = Role::create(['name' => 'manager', 'guard_name' => 'web']);

         $admin = User::factory()->create([
             'name' => 'Admin',
             'email' => 'admin@admin.com',
             'password' => Hash::make('password'),
         ]);
        $admin->assignRole('admin');

         $manager = User::factory()->create([
             'name' => 'Manager',
             'email' => 'user@user.com',
             'password' => Hash::make('password'),
         ]);
         $manager->assignRole('manager');

        Customer::factory(2)->create();


        Ticket::factory(4)->create();
    }
}
