<?php
declare(strict_types=1);

namespace Novaway\ElasticsearchBundle\DataCollector;

use Novaway\ElasticsearchBundle\Event\SearchQuery;
use Novaway\ElasticsearchBundle\Event\SearchResult;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

class ElasticsearchDataCollector  extends DataCollector implements EventSubscriberInterface
{
    const QUERIES_KEY = 'queries';
    const RESULTS_KEY  = 'results';

    public function collect(Request $request, Response $response, \Exception $exception = null)
    {

    }

    public function getName()
    {
        return 'novaway_elasticsearch.data_collector';
    }

    public function reset()
    {
        $this->data = [];
    }

    public function getQueries(): array
    {
        return $this->data[self::QUERIES_KEY];
    }

    public function getResults(): array
    {
        return $this->data[self::RESULTS_KEY];
    }

    public function onSearchQuery(SearchQuery $event)
    {
        $this->data[self::QUERIES_KEY][$event->getTimestamp()] = $event->getBody();
    }

    public function onSearchResult(SearchResult $event)
    {
        $this->data[self::RESULTS_KEY][$event->getTimestamp()] = $event->getBody();
    }

    public static function getSubscribedEvents()
    {
        yield SearchQuery::NAME => 'onSearchQuery';
        yield SearchResult::NAME => 'onSearchResult';
    }


}
