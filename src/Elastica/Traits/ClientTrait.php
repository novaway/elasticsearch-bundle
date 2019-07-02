<?php
declare(strict_types=1);

namespace Novaway\ElasticsearchBundle\Elastica\Traits;

use Elastica\ResultSet\BuilderInterface;
use Novaway\ElasticsearchBundle\Exception\Connexion\TimeoutException;

trait ClientTrait
{
    /**
     * @param int $timeout number of second before the connexion attempts stop
     * @param null $url if provider, the elasticsearch url to test
     *
     * @return int number of seconds for elasticsearch to respond
     * @throws TimeoutException
     */
    public function waitForElasticsearchToBeUp($timeout = 60, $url = null)
    {
        if (null === $url) {
            Assert::notNull($this->getConfig('url'));
            $url = $this->getConfig('url');
        }

        $i = 0;
        $httpCode = null;
        while (!(is_int($httpCode) && $httpCode >= 200 && $httpCode <= 399)) {
            if ($i != 0) {
                sleep(1);
            }
            $i++;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, TRUE);
            curl_setopt($ch, CURLOPT_NOBODY, TRUE); // remove body
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($i >= $timeout) {
                throw new TimeoutException(sprintf('Elasticsearch connexion has not responded correctly in %s seconds',$timeout));
            }
        }

        return $i;
    }
}
