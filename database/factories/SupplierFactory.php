<?php

namespace Database\Factories;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SupplierFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Supplier::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'supplierName' => $this->faker->company,
			'phone' => $this->faker->e164PhoneNumber,
			'address' => $this->faker->address,
			'description' => $this->faker->paragraph($nbSentences = 3, $variableNbSentences = true),
            'createdByUserID' => 1
        ];
    }
}
