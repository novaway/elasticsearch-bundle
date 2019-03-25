<?php

namespace Novaway\ElasticsearchBundle\Event\Behaviors;

interface SearchEvent
{
    public function getBody(): array;

    public function getTimestamp(): string;

    public static function getName(): string;
}
