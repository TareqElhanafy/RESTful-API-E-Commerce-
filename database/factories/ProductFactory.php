<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Product;
use App\User;

use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'name'=>$faker->word(),
        'description'=>$faker->paragraph(3),
        'quantity'=>$faker->numberBetween(1,10),
        'seller_id'=>User::all()->random()->id,
        'image'=>$faker->randomElement(['1.jpg','2.jpg','3.jpg']),
        'status'=>$faker->randomElement([Product::UNAVAILABLE_PRODUCT,Product::AVAILABLE_PRODUCT]),

    ];
});
