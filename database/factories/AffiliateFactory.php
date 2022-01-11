<?php

namespace Database\Factories;

use App\Models\Affiliate;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class AffiliateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Affiliate::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $company = Company::all()->random(1)->pluck('id')->first();

        return [
            'title' => $this->faker->city,
            'company_id' => $company,
        ];
    }
}
