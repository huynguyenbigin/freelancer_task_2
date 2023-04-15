<?php

use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/** @var Router $router */
$router->group([
    'middleware' => 'api',
    'prefix' => 'auth',
], function (Router $router) {
    $router->post('login', [
        'as' => 'api.auth.login',
        'uses' => 'AuthController@login',
    ]);
    $router->post('register', [
        'as' => 'api.auth.register',
        'uses' => 'AuthController@register',
    ]);
    $router->post('logout', [
        'as' => 'api.auth.logout',
        'uses' => 'AuthController@logout',
    ]);
    $router->get('user-profile', [
        'as' => 'api.auth.user_profile',
        'uses' => 'AuthController@userProfile',
    ]);
    $router->post('change-pass', [
        'as' => 'api.auth.change_pass',
        'uses' => 'AuthController@changePassWord',
    ]);
});

/** @var Router $router */
$router->group([
    'middleware' => 'auth:api',
    'prefix' => 'course',
], function (Router $router) {
    $router->post('/', [
        'as' => 'api.course.create',
        'uses' => 'CourseController@create',
    ]);
    $router->delete('{id}', [
        'as' => 'api.course.delete',
        'uses' => 'CourseController@delete',
    ]);
    $router->post('register', [
        'as' => 'api.course.register',
        'uses' => 'CourseController@register',
    ]);
    $router->post('thread', [
        'as' => 'api.course.thread',
        'uses' => 'CourseController@createThread',
    ]);
    $router->post('thread-reply', [
        'as' => 'api.course.thread_reply',
        'uses' => 'CourseController@createThreadReply',
    ]);
    $router->delete('thread-reply/{id}', [
        'as' => 'api.course.thread_reply',
        'uses' => 'CourseController@deleteThreadReply',
    ]);
    $router->get('threads', [
        'as' => 'api.course.threads',
        'uses' => 'CourseController@listThreads',
    ]);

});