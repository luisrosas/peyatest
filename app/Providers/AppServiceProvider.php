<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('League\Fractal\Manager', function () {
            $manager = new \League\Fractal\Manager;
            $manager->setSerializer(new \League\Fractal\Serializer\JsonApiSerializer(env('APP_URL') . '/api'));

            return $manager;
        });
    }
}
