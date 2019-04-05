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

    /**
     * Build uniquely named index in Client
     *
     * @param bool $markAsLive          Link the newly built index to
     * @param bool $removeOldIndexes
     * @return Index
     *
     * @throws \Exception
     */
    public function rebuildIndex($markAsLive = true, $removeOldIndexes = true): Index
    {

        $realName = sprintf('%s_%s', $this->name, date('YmdHis'));
        $index = $this->getIndexByName($realName);

        // Actually create the Index with Mapping
        $index->create($this->config);

        if (true === $markAsLive) {
            // if it failed to mark as live, return current index
            $index = $this->markAsLive($index, $removeOldIndexes) ? $index : $this->getIndex();
        }

        return $index;
    }

    /**
     * Remove $this->name alias from each Index
     * and set it on $index
     *
     * @param Index $index The index to link to the $this->name alias
     * @param bool $removeOldIndexes If true, remove the indexes currently linked to $this->name alias after marking $index as live
     *
     * @return bool index is markedAsLive
     */
    public function markAsLive(Index $index, bool $removeOldIndexes = true): bool
    {
        // Retrieve indexes currently pointing on the alias $this->>name
        $oldIndexes = $this->getCurrentIndexes() ;

        try {
            $data = ['actions' => []];

            $data['actions'][] = ['remove' => ['index' => '*', 'alias' => $this->name]];
            $data['actions'][] = ['add' => ['index' => $index->getName(), 'alias' => $this->name]];

            $this->client->request('_aliases', Request::POST, $data);
        } catch (\Exception $e) {
            // a faliure occured during the marking of the new index a live
            // set the first as the old ones, and set it back as the alias
            $oldIndex = reset($oldIndexes);
            if ($oldIndex instanceof Index) {
                $this->markAsLive($oldIndex, $removeOldIndexes);
            }
            // and delete the failed one
            $index->delete();
            return false;
        }
        if ($removeOldIndexes) {
            // if everything went well, the new index is set as the alias
            // we can now remove the old indexes
            foreach ($oldIndexes as $oldIndex) {
                if ($oldIndex->getName() !== $index->getName()) {
                    $oldIndex->delete();
                }
            }
        }

        return true;
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
    protected function getCurrentIndexes(): array
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
        $results = [];
        foreach ($response->getData() as $indexName => $value) {
            $results[] = $this->getIndexByName($indexName);
        }
        return $results;
    }
}
