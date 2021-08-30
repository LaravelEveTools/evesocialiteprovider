<?php

namespace LaravelEveTools\EveSocialiteProvider;

use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use SocialiteProviders\Manager\SocialiteWasCalled;


class EveSsoServiceProvider extends EventServiceProvider
{

    protected $listen = [
        SocialiteWasCalled::class => [
            'LaravelEveTools\EveSocialiteProvider\Socialite\EveOnline\EveOnlineExtendSocialite@handle'
        ]
    ];

     public function register()
     {
         parent::register();
         $this->mergeConfigFrom(__DIR__.'/Config/esi.scopes.php', 'esi.scopes');
     }



}