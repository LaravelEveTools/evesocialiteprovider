<?php


namespace LaravelEveTools\EveSocialiteProvider\Socialite\EveOnline\Checker\Claim;


use Jose\Component\Checker\ClaimChecker;
use Jose\Component\Checker\InvalidClaimException;

class AzpChecker implements ClaimChecker
{
    private const NAME = 'azp';

    private $client_id;

    public function __construct(string $client_id)
    {
        $this->client_id = $client_id;
    }

    public function checkClaim($value): void
    {
        if(! is_string($value))
            throw new InvalidClaimException('"azp" must be a string.', self::NAME, $value);

        if($value !== $this->client_id)
            throw new InvalidClaimException('"azp" must math the originating application.', self::NAME, $value);
    }

    public function supportedClaim(): string
    {
        return self::NAME;
    }
}
