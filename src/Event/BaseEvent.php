<?php
declare(strict_types=1);

namespace Novaway\ElasticsearchBundle\Event;

if (in_array('Symfony\Contracts\EventDispatcher\EventDispatcherInterface', class_implements('\Symfony\Component\EventDispatcher\EventDispatcherInterface'))) {
    class BaseEvent extends \Symfony\Contracts\EventDispatcher\Event
    {
    }
} else {
    class BaseEvent extends \Symfony\Component\EventDispatcher\Event
    {
    }
}
