<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoices>
 */
class InvoicesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'invoice_number' => fake()->randomNumber(5),
            'invoice_date' => fake()->date(),
            'due_date' => fake()->date(),
            'total' => 4400,
            'discount' => 1000,
            'value_vat' => 400,
            'rate_vat' => fake()->randomElement(['15%','13%','10%','20%' ,'5%']),
            'amount_collection' => 50000,
            'amount_commission' => 5000,
            'status_id' => fake()->randomElement([1,2,3,4 ,5]),
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
            'section_id' => 1,
            'product_id' => 1,

            ];
    }
}
