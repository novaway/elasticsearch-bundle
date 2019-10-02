<?php
declare(strict_types=1);


namespace Novaway\ElasticsearchBundle\Elastica;

use Novaway\ElasticsearchBundle\Elastica\Traits\ClientTrait;
use Symfony\Component\EventDispatcher\EventDispatcherInterface as OldEventDispatcherInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface as NewEventDispatcherInterface;

if (in_array('Symfony\Contracts\EventDispatcher\EventDispatcherInterface', class_implements('\Symfony\Component\EventDispatcher\EventDispatcherInterface'))) {
    class Client extends \Elastica\Client
    {
        use ClientTrait;
        /** @var EventDispatcherInterface */
        protected $eventDispatcher;

        /**
         * @required
         */
        public function setEventDispatcher(NewEventDispatcherInterface $eventDispatcher)
        {
            $this->eventDispatcher = $eventDispatcher;
        }

    }
} else {
    class Client extends \Elastica\Client
    {
        use ClientTrait;
        /** @var EventDispatcherInterface */
        protected $eventDispatcher;

        /**
         * @required
         */
        public function setEventDispatcher(OldEventDispatcherInterface $eventDispatcher)
        {
            $this->eventDispatcher = $eventDispatcher;
        }
    }
}
