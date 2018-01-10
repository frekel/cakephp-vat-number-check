<?php
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\InflectedRoute;

Router::plugin('VatNumberCheck', ['path' => '/vat_number_check'], function (RouteBuilder $routes) {
    $routes->fallbacks(InflectedRoute::class);
});

Cake\Routing\Router::extensions('json');

