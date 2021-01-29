<?php
declare(strict_types=1);


namespace Novaway\ElasticsearchBundle\Elastica\Traits;


use Elastica\Exception\ClientException;
use Elastica\Exception\Connection\HttpException;
use Elastica\Response;
use Elastica\Request;
use Elastica\ResultSet;
use Novaway\ElasticsearchBundle\Event\ExceptionEvent;
use Novaway\ElasticsearchBundle\Event\SearchQuery;
use Novaway\ElasticsearchBundle\Event\SearchResult;

/**
 * @method protected function dispatch(BaseEvent $event, string $eventName = null)
 */
trait SearchTrait
{
    public function search($query = '', $options = null, string $method = Request::POST): ResultSet
    {
        $timestamp = (string)microtime();

        $this->dispatch(new SearchQuery([
            'body' => $this->getQuery()->toArray() + $this->getOptions(),
            'indices' => $this->getIndices(),
        ], $timestamp), SearchQuery::NAME);
        try {
            $result =  parent::search($query, $options);

            $this->dispatch(new SearchResult([
                'query_time' => $result->getResponse()->getQueryTime(),
                'response' => $result->getResponse()->getData()
            ], $timestamp), SearchResult::NAME);

            return $result;

        } catch (\Exception $e) {
            $this->dispatch(new ExceptionEvent([
                'body' => $this->getQuery()->toArray() + $this->getOptions(),
                'indices' => $this->getIndices(),
            ], $e), ExceptionEvent::NAME);
            switch (true) {
                case $e instanceof HttpException:
                    $response = $e->getResponse();
                default:
                    $response = new Response('');
            }
            return new ResultSet($response, $this->getQuery(), []);
        }

    }
}
