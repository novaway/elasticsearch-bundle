<?php
declare(strict_types=1);

namespace Novaway\ElasticsearchBundle\Elastica;


use Elastica\Client;
use Novaway\ElasticsearchBundle\Elastica\Traits\IndexTrait;

use Symfony\Component\EventDispatcher\EventDispatcherInterface as OldEventDispatcherInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface as NewEventDispatcherInterface;

if (in_array('\Symfony\Contracts\EventDispatcher\EventDispatcherInterface', class_implements('\Symfony\Component\EventDispatcher\EventDispatcherInterface'))) {
    class Index extends \Elastica\Index
    {
        use IndexTrait;
        /**
         * @var EventDispatcherInterface
         */
        private $dispatcher;

        public function __construct(NewEventDispatcherInterface $dispatcher, Client $client, string $name)
        {
            parent::__construct($client, $name);
            $this->dispatcher = $dispatcher;
        }

    }
} else {
    class Index extends \Elastica\Index
    {
        use IndexTrait;
        /**
         * @var EventDispatcherInterface
         */
        private $dispatcher;

        public function __construct(OldEventDispatcherInterface $dispatcher, Client $client, string $name)
        {
            parent::__construct($client, $name);
            $this->dispatcher = $dispatcher;
        }

    }
}
