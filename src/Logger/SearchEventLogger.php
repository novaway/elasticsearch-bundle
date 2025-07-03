<?php

namespace Novaway\ElasticsearchBundle\Logger;

use Novaway\ElasticsearchBundle\Event\Behaviors\SearchEvent;
use Novaway\ElasticsearchBundle\Event\SearchQuery;
use Novaway\ElasticsearchBundle\Event\SearchResult;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SearchEventLogger implements EventSubscriberInterface
{
    public function __construct(
        private LoggerInterface $logger
    ) {
    }

    public function logInfo(SearchEvent $event): void
    {
        $this->logger->info(sprintf('Novaway ES : Name %s; Ts %s; Body %s', $event::getName(), $event->getTimestamp(),
            json_encode($event->getBody())));
    }

    public static function getSubscribedEvents(): array
    {
        return [
            SearchQuery::NAME => 'logInfo',
            SearchResult::NAME => 'logInfo',
        ];
    }
}
