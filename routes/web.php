<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

date_default_timezone_set('Asia/Jakarta');
$router->get('/', function () use ($router) {
    return array(
        'status'=>200,
        'message'=>'Welcome to '.env('APP_NAME').'-'.env('APP_ENV'),
        'data' => array('version'=>env('APP_VERSION'))
    );
});
