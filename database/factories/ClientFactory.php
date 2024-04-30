<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Client;
use App\User;
use Faker\Generator as Faker;

$factory->define(Client::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'phone' => $faker->phoneNumber,
        'address' => $faker->streetAddress,
        'city' => $faker->city,
        'postcode' => $faker->postcode,
        'user_id' => function () {
            return factory(User::class)->create()->id;
        },
    ];
});
