<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
			'categoryID' => 1,
			'minimumUnitID' => $this->faker->numberBetween(1,2),
			'maximumUnitID' => $this->faker->numberBetween(1,2),
			'productName' => $this->faker->word,
			'unitsInProduct' => $this->faker->numberBetween(1,20),
			'thresholdUnit' => $this->faker->numberBetween(10,200),
			'isSoldPackOrLoose' => $this->faker->numberBetween(1,2),
			'isUnitsInProductFixed' => $this->faker->numberBetween(0,1),
			'unitPurchasePrice' => '0.00',
			'unitSalePrice' => '0.00',
            'createdByUserID' => 1
        ];
    }
}
