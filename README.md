# novaway/elasticsearch-bundle

A bundle to integrate add debug information to  elasticsearch/elasticsearch in your Symfony Applications

## Usage 

The bundle provides a Elasticsearch\Client override, throwing events during search, and displaying it in the symfony toolbar.

The Client is provided by the [Novaway\ElasticsearchBundle\Factory\ClientFactory:createClient()](https://github.com/novaway/elasticsearch-bundle/blob/master/src/Factory/ClientFactory.php) method

## Installation
```
composer require novaway/elasticsearch-bundle
```

## Configuration

You probably want to register the Client as a service. To do so, use the ClientFactory
```yml
# config/services.yaml
services:
...
    Novaway\ElasticsearchBundle\Elasticsearch\Client:
        factory: Novaway\ElasticsearchBundle\Factory\ClientFactory:createClient
        arguments:
            - ['%elasticsearch_host%']

```
And voila, when you use this client for search, the queries and requests will be collected, and added to the SymfonyToolbar

## Integration with the novaway/elasticsearch-client

You can of course use this bundle with the [novaway/elasticsearch-client](https://github.com/novaway/elasticsearch-client), you only need to pass the Client to the Indexes

## License

This bundle is under the MIT license. See the complete license [in the bundle](https://github.com/novaway/elasticsearch-bundle/blob/master/LICENSE)
