<?php

namespace App\Http\Controllers\Api\Comments;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Transformers\Api\Comments\CommentTransformer;

// Controlador para gestionar los comentarios
class CommentsController extends Controller
{
    // Reglas para validar datos de request
    private $rules = [
        'shop_id' => 'required|integer',
        'purchase_id' => 'required|integer|unique:comments,purchase_id',
        'user_id' => 'required|integer',
        'description' => 'required',
        'score' => 'required|integer|between:1,5'
    ];

    /**
     * Retorna todos los comentarios activos.
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $comments = Comment::paginate();

        return response()->collection(
            $comments,
            new CommentTransformer,
            Response::HTTP_OK
        );
    }

    /**
     * Retorna el comentario solicitado, solo si este esta activo.
     *
     * @param int $commentId Id del comentario
     * @return \Illuminate\Http\Response
     */
    public function show($commentId)
    {
        $comment = Comment::findOrFail($commentId);

        return response()->item(
            $comment,
            new CommentTransformer,
            Response::HTTP_OK
        );
    }

    /**
     * Crea un nuevo comentario si pasa las validaciones.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->validate($request, $this->rules);

        $comment = Comment::create($request->all());

        return response()->item(
            $comment,
            new CommentTransformer,
            Response::HTTP_CREATED
        );
    }

    /**
     * Actualiza un comentario específico.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $commentId Id del comentario
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $commentId)
    {
        unset($this->rules['purchase_id']);
        $this->validate($request, $this->rules);

        $comment = Comment::findOrFail($commentId);
        $comment->updateData($request->all());

        return response()->item(
            $comment,
            new CommentTransformer,
            Response::HTTP_OK
        );
    }

    /**
     * Elimina el comentario indicado.
     *
     * @param int $commentId Id del comentario
     * @return \Illuminate\Http\Response
     */
    public function destroy($commentId)
    {
        $comment = Comment::findOrFail($commentId);
        $comment->delete();

        return response()->item(
            $comment,
            new CommentTransformer,
            Response::HTTP_OK
        );
    }

    /**
     * Retorna el comentario para una compra específica.
     *
     * @param int $purchaseId Id de la compra
     * @return \Illuminate\Http\Response
     */
    public function showCommentByPurchase($purchaseId)
    {
        $comment = new Comment;
        $commentByPurchase = $comment->getCommentByPurchase($purchaseId);

        return response()->item(
            $commentByPurchase,
            new CommentTransformer,
            Response::HTTP_OK
        );
    }

    /**
     * Retorna los comentarios pertenecientes a una tienda.
     *
     * @param int $shopId Id de la tienda
     * @return \Illuminate\Http\Response
     */
    public function showCommentsByShop($shopId)
    {
        $comment = new Comment;
        $commentByShop = $comment->getCommentsByShop($shopId);

        return response()->collection(
            $commentByShop,
            new CommentTransformer,
            Response::HTTP_OK
        );
    }
}
