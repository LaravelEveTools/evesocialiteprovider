<?php


namespace LaravelEveTools\EveSocialiteProvider\Socialite\EveOnline\Checker\Claim;


use Jose\Component\Checker\ClaimChecker;

class OwnerChecker implements ClaimChecker
{
    private const NAME = 'owner';

    /**
     * {@inheritdoc}
     */
    public function checkClaim($value): void
    {
        if (! is_string($value))
            throw new InvalidClaimException('"owner" must be a string.', self::NAME, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function supportedClaim(): string
    {
        return self::NAME;
    }
}
