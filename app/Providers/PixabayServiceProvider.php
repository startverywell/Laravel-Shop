<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use GuzzleHttp\Client as GuzzleClient;

class PixabayServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->singleton(Pixabay::class, function ($app) {
            $guzzle = new GuzzleClient([
                'base_uri' => 'https://pixabay.com/api/',
            ]);
            return new Pixabay(
                $guzzle,
                env('PIXABAY_API_KEY')
            );
        });
    }

}

