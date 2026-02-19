<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('/dbtest', function () {
    $db = \Config\Database::connect();
    return $db->connID ? 'DB OK' : 'DB FAIL';
});

$routes->get('/envtest', function () {
    return [
        getenv('database.default.hostname'),
        getenv('database.default.username'),
        getenv('database.default.password'),
        getenv('database.default.database'),
        getenv('database.default.port')
    ];
});

$routes->get('/rawdb', function () {

    $conn = mysqli_connect(
        getenv('database.default.hostname'),
        getenv('database.default.username'),
        getenv('database.default.password'),
        getenv('database.default.database'),
        getenv('database.default.port')
    );

    if (!$conn) {
        return mysqli_connect_error();
    }

    return "RAW CONNECT OK";
});




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
