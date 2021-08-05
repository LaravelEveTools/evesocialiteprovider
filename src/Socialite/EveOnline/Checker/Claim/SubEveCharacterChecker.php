<?php


namespace LaravelEveTools\EveSocialiteProvider\Socialite\EveOnline\Checker\Claim;


use Jose\Component\Checker\ClaimChecker;
use Jose\Component\Checker\InvalidClaimException;

class SubEveCharacterChecker implements ClaimChecker
{
    private const NAME = 'sub';

    /**
     * {@inheritdoc}
     */
    public function checkClaim($value): void
    {
        if (! is_string($value))
            throw new InvalidClaimException('"sub" must be a string.', self::NAME, $value);

        if (preg_match('/^CHARACTER:EVE:[0-9]+$/', $value) !== 1)
            throw new InvalidClaimException('"sub" must be of the form CHARACTER:EVE:{character_id}', self::NAME, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function supportedClaim(): string
    {
        return self::NAME;
    }
}
