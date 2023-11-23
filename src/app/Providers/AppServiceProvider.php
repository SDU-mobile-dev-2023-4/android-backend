<?php

namespace App\Providers;

use App\Models\Expense;
use App\Models\GroupUser;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->register(\L5Swagger\L5SwaggerServiceProvider::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Observers
        Expense::observe(\App\Observers\ExpenseObserver::class);
        GroupUser::observe(\App\Observers\GroupUserObserver::class);
    }
}
