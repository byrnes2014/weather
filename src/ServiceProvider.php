<?php
namespace Byrnes2014\Weather;


use Illuminate\Contracts\Support\DeferrableProvider;

class ServiceProvider extends \Illuminate\Support\ServiceProvider implements DeferrableProvider
{


	public function register()
    {
        $this->app->singleton(Weather::class, function(){
            return new Weather(config('services.weather.key'));
        });

        $this->app->alias(Weather::class, 'weather');
    }

    public function provides()
    {
        return [Weather::class, 'weather'];
    }

}