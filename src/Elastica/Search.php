<?php
declare(strict_types=1);

namespace Novaway\ElasticsearchBundle\Elastica;

use Elastica\ResultSet;
use Elastica\Client;
use Elastica\ResultSet\BuilderInterface;
use Novaway\ElasticsearchBundle\Event\SearchQuery;
use Novaway\ElasticsearchBundle\Event\SearchResult;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class Search extends \Elastica\Search
{
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher, Client $client, BuilderInterface $builder = null)
    {
        parent::__construct($client, $builder);
        $this->dispatcher = $dispatcher;
    }

    public function search($query = '', $options = null): ResultSet
    {
        $timestamp = (string)microtime();
        $this->dispatcher->dispatch(SearchQuery::NAME, new SearchQuery([
            'body' => $this->getQuery()->toArray() + $this->getOptions(),
            'type' => $this->getTypes(),
            'indices' => $this->getIndices(),
        ], $timestamp));
        $result =  parent::search($query, $options);

        $this->dispatcher->dispatch(SearchResult::NAME, new SearchResult([
            'query_time' => $result->getResponse()->getQueryTime(),
            'response' => $result->getResponse()->getData()
        ], $timestamp));

        return $result;
    }

}
