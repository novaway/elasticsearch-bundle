<?php
declare(strict_types=1);

namespace Novaway\ElasticsearchBundle\Logger;

use Novaway\ElasticsearchBundle\Event\Behaviors\SearchEvent;
use Novaway\ElasticsearchBundle\Event\ExceptionEvent;
use Novaway\ElasticsearchBundle\Event\SearchQuery;
use Novaway\ElasticsearchBundle\Event\SearchResult;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ExceptionEventLogger implements EventSubscriberInterface
{
    public function __construct(
        private LoggerInterface $logger
    ) {
    }

    public function logException(ExceptionEvent $event): void
    {
        $serializer = new Serializer([new ArrayDenormalizer(), new ObjectNormalizer()], [new JsonEncoder()]);

        $this->logger->critical('Novaway ES : Exception', [
            'queryData' => $serializer->serialize($event->getQueryData(), 'json', ['json_encode_options' => \JSON_PRETTY_PRINT]),
            'exception' => $event->getException()
        ]);
    }

    public static function getSubscribedEvents(): array
    {
        return [ExceptionEvent::NAME => 'logException'];
    }
}
