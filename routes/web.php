<?php

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'users'], function () use ($router) {
    $router->get('/',  ['uses' => 'UserController@showAllUsers']);
    $router->get('accounts/{id}', ['uses' => 'UserController@showUsersByAccount']);
    $router->get('{id}', ['uses' => 'UserController@showOneUser']);
    $router->post('login',  ['uses' => 'UserController@login']);
    $router->post('/', ['uses' => 'UserController@create']);
    $router->get('delete/{id}', ['uses' => 'UserController@delete']);
    $router->get('update/{id}', ['uses' => 'UserController@update']);
});


$router->group(['prefix' => 'products'], function () use ($router) {
    $router->get('/',  ['uses' => 'ProductController@showAllProducts']);
    $router->get('categories/{id}', ['uses' => 'ProductController@showProductsByCategory']);
    $router->get('{id}', ['uses' => 'ProductController@showOneProduct']);
    $router->post('/', ['uses' => 'ProductController@create']);
    $router->get('delete/{id}', ['uses' => 'ProductController@delete']);
    $router->get('update/{id}', ['uses' => 'ProductController@update']);
});

$router->group(['prefix' => 'orders'], function () use ($router) {
    $router->get('/',  ['uses' => 'OrderController@showAllOrders']);
    $router->get('{id}', ['uses' => 'OrderController@showOneOrder']);
    $router->post('/', ['uses' => 'OrderController@create']);
    $router->get('delete/{id}', ['uses' => 'OrderController@delete']);
    $router->get('update/{id}', ['uses' => 'OrderController@update']);
});
