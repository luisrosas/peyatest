<?php

/**
 * Obtener todos los comentarios
 */
$router->get('/', [
    'as' => 'getComments',
    'uses' => 'CommentsController@index'
]);

/**
 * Obtener un comentario especifico
 */
$router->get('/{id}', [
    'as' => 'getComment',
    'uses' => 'CommentsController@show'
]);

/**
 * Crear un comentario
 */
$router->post('/', [
    'as' => 'createComment',
    'uses' => 'CommentsController@create'
]);

/**
 * Actualizar un comentario especifico
 */
$router->put('/{id}', [
    'as' => 'updateComment',
    'uses' => 'CommentsController@update'
]);

/**
 * Eliminar un comentario especifico
 */
$router->delete('/{id}', [
    'as' => 'deleteComment',
    'uses' => 'CommentsController@destroy'
]);
