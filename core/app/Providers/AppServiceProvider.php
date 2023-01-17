<?php

namespace App\Providers;

use App\Helpers\LanguageHelper;
use App\Helpers\SidebarMenuHelper;
use App\Models\Themes;
use App\Observers\WalletBalanceObserver;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Modules\Blog\Entities\BlogCategory;
use Modules\Wallet\Entities\Wallet;
use function Psy\bin;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        app()->singleton('LandlordAdminMenu',function (){
           return  new SidebarMenuHelper();
        });
        app()->singleton('GlobalLanguage',function (){
           return  new LanguageHelper();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();
    }
}
