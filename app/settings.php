<?php
return [
    'settings' => [
        'displayErrorDetails' => true,
        
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],
        
        'view' => [
            'template_path' => __DIR__ . '/../templates',
            'twig' => [
                'cache' => __DIR__ . '/../data/cache/twig',
                'debug' => true,
                'auto_reload' => true,
            ],
        ],
        
        'doctrine' => [
            'connection' => [
                'driver' => 'pdo_mysql',
                'host'     => '127.0.0.1',
                'port'     => 3306,
                'dbname'   => 'project_directory',
                'user'     => 'root',
                'password' => 'password'
            ],
            'annotation_paths' => __DIR__ . '/src/Entity',
            'auto_generate_proxies' => \Doctrine\Common\Proxy\AbstractProxyFactory::AUTOGENERATE_ALWAYS,
            'proxy_path' =>  __DIR__.'/../data/cache/doctrine/proxies',
            //'sql_logger' => new \Doctrine\DBAL\Logging\EchoSQLLogger(),
            //'cache_driver' =>  new \Doctrine\Common\Cache\FilesystemCache(__DIR__.'/../data/cache/doctrine/cache')
            'cache_driver' => new \Doctrine\Common\Cache\VoidCache()
        ],

        'logger' => [
            'name' => 'app',
            'path' => __DIR__ . '/../data/logs/app.log',
        ],
        
    ],
];
