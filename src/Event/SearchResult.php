<?php
declare(strict_types=1);

namespace Novaway\ElasticsearchBundle\Event;

use Novaway\ElasticsearchBundle\Event\Behaviors\SearchEvent;
use Novaway\ElasticsearchBundle\Event\Traits\SearchEventTrait;

class SearchResult extends BaseEvent implements SearchEvent
{
    use SearchEventTrait;

    const NAME = 'novaway_elasticsearch.search_result';
}
