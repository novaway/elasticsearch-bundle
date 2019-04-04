<?php
declare(strict_types=1);

namespace Novaway\ElasticsearchBundle\Provider;


use Elastica\Request;
use Elastica\Response;
use Novaway\ElasticsearchBundle\Elastica\Client;
use Novaway\ElasticsearchBundle\Elastica\Index;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class IndexProvider
{
    /** @var Client */
    private $client;
    /** @var EventDispatcherInterface */
    private $eventDispatcher;
    /** @var string */
    private $name;
    /** @var array */
    private $config;

    public function __construct(
        Client $client,
        EventDispatcherInterface $eventDispatcher,
        string $name,
        array $config
    ) {
        $this->client = $client;
        $this->eventDispatcher = $eventDispatcher;
        $this->name = $name;
        $this->config = $config;
    }

    public function getIndex(): Index
    {
        return new Index($this->eventDispatcher, $this->client, $this->name);
    }

    public function rebuildIndex($markAsLive = true): Index
    {
        $realName = sprintf('%s_%s', $this->name, date('YmdHis'));
        $index = $this->newIndex($realName);

        // Actually create the Index with Mapping
        $index->create($this->config);

        if ($markAsLive) {
            $this->markAsLive($index);
        }

        return $index;
    }

    public function markAsLive(Index $index): Response
    {
        $data = ['actions' => []];

        $data['actions'][] = ['remove' => ['index' => '*', 'alias' => $this->name]];
        $data['actions'][] = ['add' => ['index' => $index->getName(), 'alias' => $this->name]];

        return $this->client->request('_aliases', Request::POST, $data);
    }
    
    protected function newIndex($name): Index
    {
        return new Index($this->eventDispatcher, $this->client, $name);
    }
}
