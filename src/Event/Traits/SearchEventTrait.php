<?php

namespace Novaway\ElasticsearchBundle\Event\Traits;

trait SearchEventTrait
{
    /** @var array */
    private $body;
    /** @var string */
    private $timestamp;

    public function __construct(
        array $body,
        string $timestamp
    ) {
        $this->body = $body;
        $this->timestamp = $timestamp;
    }

    public function getBody(): array
    {
        return $this->body;
    }

    public function getTimestamp(): string
    {
        return $this->timestamp;
    }

    public static function getName(): string
    {
        return static::NAME;
    }
}
