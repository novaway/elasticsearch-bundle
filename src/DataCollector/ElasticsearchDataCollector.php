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
    /** @var array  */
    protected $queries = [];
    /** @var array  */
    protected $results = [];

    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        // everything is collected via Events
    }

    public function getName()
    {
        return 'novaway_elasticsearch.data_collector';
    }

    public function reset()
    {
        $this->queries = [];
        $this->results = [];
    }

    public function getQueries(): array
    {
        return $this->queries;
    }

    public function getResults(): array
    {
        return $this->results;
    }

    public function onSearchQuery(SearchQuery $event)
    {
        $this->queries[$event->getTimestamp()] = $event->getBody();
    }

    public function onSearchResult(SearchResult $event)
    {
        $this->results[$event->getTimestamp()] = $event->getBody();
    }

    public static function getSubscribedEvents()
    {
        yield SearchQuery::NAME => 'onSearchQuery';
        yield SearchResult::NAME => 'onSearchResult';
    }


}
