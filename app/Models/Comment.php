<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    // Implementación de borrado suave en comentarios
    use SoftDeletes;

    // Campos de la tabla
    const SHOP_ID_FIELD = 'shop_id';
    const PUCHASE_ID_FIELD = 'purchase_id';
    const USER_ID_FIELD = 'user_id';
    const DESCRIPTION_ID_FIELD = 'description';
    const SCORE_ID_FIELD = 'score';
    const CREATED_AT_FIELD = 'created_at';
    const UPDATED_AT_FIELD = 'updated_at';

    // Tipos de datos
    const TYPE_INTEGER = 'integer';

    /**
     * Atributos asignables en masa.
     *
     * @var array
     */
    protected $fillable = [
        self::SHOP_ID_FIELD,
        self::PUCHASE_ID_FIELD,
        self::USER_ID_FIELD,
        self::DESCRIPTION_ID_FIELD,
        self::SCORE_ID_FIELD
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
        self::SHOP_ID_FIELD => self::TYPE_INTEGER,
        self::PUCHASE_ID_FIELD => self::TYPE_INTEGER,
        self::USER_ID_FIELD => self::TYPE_INTEGER,
        self::SCORE_ID_FIELD => self::TYPE_INTEGER,
    ];

    /**
     * Actualiza los datos de un comentario en la base de datos
     *
     * @return void
     */
    public function updateData($data)
    {
        $this->shop_id = $data[self::SHOP_ID_FIELD];
        $this->purchase_id = $data[self::PUCHASE_ID_FIELD];
        $this->user_id = $data[self::USER_ID_FIELD];
        $this->description = $data[self::DESCRIPTION_ID_FIELD];
        $this->score = $data[self::SCORE_ID_FIELD];
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
        return $this->whereShopId($shopId)->paginate();
    }
}
