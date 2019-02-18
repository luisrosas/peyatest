<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'shop_id',
        'shop_id',
        'purchase_id',
        'user_id',
        'description',
        'score',
        'created_at',
        'updated_at'
    ];

    protected $hidden = [
        'deleted_at'
    ];

    public function updateData($data)
    {
        $this->shop_id = $data['shop_id'];
        $this->purchase_id = $data['purchase_id'];
        $this->user_id = $data['user_id'];
        $this->description = $data['description'];
        $this->score = $data['score'];
        $this->save();
    }

    public function getCommentByPurchase($purchaseId)
    {
        return $this->wherePurchaseId($purchaseId)->firstOrFail();
    }

    public function getCommentsByShop($shopId)
    {
        return $this->whereShopId($shopId)->get();
    }
}
