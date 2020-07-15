<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
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
