<?php

namespace MichielGerritsen\LaravelNovaEmailOnEvent;

use Laravel\Nova\Nova;
use Laravel\Nova\Events\ServingNova;
use Illuminate\Support\ServiceProvider;
use MichielGerritsen\LaravelNovaEmailOnEvent\Events\WildcardEvent;
use MichielGerritsen\LaravelNovaEmailOnEvent\Nova\Resources\EmailEventsResource;

class MailOnEventServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Nova::serving(function (ServingNova $event) {
            Nova::script('email-on-event', __DIR__ . '/../dist/js/boot.js');

            Nova::resources([
                EmailEventsResource::class,
            ]);
        });

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        \Event::listen('*', WildcardEvent::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
