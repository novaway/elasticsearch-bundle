<?php
declare(strict_types=1);

namespace Novaway\ElasticsearchBundle\DataCollector;

use Novaway\ElasticsearchBundle\Event\SearchQuery;
use Novaway\ElasticsearchBundle\Event\SearchResult;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

class ElasticsearchDataCollector extends DataCollector implements EventSubscriberInterface
{
    const QUERIES_KEY = 'queries';
    const RESULTS_KEY = 'results';

    public function collect(Request $request, Response $response, \Throwable $exception = null): void
    {
        // everything is collected via Events
    }

    public function getName(): string
    {
        return 'novaway_elasticsearch.data_collector';
    }

    public function reset(): void
    {
        $this->data = [
            self::QUERIES_KEY => [],
            self::RESULTS_KEY => [],
        ];
    }

    public function getQueries(): array
    {
        return $this->data[self::QUERIES_KEY] ?? [];
    }

    public function getResults(): array
    {
        return $this->data[self::RESULTS_KEY] ?? [];
    }

    public function onSearchQuery(SearchQuery $event): void
    {
        $this->data[self::QUERIES_KEY][$event->getTimestamp()] = $event->getBody();
    }

    public function onSearchResult(SearchResult $event): void
    {
        $this->data[self::RESULTS_KEY][$event->getTimestamp()] = $event->getBody();
    }

    public static function getSubscribedEvents(): array
    {
        return [
            SearchQuery::NAME => 'onSearchQuery',
            SearchResult::NAME => 'onSearchResult',
        ];
    }


}
