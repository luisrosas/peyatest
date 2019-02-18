<?php

/**
 * Grupo de rutas para prefijo comments
 */
$router->group(['prefix' => 'comments'], function() use ($router) {
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
});

/**
 * Obtener el comentario para una compra especifica
 */
$router->get('purchases/{id}/comments', [
    'as' => 'getPurchaseComment',
    'uses' => 'CommentsController@showCommentByPurchase'
]);

/**
 * Obtener los comentarios para una tienda
 */
$router->get('shops/{id}/comments', [
    'as' => 'getShopComments',
    'uses' => 'CommentsController@showCommentsByShop'
]);
