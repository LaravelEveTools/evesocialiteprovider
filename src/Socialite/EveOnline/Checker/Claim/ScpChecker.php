<?php


namespace LaravelEveTools\EveSocialiteProvider\Socialite\EveOnline\Checker\Claim;


use Jose\Component\Checker\ClaimChecker;
use Jose\Component\Checker\InvalidClaimException;

class ScpChecker implements ClaimChecker
{
    private const NAME = 'scp';

    /**
     * @var string[]
     */
    private $scopes;

    /**
     * ScpChecker constructor.
     *
     * @param array $scopes
     */
    public function __construct(array $scopes)
    {
        $this->scopes = $scopes;
    }

    /**
     * {@inheritdoc}
     */
    public function checkClaim($value): void
    {
        if (! is_array($value) && ! is_string($value))
            throw new InvalidClaimException('"scp" must be an array of scopes.', self::NAME, $value);

        if (! is_array($value))
            $value = [$value];

        if (! empty(array_diff($this->scopes, $value)))
            throw new InvalidClaimException('"scp" contains scopes which does not match requested ones or miss some requested scopes.', self::NAME, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function supportedClaim(): string
    {
        return self::NAME;
    }
}
