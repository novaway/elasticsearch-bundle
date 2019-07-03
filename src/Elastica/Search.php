<?php
declare(strict_types=1);

namespace Novaway\ElasticsearchBundle\Elastica;

use Elastica\Client;
use Elastica\ResultSet\BuilderInterface;
use Novaway\ElasticsearchBundle\Elastica\Traits\SearchTrait;
use Novaway\ElasticsearchBundle\Event\BaseEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface as OldEventDispatcherInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface as NewEventDispatcherInterface;

if (in_array('Symfony\Contracts\EventDispatcher\EventDispatcherInterface', class_implements('\Symfony\Component\EventDispatcher\EventDispatcherInterface'))) {
    class Search extends \Elastica\Search
    {
        use SearchTrait;
        /**
         * @var NewEventDispatcherInterface
         */
        private $dispatcher;

        public function __construct(NewEventDispatcherInterface $dispatcher, Client $client, BuilderInterface $builder = null)
        {
            parent::__construct($client, $builder);
            $this->dispatcher = $dispatcher;
        }

        protected function dispatch(BaseEvent $event, string $eventName = null)
        {
            $this->dispatcher->dispatch($event, $eventName);
        }

    }
} else {
    class Search extends \Elastica\Search
    {
        use SearchTrait;
        /**
         * @var OldEventDispatcherInterface
         */
        private $dispatcher;

        public function __construct(OldEventDispatcherInterface $dispatcher, Client $client, BuilderInterface $builder = null)
        {
            parent::__construct($client, $builder);
            $this->dispatcher = $dispatcher;
        }

        protected function dispatch(BaseEvent $event, string $eventName = null)
        {
            $this->dispatcher->dispatch($eventName, $event);
        }

    }
}
