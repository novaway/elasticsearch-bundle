<?php
declare(strict_types=1);

namespace Novaway\ElasticsearchBundle\Elasticsearch;

use Elasticsearch\Namespaces\AbstractNamespace;
use Elasticsearch\Transport;

/**
 * @method Client build()
 * @deprecated Will be removed when the focus really shifts to supporting ruflin\Elastica
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
