<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        //buat permission
        Permission::create(['name' => 'add-user']);
        Permission::create(['name' => 'edit-user']);
        Permission::create(['name' => 'delete-user']);
        Permission::create(['name' => 'read-user']);

        Permission::create(['name' => 'add-category']);
        Permission::create(['name' => 'edit-category']);
        Permission::create(['name' => 'delete-category']);
        Permission::create(['name' => 'read-category']);

        Permission::create(['name' => 'add-task']);
        Permission::create(['name' => 'edit-task']);
        Permission::create(['name' => 'delete-task']);
        Permission::create(['name' => 'read-task']);

        //buat Role
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'user']);

        //Admin
        $roleAdmin = Role::findByName('admin');
        $roleAdmin->givePermissionTo('add-user');
        $roleAdmin->givePermissionTo('edit-user');
        $roleAdmin->givePermissionTo('delete-user');
        $roleAdmin->givePermissionTo('read-user');

        $roleAdmin->givePermissionTo('add-category');
        $roleAdmin->givePermissionTo('edit-category');
        $roleAdmin->givePermissionTo('delete-category');
        $roleAdmin->givePermissionTo('read-category');

        $roleAdmin->givePermissionTo('add-task');
        $roleAdmin->givePermissionTo('edit-task');
        $roleAdmin->givePermissionTo('delete-task');
        $roleAdmin->givePermissionTo('read-task');


        //User
        $roleUser = Role::findByName('user');
        $roleUser->givePermissionTo('add-task');
        $roleUser->givePermissionTo('edit-task');
        $roleUser->givePermissionTo('delete-task');
        $roleUser->givePermissionTo('read-task');

        $admin = User::create([
            'name' => 'Yusuf Abdul Rahman',
            'username' => 'yusuf8',
            'email' => 'yusufarrr6@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'),
        ]);
        $admin->assignRole('admin');

        $user = User::create([
            'name' => 'Aldi',
            'username' => 'aldi8',
            'email' => 'aldi@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'),
        ]);
        $user->assignRole('user');

        $user = User::create([
            'name' => 'Alya',
            'username' => 'Alya8',
            'email' => 'alya@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'),
        ]);
        $user->assignRole('user');
    }
}
