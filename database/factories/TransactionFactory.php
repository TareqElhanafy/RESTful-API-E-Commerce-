<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use App\Seller;
use App\Transaction;
use Faker\Generator as Faker;

$factory->define(Transaction::class, function (Faker $faker) {
$seller=Seller::has('products')->get()->random();
$buyer=User::all()->except($seller->id)->random();
    return [
        'quantity'=>$faker->numberBetween(1,5),
        'product_id'=>$seller->products->random()->id,
        'buyer_id'=>$buyer->id
    ];
});