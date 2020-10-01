<?php
declare(strict_types=1);

namespace Novaway\ElasticsearchBundle\Event;

use Elastica\Query;
use Novaway\ElasticsearchBundle\Event\Behaviors\SearchEvent;

class ExceptionEvent extends BaseEvent
{
    const NAME = 'novaway_elasticsearch.exception_event';
    /** @var array  */
    protected $queryData;
    /** @var \Exception  */
    protected $exception;

    public function __construct(
        array $queryData,
        \Exception $exception
    )
    {
        $this->queryData = $queryData;
        $this->exception = $exception;
    }

    public function getQueryData(): array
    {
        return $this->queryData;
    }

    public function getException(): \Exception
    {
        return $this->exception;
    }
}
