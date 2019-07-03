<?php
declare(strict_types=1);


namespace Novaway\ElasticsearchBundle\Elastica\Traits;


use Elastica\ResultSet;
use Novaway\ElasticsearchBundle\Event\SearchQuery;
use Novaway\ElasticsearchBundle\Event\SearchResult;

/**
 * @method protected function dispatch(BaseEvent $event, string $eventName = null)
 */
trait SearchTrait
{
    public function search($query = '', $options = null): ResultSet
    {
        $timestamp = (string)microtime();

        $this->dispatch(new SearchQuery([
            'body' => $this->getQuery()->toArray() + $this->getOptions(),
            'type' => $this->getTypes(),
            'indices' => $this->getIndices(),
        ], $timestamp), SearchQuery::NAME);

        $result =  parent::search($query, $options);

        $this->dispatch(new SearchResult([
            'query_time' => $result->getResponse()->getQueryTime(),
            'response' => $result->getResponse()->getData()
        ], $timestamp), SearchResult::NAME);

        return $result;
    }
}
