<?php

namespace Database\Seeders;

use App\Models\Invoices;
use App\Models\Product;
use App\Models\Section;
use App\Models\Session;
use App\Models\status;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'yahya wael',
            'email' => 'yahyaw889@gmail.com',
            'password'=> Hash::make('123456789')  // Hash the password
        ]);
        Section::factory(5)->create();
        Product::factory(25)->create();
        status::factory()->create(['name' => 'غير مدفوعه']);
        status::factory()->create(['name' => 'مدفوعه']);
        status::factory()->create(['name' => 'تم إلغاء']);
        status::factory()->create(['name' => 'مدفوعه جزئيا']);
        status::factory()->create(['name' => 'غير صالحه']);

        Invoices::factory(10)->create();

    }
}
