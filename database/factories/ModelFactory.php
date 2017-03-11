<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});


$factory->define(\App\Cart::class, function(\Faker\Generator $faker){
    $purchased = $faker->boolean();
    if(\App\User::count())
        $user = \App\User::inRandomOrder()->first();
    else
        $user = factory(\App\User::class)->create();
    return [
        'user_id'=>$user->id,
        'purchased'=> $purchased,
        'sales_date'=> $purchased ? $faker->dateTime : null,
        'sales_price'=>$purchased ? $faker->randomFloat() : null
    ];
});

$factory->define(\App\Product::class, function(\Faker\Generator $faker){
    return [
        'title'=>$faker->word,
        'description'=>$faker->paragraph,
        'price'=>$faker->randomFloat(),
        'available'=>$faker->boolean()
    ];
});

$factory->define(\App\CartItem::class, function(\Faker\Generator $faker){
    if(\App\Cart::count())
        $cart = \App\Cart::inRandomOrder()->first();
    else
        $cart = factory(\App\Cart::class)->create();

    if(\App\Product::count())
        $product = \App\Product::inRandomOrder()->first();
    else
        $product = factory(\App\Product::class)->create();
    return [
        'cart_id'=>$cart->id,
        'product_id'=>$product->id,
        'quantity'=>$faker->numberBetween(0, 100)
    ];
});
