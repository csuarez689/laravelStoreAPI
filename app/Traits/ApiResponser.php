<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection as SupportCollection;

trait ApiResponser
{
    /**
     * Create a custom success json response
     *
     * @param Mixed $data can be a Collection or Model
     * @param Integer $code default value 200
     * @return \Illuminate\Http\JsonResponse with $data and $code
     **/
    private function successJsonResponse($data, $code)
    {
        return response()->json($data, $code);
    }

    /**
     * Create a custom error json response
     *
     * @param Mixed $message String[] or String
     * @param Integer $code doesn't have default value
     * @return JsonResponse with $message error and $code
     **/
    protected function errorJsonResponse($message, $code)
    {
        return response()->json(['error' => $message, 'code' => $code], $code);
    }

    protected function showAll(SupportCollection $collection, $code = 200)
    {
        if ($collection->isEmpty()) {
            return $this->successJsonResponse(['data' => $collection], $code);
        }
        $transformer = $collection->first()->transformer;
        $collection = $this->transformData($collection, $transformer);
        return $this->successJsonResponse($collection, $code);
    }
    protected function showOne(Model $instance, $code = 200)
    {
        $transformer = $instance->transformer;
        $instance = $this->transformData($instance, $transformer);
        return $this->successJsonResponse($instance, $code);
    }

    protected function showMessage($message, $code = 200)
    {
        return response()->json(['data' => $message], $code);
    }

    protected function transformData($data, $transformer)
    {
        $transformation = fractal($data, new $transformer);
        return $transformation->toArray();
    }
}
