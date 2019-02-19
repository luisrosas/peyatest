<?php

// Factory para el modelo de comentarios
$factory->define(App\Models\Comment::class, function (Faker\Generator $faker) {
    return [
        'shop_id' => $faker->numberBetween(1, 100),
        'purchase_id' => $faker->numberBetween(1, 5000),
        'user_id' => $faker->numberBetween(1, 10),
        'description' => $faker->paragraph(2),
        'score' => $faker->numberBetween(1, 5),
    ];
});
