<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class FractalServiceProvider extends ServiceProvider
{
    /**
     * Boot the transformer services response for the application.
     *
     * @return void
     */
    public function boot()
    {
        $fractal = $this->app->make('League\Fractal\Manager');

        response()->macro(
            'item',
            function (
                $item,
                \League\Fractal\TransformerAbstract $transformer,
                $status = 200,
                array $headers = []
            ) use ($fractal) {
                $resource = new \League\Fractal\Resource\Item($item, $transformer, $transformer::$scope);

                return response()->json(
                    $fractal->createData($resource)->toArray(),
                    $status,
                    $headers
                );
            }
        );

        response()->macro(
            'collection',
            function (
                $collection,
                \League\Fractal\TransformerAbstract $transformer,
                $status = 200,
                array $headers = []
            ) use ($fractal) {
                \Log::info($collection);
                $resource = new \League\Fractal\Resource\Collection($collection, $transformer, $transformer::$scope);
                $resource->setPaginator(new IlluminatePaginatorAdapter($collection));
                return response()->json(
                    $fractal->createData($resource)->toArray(),
                    $status,
                    $headers
                );
            }
        );
    }
}
