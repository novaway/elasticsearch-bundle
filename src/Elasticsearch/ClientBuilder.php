<?php
declare(strict_types=1);

namespace Novaway\ElasticsearchBundle\Elasticsearch;

use Elasticsearch\Namespaces\AbstractNamespace;
use Elasticsearch\Transport;

/**
 * @method Client build()
 */
class ClientBuilder extends \Elasticsearch\ClientBuilder
{
    /**
     * @param Transport $transport
     * @param callable $endpoint
     * @param AbstractNamespace[] $registeredNamespaces
     *
     * @return Client
     */
    protected function instantiate(Transport $transport, callable $endpoint, array $registeredNamespaces)
    {   
        return new Client($transport, $endpoint, $registeredNamespaces);
    }
}
