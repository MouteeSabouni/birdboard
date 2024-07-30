<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Project::observe(ProjectObserver::class);
        // Task::observe(TaskObserver::class);

        // Carbon::setLocale(config('app.locale'));
        // $translator = Carbon::getTranslator();
        // $translator->addResource('array', require resource_path('lang/vendor/Carbon/custom.php'), config('app.locale'));
    }
}
