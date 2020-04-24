<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Hash;
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
    static $password;
    $verified = $faker->randomElement([User::VERIFIED_USER, User::UNVERIFIED_USER]);
    $email_verified_at = ($verified) ? now() : null;
    $verification_token = ($verified) ? null : User::generateVerificationCode();

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => $email_verified_at,
        'password' => $password ?: $password = Hash::make('secret'),
        'remember_token' => Str::random(10),
        'verified' => $verified,
        'verification_token' => $verification_token,
        'admin' => $faker->randomElement([User::REGULAR_USER, User::ADMIN_USER]),
    ];
});
