<?php
declare(strict_types=1);

namespace Novaway\ElasticsearchBundle\Elastica\Traits;

use Elastica\ResultSet\BuilderInterface;
use Novaway\ElasticsearchBundle\Elastica\Search;

trait IndexTrait
{
    public function createSearch($query = '', $options = null, BuilderInterface $builder = null): Search
    {
        $search = new Search($this->dispatcher,  $this->getClient(), $builder);
        $search->addIndex($this);
        $search->setOptionsAndQuery($options, $query);

        return $search;
    }

}
