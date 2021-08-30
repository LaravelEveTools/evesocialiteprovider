<?php

namespace LaravelEveTools\EveImages;

use Illuminate\Support\ServiceProvider;

class EveImagesServiceProvider extends ServiceProvider
{

    public function boot(){
        $this->publishes([
            __DIR__ . '/resources/images' => public_path('eveimages')
        ]);
    }
}
