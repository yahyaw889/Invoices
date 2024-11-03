<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DefaultUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Creating Super Admin User
        $superAdmin = User::query()->create([
            'name' => 'yahya wael',
            'email' => 'yahyaw889@gmail.com',
            'password' => Hash::make('123456789')
        ]);
        $superAdmin->assignRole('Super Admin');

        // Creating Admin User
        $admin = User::query()->create([
            'name' => 'ebrahim samy',
            'email' => 'samy@gmail.com',
            'password' => Hash::make('123456789')
        ]);
        $admin->assignRole('Admin');

        // Creating Product Manager User
        $productManager = User::query()->create([
            'name' => 'mustafa elkoky',
            'email' => 'elkoky@gmail.com',
            'password' => Hash::make('123456789')
        ]);
        $productManager->assignRole('Product Manager');

        // Creating Application User
        $user = User::query()->create([
            'name' => 'user',
            'email' => 'user@gmail.com',
            'password' => Hash::make('123456789')
        ]);
        $user->assignRole('User');
    }
}
