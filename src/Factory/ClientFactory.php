<?php
declare(strict_types=1);


namespace Novaway\ElasticsearchBundle\Factory;


use Elasticsearch\Serializers\SerializerInterface;
use Novaway\ElasticsearchBundle\Elasticsearch\Client;
use Novaway\ElasticsearchBundle\Elasticsearch\ClientBuilder;

/**
 * Class ClientFactory
 * @package Novaway\ElasticsearchBundle\Factory
 * @deprecated Will be removed when the focus really shifts to supporting ruflin\Elastica
 */
class ClientFactory
{
    public function createClient(array $hosts, SerializerInterface $serializer = null): Client
    {
        /** @var ClientBuilder $builder */
        $builder = ClientBuilder::create()->setHosts($hosts);
        if (null !== $serializer) {
            $builder->setSerializer($serializer);
        }
        return $builder->build();
    }
}
