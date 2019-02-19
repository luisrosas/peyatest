<?php

use Illuminate\Http\Response;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class CommentsTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    private $structureData = [
        "id",
        "shop_id",
        "purchase_id",
        "user_id",
        "description",
        "score",
        "created_at",
        "updated_at"
    ];
    private $structureError = [
        "error" => [
            'status',
            'title',
            'details'
        ]
    ];

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetAllComments()
    {
        $data = $this->createComments([], 3);
        $url = '/api/comments';
        $this->get($url)
            ->seeStatusCode(Response::HTTP_OK)
            ->seeJsonStructure([$this->structureData]);
    }

    public function testGetSpecificComment()
    {
        $commentsToCreate = rand(1, 5);
        $data = $this->createComments([], $commentsToCreate);
        $idComment = $data->random()->id;
        $url = '/api/comments/' . $idComment;
        $response = $this->get($url);
        $this->seeStatusCode(Response::HTTP_OK)
            ->seeJsonStructure($this->structureData)
            ->seeJson([
                'id' => $idComment,
            ]);
    }

    public function testCreateSpecificComment()
    {
        $data = [
            'shop_id' => 100,
            'purchase_id' => 100,
            'user_id' => 100,
            'description' => 100,
            'score' => 4,
        ];
        $url = '/api/comments';
        $response = $this->post($url, $data);
        $this->seeStatusCode(Response::HTTP_CREATED)
            ->seeJsonStructure($this->structureData)
            ->seeJson($data);
    }

    public function testCreateDuplicateComment()
    {
        $commentsToCreate = rand(1, 5);
        $data = $this->createComments([], $commentsToCreate);
        $idPurchase = $data->random()->purchase_id;
        $data = [
            'shop_id' => 100,
            'purchase_id' => $idPurchase,
            'user_id' => 100,
            'description' => 100,
            'score' => 4,
        ];
        $url = '/api/comments';
        $this->post($url, $data)
            ->seeStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->seeJsonStructure([$this->structureError]);
    }

    public function testValidateFiels()
    {
        $data = [];
        $url = '/api/comments';
        $this->post($url, $data)
            ->seeStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->seeJsonStructure([$this->structureError])
            ->seeJson(['title' => 'shop_id'])
            ->seeJson(['title' => 'purchase_id'])
            ->seeJson(['title' => 'user_id'])
            ->seeJson(['title' => 'description'])
            ->seeJson(['title' => 'score']);
    }

    public function testUpdateSpecificComment()
    {
        $commentsToCreate = rand(1, 2);
        $data = $this->createComments([], $commentsToCreate);
        $data = $data->random();
        $data->score = 2;
        $idComment = $data->id;
        unset($data->id);
        unset($data->created_at);
        unset($data->updated_at);
        $url = '/api/comments/' . $idComment;
        $this->put($url, $data->toArray())
            ->seeStatusCode(Response::HTTP_OK);
    }

    public function testUpdateDuplicateComment()
    {
        $commentsToCreate = 2;
        $data = $this->createComments([], $commentsToCreate);
        $data1 = $data->first();
        $data2 = $data->last();
        $data1->purchase_id = $data2->purchase_id;
        $idComment = $data1->id;
        unset($data1->id);
        unset($data1->created_at);
        unset($data1->updated_at);
        $url = '/api/comments/' . $idComment;
        $this->put($url, $data1->toArray())
            ->seeStatusCode(Response::HTTP_CONFLICT)
            ->seeJsonStructure($this->structureError);
    }

    public function testDeleteSpecificComment()
    {
        $data = $this->createComments([], 1);

        $this->seeInDatabase('comments', ['deleted_at' => null]);

        $data = $data->first();
        $idComment = $data->id;
        $url = '/api/comments/' . $idComment;
        $this->delete($url)
            ->seeStatusCode(Response::HTTP_OK);
        $this->notSeeInDatabase('comments', ['deleted_at' => null]);
    }

    public function testCommentByPurchase()
    {
        $commentsToCreate = rand(1, 4);
        $data = $this->createComments([], $commentsToCreate);
        $data = $data->random();
        $idPurchase = $data->purchase_id;
        $url = '/api/purchases/' . $idPurchase . '/comments';
        $this->get($url)
            ->seeStatusCode(Response::HTTP_OK)
            ->seeJsonStructure($this->structureData)
            ->seeJsonContains([
                'purchase_id' => "$idPurchase"
            ]);
    }

    public function testCommentByShop()
    {
        $idShop = 3;
        $commentsToCreate = rand(1, 6);
        $data = $this->createComments(['shop_id' => $idShop], $commentsToCreate);
        $data = $data->random();
        $idPurchase = $data->purchase_id;
        $url = '/api/shops/' . $idShop . '/comments';
        $this->get($url)
            ->seeStatusCode(Response::HTTP_OK)
            ->seeJsonStructure([$this->structureData])
            ->seeJsonContains([
                'shop_id' => "$idShop"
            ]);
    }

    public function testModelNotFound()
    {
        $url = '/api/comments/1';
        $this->get($url)
            ->seeStatusCode(Response::HTTP_NOT_FOUND)
            ->seeJsonStructure($this->structureError);
    }

    public function testHttpNotFound()
    {
        $url = '/api/comments/all/1';
        $this->get($url)
            ->seeStatusCode(Response::HTTP_NOT_FOUND)
            ->seeJsonStructure($this->structureError);
    }

    public function testError500()
    {
        $data = [
            'shop_id' => 100,
            'purchase_id' => 100,
            'user_id' => 100,
            'description' => 100,
            'score' => 4,
            'created_at' => 'Date'
        ];
        $url = '/api/comments';
        $response = $this->post($url, $data)
            ->seeStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR)
            ->seeJsonStructure($this->structureError);
    }

    private function createComments($data = [], $commentsCount = 1)
    {
        return factory(\App\Models\Comment::class, $commentsCount)
            ->create($data);
    }
}
