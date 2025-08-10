<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $purchasePrice = $this->faker->numberBetween(1000000, 10000000);
        $sellingPrice = $purchasePrice + $this->faker->numberBetween(200000, 1500000);

        return [
            'name' => $this->faker->randomElement(['Samsung Galaxy', 'Realme', 'Infinix Note', 'Xiaomi Redmi']) . ' ' . $this->faker->numerify('##') . ' ' . $this->faker->randomElement(['Pro', 'Max', 'Lite']),
            'brand' => $this->faker->randomElement(['Samsung', 'Xiaomi', 'Realme', 'Infinix']),
            'purchase_price' => $purchasePrice,
            'selling_price' => $sellingPrice,
            'stock' => 0, // Stok awal kita set 0, nanti diisi oleh seeder
        ];
    }
}
