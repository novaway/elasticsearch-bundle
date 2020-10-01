# novaway/elasticsearch-bundle

A bundle to integrate add debug information to elasticsearch/elasticsearch and ruflin/elastica in your Symfony Applications

## Usage 

The bundle provides a Elasticsearch\Client override, throwing events during search, and displaying it in the symfony toolbar.

## Installation
```
composer require novaway/elasticsearch-bundle
```

## Service configuration

You probably want to register the Novaway\ElasticsearchBundle\Elastica\Client as a service, and set Elastica\Client as its alias 
```yml
# config/services.yaml
services:
...
    Novaway\ElasticsearchBundle\Elastica\Client:
        arguments:
            $config:
                url: '%elasticsearch_host%/'

    Elastica\Client: '@Novaway\ElasticsearchBundle\Elastica\Client'
```
And voila, when you use this client for search, the queries and requests will be collected, and added to the SymfonyToolbar


## Configuration


```yml
# config/package/novaway_elasticsearch.yaml
novaway_elasticsearch:
    logging:
        enabled: false # if true, log every search request with a LoggerInterface service
        logger: 'logger' # the logger service id
```


## License

This bundle is under the MIT license. See the complete license [in the bundle](https://github.com/novaway/elasticsearch-bundle/blob/master/LICENSE)
