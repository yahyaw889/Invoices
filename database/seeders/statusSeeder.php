<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class statusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        status::factory()->create(['name' => 'غير مدفوعه']);
        status::factory()->create(['name' => 'مدفوعه']);
        status::factory()->create(['name' => 'تم إلغاء']);
        status::factory()->create(['name' => 'مدفوعه جزئيا']);
        status::factory()->create(['name' => 'غير صالحه']);
    }
}
