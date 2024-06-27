<?php

declare(strict_types=1);

use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use App\Infrastructure\Query\QueryRepository;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        LoggerInterface::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);

            $loggerSettings = $settings->get('logger');
            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },
    ]);

    // Database
    $containerBuilder->addDefinitions([
        'db' => function(ContainerInterface $c) {
            $settings = $c->get('settings')['db'];
            $pdo = new PDO("mysql:host={$settings['host']};dbname={$settings['dbname']};charset={$settings['charset']}",
                            $settings['user'], 
                            $settings['pass']);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        },
    ]);

    // QueryRepository
    $containerBuilder->addDefinitions([
        QueryRepository::class => function(ContainerInterface $c) {
            return new QueryRepository($c->get('db'));
        },
    ]);
};
