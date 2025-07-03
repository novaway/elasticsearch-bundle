<?php
declare(strict_types=1);

namespace Novaway\ElasticsearchBundle;


use Novaway\ElasticsearchBundle\DependencyInjection\CompilerPass\ConfigureLoggerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class NovawayElasticsearchBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ConfigureLoggerPass());
    }

    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
