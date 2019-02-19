<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    // Implementación de borrado suave en comentarios
    use SoftDeletes;

    /**
     * Atributos asignables en masa.
     *
     * @var array
     */
    protected $fillable = [
        'shop_id',
        'purchase_id',
        'user_id',
        'description',
        'score',
        'created_at',
        'updated_at'
    ];

    /**
     * Atributos que deben estar ocultos
     *
     * @var array
     */
    protected $hidden = [
        'deleted_at'
    ];

    /**
     * Casting para los atributos
     *
     * @var array
     */
    protected $casts = [
        'shop_id' => 'integer',
        'purchase_id' => 'integer',
        'user_id' => 'integer',
        'score' => 'integer',
    ];

    /**
     * Actualiza los datos de un comentario en la base de datos
     *
     * @return void
     */
    public function updateData($data)
    {
        $this->shop_id = $data['shop_id'];
        $this->purchase_id = $data['purchase_id'];
        $this->user_id = $data['user_id'];
        $this->description = $data['description'];
        $this->score = $data['score'];
        $this->save();
    }

    /**
     * Obtiene el comentario para una compra.
     *
     * @return \App\Models\Comment
     */
    public function getCommentByPurchase($purchaseId)
    {
        return $this->wherePurchaseId($purchaseId)->firstOrFail();
    }

    /**
     * Obtiene los comentarios para una tienda.
     *
     * @return \App\Models\Comment
     */
    public function getCommentsByShop($shopId)
    {
        return $this->whereShopId($shopId)->simplePaginate();
    }
}
