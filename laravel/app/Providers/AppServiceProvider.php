<?php

namespace App\Providers;

use App\Interfaces\AuthValidatorInterface;
use App\Interfaces\GifValidatorInterface;
use App\Interfaces\HttpServiceInterface;
use App\Services\HttpService;
use App\Validators\AuthValidator;
use App\Validators\GifValidator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(HttpServiceInterface::class, HttpService::class);        
        $this->app->bind(AuthValidatorInterface::class, AuthValidator::class);        
        $this->app->bind(GifValidatorInterface::class, GifValidator::class);        
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
