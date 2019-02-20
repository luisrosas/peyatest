<?php

namespace App\Transformers\Api\Comments;

use App\Models\Comment;
use League\Fractal\TransformerAbstract;

class CommentTransformer extends TransformerAbstract
{
    /**
     * name of scope for this transformer
     *
     * @var String
     */
    public static $scope = 'comments';

    /**
     * Turn this item object into a generic array.
     *
     * @param  \App\User  $user
     * @return array
     */
    public function transform(Comment $comment)
    {
        return [
            "id" => $comment->id,
            "shop_id" => $comment->shop_id,
            "purchase_id" => $comment->purchase_id,
            "user_id" => $comment->user_id,
            "description" => $comment->description,
            "score" => $comment->score,
            "created_at" => $comment->created_at->toDateTimeString(),
            "updated_at" => $comment->updated_at->toDateTimeString()
        ];
    }
}
