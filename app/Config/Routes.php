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
        'HOST' => getenv('MYSQLHOST'),
        'PORT' => getenv('MYSQLPORT'),
        'USER' => getenv('MYSQLUSER'),
        'DB'   => getenv('MYSQLDATABASE'),
    ];
});

$routes->get('/rawdb', function () {
    $conn = mysqli_connect(
        getenv('MYSQLHOST'),
        getenv('MYSQLUSER'),
        getenv('MYSQLPASSWORD'),
        getenv('MYSQLDATABASE'),
        getenv('MYSQLPORT')
    );

    if (!$conn) {
        return mysqli_connect_error();
    }

    return 'RAW CONNECT OK';
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
