<?php

namespace App\Http\Controllers\Api\Microservices\Comments;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentsController extends BaseCommentsController
{

    public function index()
    {
        $comments = Comment::all();
        return response()->json($comments);
    }

    public function show($id)
    {
        $comment = Comment::findOrFail($id);
        return response()->json($comment);
    }

    public function create(Request $request)
    {
        $rules = [
            'shop_id' => 'required|integer',
            'purchase_id' => 'required|integer|unique:comments,purchase_id',
            'user_id' => 'required|integer',
            'description' => 'required',
            'score' => 'required|integer|between:1,5'
        ];
        $this->validate($request, $rules);

        $comment = Comment::create($request->all());

        return response()->json($comment);
    }

    public function update($id, Request $request)
    {
        $rules = [
            'shop_id' => 'required|integer',
            'user_id' => 'required|integer',
            'description' => 'required',
            'score' => 'required|integer|between:1,5'
        ];
        $this->validate($request, $rules);

        $comment = Comment::findOrFail($id);
        $comment->updateData($request->all());

        return response()->json($comment);
    }

    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();
        return response()->json($comment);
    }
}
