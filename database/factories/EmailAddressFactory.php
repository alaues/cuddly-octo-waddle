<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\EmailAddress;
use Faker\Generator as Faker;

$factory->define(EmailAddress::class, function (Faker $faker) {
    return [
        'email' => $faker->email,
        'code' => $faker->randomNumber(4),
    ];
});
