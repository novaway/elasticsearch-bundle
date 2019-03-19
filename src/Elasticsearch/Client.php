<?php
declare(strict_types=1);


namespace Novaway\ElasticsearchBundle\Elasticsearch;


use Novaway\ElasticsearchBundle\Event\SearchQuery;
use Novaway\ElasticsearchBundle\Event\SearchResult;
use Novaway\ElasticsearchBundle\Traits\EventDispatcherAwareTrait;

class Client extends \Elasticsearch\Client
{
    use EventDispatcherAwareTrait;

    /**
     * @inheritdoc
     */
    public function search($params = array()): array
    {
        $timestamp = (string)microtime();
        $this->dispatcher->dispatch(SearchQuery::NAME, new SearchQuery($params, $timestamp));
        $result = parent::search($params);
        $this->dispatcher->dispatch(SearchResult::NAME, new SearchResult($result, $timestamp));
        return $result;
    }
}
