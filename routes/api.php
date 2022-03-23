<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

$router->get('/v1', function () use ($router) {
    return "Workflow Orchestration Microservice API" . " [". $router->app->version() . "]";
});

$router->group(['prefix' => 'v1', 'middleware' => 'JsonRequestMiddleware'], function() use ($router) {

    $router->post('/poc', 'WorkflowPocController@run');
    $router->get('/status/{uuid}', 'WorkflowPocController@getStatus');

    $router->get('/activities', 'ActivityController@index');
    $router->get('/activities/{uuid}', 'ActivityController@show');
    $router->get('/workflows', 'WorkflowController@index');
    $router->get('/workflows/{uuid}', 'WorkflowController@show');
    $router->get('/workflow-runs', 'WorkflowRunController@index');
    $router->get('/workflow-runs/{uuid}', 'WorkflowRunController@show');
});

