<?php
declare(strict_types=1);

namespace Novaway\ElasticsearchBundle\Provider;


use Elastica\Request;
use Elastica\Response;
use Elastica\Client;
use Elasticsearch\Endpoints\Indices\Alias\Exists;
use Elasticsearch\Endpoints\Indices\Alias\Get;
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

    public function rebuildIndex($markAsLive = true, $removeOldIndexes = true): Index
    {
        $markedAslive = false;
        try {
            //
            $oldIndexes = $this->getCurrentIndexes();

            $realName = sprintf('%s_%s', $this->name, date('YmdHis'));
            $index = $this->getIndexByName($realName);

            // Actually create the Index with Mapping
            $index->create($this->config);

            if (true === $markAsLive) {
                $this->markAsLive($index);
                $markedAslive = true;
                if (true === $removeOldIndexes)
                    // if eveyrything went well, the new index is set as the alias
                    // we can now remove the old indexes
                    foreach ($oldIndexes as $oldIndex) {
                        $oldIndex->delete();
                    }
            }


        } catch (\Exception $e) {
            if (isset($index) && false === $markedAslive) {
                // if the new index is created and not markedAsLive
                // we must remove the created and unlinked one
                $index->delete();
            }
            throw $e;
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
    
    protected function getIndexByName($name): Index
    {
        return new Index($this->eventDispatcher, $this->client, $name);
    }

    /**
     * Return indexes currently pointing on alias
     * Sould return only one index, provided no problem occured
     *
     * @return Index[]
     */
    protected function getCurrentIndexes(): iterable
    {
        $existAlias = new Exists();
        $existAlias->setName($this->name);
        if (200 !== $this->client->requestEndpoint($existAlias)->getStatus()) {
            // alias doesn't already exist, there is no oldIndexes
            return [];
        }
        $getAlias = new Get();
        $getAlias->setName($this->name);
        $response = $this->client->requestEndpoint($getAlias);
        foreach ($response->getData() as $indexName => $value) {
            yield $this->getIndexByName($indexName);
        }
    }
}
