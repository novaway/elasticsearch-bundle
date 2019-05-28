<?php

namespace Novaway\ElasticsearchBundle\Exception\Response;

use Elastica\Exception\ResponseException as ElasticaResponseException;
use Elastica\Exception\ElasticsearchException;
use Elastica\Exception\ExceptionInterface;
use Elastica\Request;
use Elastica\Response;

class ResponseException extends ElasticaResponseException implements ExceptionInterface
{
    /**
     * Construct Exception.
     *
     * @param \Elastica\Request $request
     * @param \Elastica\Response $response
     */
    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);
        $this->message = $response->getError() . "\n\n\n" . json_encode($response->getFullError(), JSON_PRETTY_PRINT) ;
    }
}
