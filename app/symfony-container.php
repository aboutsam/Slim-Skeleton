<?php

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\Config\ConfigCache;

$file = __DIR__ . '/../var/cache/symfony-container.php';
$containerConfigCache = new ConfigCache($file, $_ENV['APP_DEBUG']);

if (!$containerConfigCache->isFresh()) {
    $containerBuilder = new ContainerBuilder();
    $fileLoader = new YamlFileLoader($containerBuilder, new FileLocator());
    $fileLoader->load(__DIR__ . '/settings.yaml');
    $fileLoader->load(__DIR__ . '/services.yaml');
    $containerBuilder->compile();

    $dumper = new PhpDumper($containerBuilder);
    $containerConfigCache->write(
        $dumper->dump(),
        $containerBuilder->getResources()
    );
}

require_once $file;
return new ProjectServiceContainer();
