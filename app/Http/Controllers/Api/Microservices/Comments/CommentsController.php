<?php

namespace App\Http\Controllers\Api\Microservices\Comments;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CommentsController extends BaseCommentsController
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
     * Retorna todos los comentarios activos
     */
    public function index()
    {
        $comments = Comment::all();
        return response()->json($comments, Response::HTTP_OK);
    }

    public function show($id)
    {
        $comment = Comment::findOrFail($id);

        return response()->json($comment, Response::HTTP_OK);
    }

    public function create(Request $request)
    {
        $this->validate($request, $this->rules);

        $comment = Comment::create($request->all());

        return response()->json($comment, Response::HTTP_CREATED);
    }

    public function update(Request $request, $commentId)
    {
        unset($this->rules['purchase_id']);
        $this->validate($request, $this->rules);

        $comment = Comment::findOrFail($commentId);
        $comment->updateData($request->all());

        return response()->json($comment, Response::HTTP_OK);
    }

    public function destroy($commentId)
    {
        $comment = Comment::findOrFail($commentId);
        $comment->delete();
        return response()->json($comment, Response::HTTP_OK);
    }

    public function showCommentByPurchase($purchaseId)
    {
        $comment = new Comment;
        $commentByPurchase = $comment->getCommentByPurchase($purchaseId);
        return response()->json($commentByPurchase, Response::HTTP_OK);
    }

    public function showCommentsByShop($shopId)
    {
        $comment = new Comment;
        $commentByShop = $comment->getCommentsByShop($shopId);
        return response()->json($commentByShop, Response::HTTP_OK);
    }
}
