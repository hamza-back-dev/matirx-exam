<?php

namespace App\Providers;

use App\Events\UserSaved;
use App\Listeners\SaveUserBackgroundInformation;
use App\Service\UserService;
use App\Service\UserServiceInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    protected $listen = [
        UserSaved::class => [
            SaveUserBackgroundInformation::class,
        ],
    ];
    
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserServiceInterface::class, UserService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
