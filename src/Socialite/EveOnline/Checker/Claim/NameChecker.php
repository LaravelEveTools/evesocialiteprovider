<?php


namespace LaravelEveTools\EveSocialiteProvider\Socialite\EveOnline\Checker\Claim;


use Jose\Component\Checker\ClaimChecker;
use Jose\Component\Checker\InvalidClaimException;

class NameChecker implements ClaimChecker
{
    private const NAME = 'name';

    public function checkClaim($value): void
    {
        if (! is_string($value))
            throw new InvalidClaimException('"name" must be a string.', self::NAME, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function supportedClaim(): string
    {
        return self::NAME;
    }

}
