<?php
declare(strict_types=1);

namespace Novaway\ElasticsearchBundle\Elastica;


use Elastica\Client;
use Elastica\ResultSet\BuilderInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class Index extends \Elastica\Index
{
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher, Client $client, string $name)
    {
        parent::__construct($client, $name);
        $this->dispatcher = $dispatcher;
    }


    public function createSearch($query = '', $options = null, BuilderInterface $builder = null)
    {
        $search = new Search($this->dispatcher,  $this->getClient(), $builder);
        $search->addIndex($this);
        $search->setOptionsAndQuery($options, $query);

        return $search;
    }
}
