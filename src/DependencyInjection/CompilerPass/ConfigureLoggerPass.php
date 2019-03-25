<?php

namespace Novaway\ElasticsearchBundle\DependencyInjection\CompilerPass;

use Novaway\ElasticsearchBundle\Logger\SearchEventLogger;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

class ConfigureLoggerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $config = $container->getParameter('novaway_elasticsearch.logging');

        if ($config['enabled'] === true) {
            try {
                $argumentLogger = $container->findDefinition($config['logger']);
            } catch (ServiceNotFoundException $e) {
                $msg = sprintf('Error when trying to find the novaway_elasticsearch.logging.logger : %s', $e->getMessage());
                throw new ServiceNotFoundException($e->getId(), $e->getSourceId(), $e->getPrevious(), $e->getAlternatives(), $msg);
            }
            $searchLogger = $container->findDefinition(SearchEventLogger::class);
            $searchLogger->setArgument(0, $argumentLogger);
        } else {
            $container->removeDefinition(SearchEventLogger::class);
        }
    }
}
