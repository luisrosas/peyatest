<?php

/**
 * Obtener todos los comentarios
 */
$router->get('/', [
    'as' => 'getComments',
    'uses' => 'Api\Microservices\Comments\CommentsController@index'
]);

/**
 * Obtener un comentario especifico
 */
$router->get('/{id}', [
    'as' => 'getComment',
    'uses' => 'Api\Microservices\Comments\CommentsController@show'
]);

/**
 * Crear un comentario
 */
$router->post('/', [
    'as' => 'createComment',
    'uses' => 'Api\Microservices\Comments\CommentsController@create'
]);

/**
 * Actualizar un comentario especifico
 */
$router->put('/{id}', [
    'as' => 'updateComment',
    'uses' => 'Api\Microservices\Comments\CommentsController@update'
]);

/**
 * Eliminar un comentario especifico
 */
$router->delete('/{id}', [
    'as' => 'deleteComment',
    'uses' => 'Api\Microservices\Comments\CommentsController@destroy'
]);
