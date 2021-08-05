<?php


namespace LaravelEveTools\EveSocialiteProvider\Socialite\EveOnline\Checker\Header;


use Jose\Component\Checker\HeaderChecker;

class TypeChecker implements HeaderChecker
{
    private const HEADER_NAME = 'typ';

    /**
     * @var bool
     */
    private $protected_header = true;

    /**
     * @var string[]
     */
    private $supported_types;

    /**
     * TypeChecker constructor.
     *
     * @param string[] $supported_types
     * @param bool $protected_header
     */
    public function __construct(array $supported_types, bool $protected_header = true)
    {
        $this->supported_types = $supported_types;
        $this->protected_header = $protected_header;
    }

    /**
     * {@inheritdoc}
     */
    public function checkHeader($value): void
    {
        if (! is_string($value))
            throw new InvalidHeaderException('"typ" must be a string.', self::HEADER_NAME, $value);

        if (! in_array($value, $this->supported_types, true))
            throw new InvalidHeaderException('Unsupported type.', self::HEADER_NAME, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function supportedHeader(): string
    {
        return self::HEADER_NAME;
    }

    /**
     * {@inheritdoc}
     */
    public function protectedHeaderOnly(): bool
    {
        return $this->protected_header;
    }
}
