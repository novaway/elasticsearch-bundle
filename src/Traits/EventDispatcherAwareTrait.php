<?php
declare(strict_types=1);


namespace Novaway\ElasticsearchBundle\Traits;


use Symfony\Component\EventDispatcher\EventDispatcherInterface as OldEventDispatcherInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface as NewEventDispatcherInterface;
use Symfony\Contracts\Service\Attribute\Required;

if (in_array('Symfony\Contracts\EventDispatcher\EventDispatcherInterface', class_implements('\Symfony\Component\EventDispatcher\EventDispatcherInterface'))) {
    trait EventDispatcherAwareTrait
    {
        /** @var NewEventDispatcherInterface */
        protected $dispatcher;

        /**
         * @required
         */
        #[Required]
        public function setDispatcher(NewEventDispatcherInterface $dispatcher)
        {
            $this->dispatcher = $dispatcher;
        }
    }
} else {
    trait EventDispatcherAwareTrait
    {
        /** @var OldEventDispatcherInterface */
        protected $dispatcher;

        /**
         * @required
         */
        public function setDispatcher(OldEventDispatcherInterface $dispatcher)
        {
            $this->dispatcher = $dispatcher;
        }
    }
}

