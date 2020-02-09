<?php
$router->group(['prefix' => 'api/v1','middleware' => 'api.auth'],  function () use ($router) {
    $router->get('/', function () use ($router) {
        return array(
            'status'=>200,
            'message'=>'Welcome to '.env('APP_NAME'),
            'data' => array('version'=>env('APP_VERSION'))
        );
    });

    $router->group(['prefix' => 'cars', 'namespace' => 'App\Modules\v1\Controllers'], function() use($router) {
        $router->get('/status', 'Cars@status');
        $router->get('/status/{date}', 'Cars@status');
        $router->get('/', 'Cars@index');
        $router->get('/{registration_no}', 'Cars@index');
        $router->post('/', 'Cars@create');
        $router->put('/{registration_no}', 'Cars@update');
        $router->delete('/{registration_no}', 'Cars@destroy');
    });

    $router->group(['prefix' => 'reservations', 'namespace' => 'App\Modules\v1\Controllers'], function() use($router) {
        $router->post('/reserve', 'Reservations@reserve');
        $router->delete('/cancel/{id}', 'Reservations@cancel');
    });
});
?>