<?php

namespace Novaway\ElasticsearchBundle\Logger;

use Novaway\ElasticsearchBundle\Event\Behaviors\SearchEvent;
use Novaway\ElasticsearchBundle\Event\SearchQuery;
use Novaway\ElasticsearchBundle\Event\SearchResult;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SearchEventLogger implements EventSubscriberInterface
{
    /** @var LoggerInterface */
    private $logger;

    public function __construct(
        LoggerInterface $logger
    ) {
        $this->logger = $logger;
    }

    public function logInfo(SearchEvent $event)
    {
        $this->logger->info(sprintf('Novaway ES : Name %s; Ts %s; Body %s', $event::getName(), $event->getTimestamp(), json_encode($event->getBody())));
    }
    
    public static function getSubscribedEvents()
    {
        yield SearchQuery::NAME => 'logInfo';
        yield SearchResult::NAME => 'logInfo';
    }
}
