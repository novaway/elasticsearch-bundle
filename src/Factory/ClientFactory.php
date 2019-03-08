<?php
declare(strict_types=1);


namespace Novaway\ElasticsearchBundle\Factory;


use Elasticsearch\Serializers\SerializerInterface;
use Novaway\ElasticsearchBundle\Elasticsearch\Client;
use Novaway\ElasticsearchBundle\Elasticsearch\ClientBuilder;

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
