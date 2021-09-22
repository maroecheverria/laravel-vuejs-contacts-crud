<?php

namespace Database\Factories;


use App\Domains\Contact\Models\ContactPhone;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactPhoneFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ContactPhone::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'number' => $this->faker->phoneNumber(),
        ];
    }
}
