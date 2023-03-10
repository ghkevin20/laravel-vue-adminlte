<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    $gender = $faker->randomElement(['Male', 'Female']);

    $created_at = strtotime(rand(1, 12) . '/01/' . date('Y') . ' 08:00:00');

    $updated_at = strtotime(rand(1, 12) . '/01/' . date('Y') . ' 08:00:00');

    return [
        'name' => $faker->name($gender),
        'gender' => $gender,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),
        'created_at' => date('Y-m-d H:i:s', $created_at),
        'updated_at' => date('Y-m-d H:i:s', $updated_at)
    ];
});
