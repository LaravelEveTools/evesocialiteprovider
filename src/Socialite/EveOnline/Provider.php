<?php

namespace LaravelEveTools\EveSocialiteProvider\Socialite\EveOnline;

use LaravelEveTools\EveImages\Image;
use LaravelEveTools\EveSocialiteProvider\Socialite\EveOnline\Checker\Claim\AzpChecker;
use LaravelEveTools\EveSocialiteProvider\Socialite\EveOnline\Checker\Claim\NameChecker;
use LaravelEveTools\EveSocialiteProvider\Socialite\EveOnline\Checker\Claim\OwnerChecker;
use LaravelEveTools\EveSocialiteProvider\Socialite\EveOnline\Checker\Claim\ScpChecker;
use LaravelEveTools\EveSocialiteProvider\Socialite\EveOnline\Checker\Claim\SubEveCharacterChecker;
use LaravelEveTools\EveSocialiteProvider\Socialite\EveOnline\Checker\Header\TypeChecker;
use SocialiteProviders\Manager\OAuth2\AbstractProvider;
use SocialiteProviders\Manager\OAuth2\User;
use Jose\Component\Core\JWKSet;
use Jose\Easy\Load;

class Provider extends AbstractProvider
{

    public const IDENTIFIER = 'EVE';

    protected $scopeSeparator = ' ';

    protected $authUrl = 'https://login.eveonline.com/v2/oauth/authorize';

    protected $scopes = [];

    protected function getTokenUrl(){
        return 'https://login.eveonline.com/v2/oauth/token';
    }

    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase($this->authUrl, $state);
    }

    protected function getUserByToken($token){
        return $this->validateJwtToken($token);
    }

    private function validateJwtToken(string $token): array
    {
        $sets = $this->getJwkSets();

        $jwk_sets = JWKSet::createFromKeyData($sets);

        $jws = Load::jws($token)
            ->algs(['RS256', 'ES256', 'HS256'])
            ->exp()
            ->iss('login.eveonline.com')
            ->header('typ', new TypeChecker(['JWT'], true))
            ->claim('scp', new ScpChecker($this->scopes))
            ->claim('sub', new SubEveCharacterChecker())
            ->claim('azp', new AzpChecker(config('services.eveonline.client_id')))
            ->claim('name', new NameChecker())
            ->claim('owner', new OwnerChecker())
            ->keyset($jwk_sets)
            ->run();

        return $jws->claims->all();
    }

    private function getJwkSets()
    {
        $jwk_uri = $this->getJwkUri();

        $response = $this->getHttpClient()
            ->get($jwk_uri);

        return json_decode($response->getBody(), true);
    }

    private function getJwkUri()
    {
        $response = $this->getHttpClient()
            ->get('https://login.eveonline.com/.well-known/oauth-authorization-server');

        $metadata = json_decode($response->getBody());

        return $metadata->jwks_uri;
    }

    /**
     * @param string $code
     * @return array|string[]
     */
    protected function getTokenFields($code)
    {
        return array_merge(parent::getTokenFields($code), ['grant_type'=>'authorization_code']);
    }

    protected function mapUserToObject(array $user)
    {
        $avatar = asset('img/evewho.png');
        $character_id = strtr($user['sub'], ['CHARACTER:EVE:' => '']);

        try {
            $avatar = (new Image('characters', $character_id, 128))->url(128);
        } catch (\Exception $e) {
            logger()->error($e->getMessage(), $e->getTrace());
        }

        return (new User)->setRaw($user)->map([
            'id'                   => $character_id,
            'name'                 => $user['name'],
            'nickname'             => $user['name'],
            'character_owner_hash' => $user['owner'],
            'scopes'               => is_array($user['scp']) ? $user['scp'] : [$user['scp']],
            'expires_on'           => $user['exp'],
            'avatar'               => $avatar,
        ]);
    }
}
