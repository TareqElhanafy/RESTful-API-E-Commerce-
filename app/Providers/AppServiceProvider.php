<?php

namespace App\Providers;

use App\Mail\UserCreated;
use App\Mail\UserUpdated;
use Illuminate\Support\ServiceProvider;
use App\Product;
use App\User;
use Illuminate\Support\Facades\Mail;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Product::updated(function($product){
            if ($product->quantity==0 && $product->isAvailable()) {
              $product->status=Product::UNAVAILABLE_PRODUCT;
               $product->save();
              }
        });

        User::created(function($user){
            Mail::to($user->email)->send(new UserCreated($user));
        });
        User::updated(function($user){
            if (!$user->isClean('email')) {
                Mail::to($user->email)->send(new UserUpdated($user));

            }
        });
    }
}
