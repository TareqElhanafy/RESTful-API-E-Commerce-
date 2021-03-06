<?php

namespace App\Providers;

use App\Buyer;
use App\Policies\BuyerPolicy;
use App\Policies\ProductPolicy;
use App\Policies\SellerPolicy;
use App\Policies\TransactionPolicy;
use App\Policies\UserPolicy;
use App\Product;
use App\Seller;
use App\Transaction;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
        Buyer::class=>BuyerPolicy::class,
        Seller::class=>SellerPolicy::class,
        User::class=>UserPolicy::class,
        Transaction::class=>TransactionPolicy::class,
        Product::class=>ProductPolicy::class,

    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();
         
        Passport::tokensExpireIn(Carbon::now()->addMinutes(30));
        Passport::refreshTokensExpireIn(Carbon::now()->addDays(30));
        
        Passport::tokensCan([
            'purchase-product' => 'create new transaction',
            'manage-products' => 'CRUD',
            'manage-account'=>'Read all data but not password and update data',
            'read-general'=>'read general in different controllers',
        ]);
    }
}
