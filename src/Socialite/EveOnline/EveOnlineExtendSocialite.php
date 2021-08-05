<?php


namespace LaravelEveTools\EveSocialiteProvider\Socialite\EveOnline;

use SocialiteProviders\Manager\SocialiteWasCalled;

class EveOnlineExtendSocialite
{

    public function handle(SocialiteWasCalled $socialiteWasCalled)
    {
        $socialiteWasCalled->extendSocialite('eveonline', Provider::class);
    }

}
