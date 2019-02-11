<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('shop_id')->unsigned()->comment('Id de la tienda');
            $table->integer('purchase_id')->unsigned()->unique()->comment('Id de la compra');
            $table->integer('user_id')->unsigned()->comment('Id del usuario que realiza el comentario');
            $table->text('description')->comment('Texto del comentario');
            $table->tinyInteger('score')->comment('Puntuación de la compra');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
