<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->group('api', function ($routes) {
    // Auth (Public)
    $routes->post('login', 'Api\Auth::login');
    $routes->post('register', 'Api\Auth::register');

    // Todo (Protected - Harus Login)
    $routes->group('todos', ['filter' => 'jwt'], function ($routes) {
        $routes->get('/', 'Api\TodoController::index');
        $routes->post('/', 'Api\TodoController::create');
        $routes->put('(:num)', 'Api\TodoController::update/$1');
        $routes->delete('(:num)', 'Api\TodoController::delete/$1');
    });
});
