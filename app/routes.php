<?php

$app->get('/', 'App\Controller\HomeController:index')->setName('homepage');

$app->get('/populate', 'App\Controller\HomeController:populate')->setName('populate');

$app->post('/data', 'App\Controller\HomeController:data')->setName('data');