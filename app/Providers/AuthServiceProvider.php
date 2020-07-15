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
        Buyer::class => BuyerPolicy::class,
        Seller::class => SellerPolicy::class,
        User::class => UserPolicy::class,
        Transaction::class => TransactionPolicy::class,
        Product::class => ProductPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('admin-actions', function ($user) {
            return $user->isAdmin();
        });

        Passport::routes();
        Passport::tokensExpireIn(Carbon::now()->addMinutes(30));
        Passport::refreshTokensExpireIn(Carbon::now()->addDays(15));
        Passport::enableImplicitGrant();
        Passport::tokensCan([
            'puchase-product' => 'Create a new transaction for a specific product',
            'manage-products' => 'Create, read, update and delete products',
            'manage-account' => 'Read your acount data, id , name , email, if verified, and admin (cannot read password). Modify yout account data
                                (email, and password). Cannot delete your account',
            'read-general' => 'Read general information, line purchasing categoies, purchased products, selling products, selling categories, your
                            transactions, (purchases and sales).'
        ]);
    }
}
