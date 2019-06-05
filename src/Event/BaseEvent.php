<?php
declare(strict_types=1);

namespace Novaway\ElasticsearchBundle\Event;

if (class_exists('Symfony\Contracts\EventDispatcher\Event')) {
    class BaseEvent extends Symfony\Contracts\EventDispatcher\Event
    {
    }
} else {
    class BaseEvent extends Symfony\Component\EventDispatcher\Event
    {
    }
}
