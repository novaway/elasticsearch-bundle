<?php
declare(strict_types=1);


namespace Novaway\ElasticsearchBundle\Event;


use Symfony\Component\EventDispatcher\Event;

class SearchQuery extends Event
{
    const NAME = 'novaway_elasticsearch.search_query';

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
}
