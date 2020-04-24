<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

trait ApiResponser
{
    private function successJsonResponse($data, $code)
    {
        return response()->json($data, $code);
    }

    protected function errorJsonResponse($message, $code)
    {
        return response()->json(['error' => $message, 'code' => $code], $code);
    }

    protected function indexJsonResponse(Collection $collection, $code = 200)
    {
        return $this->successJsonResponse(['data' => $collection], $code);
    }

    protected function showJsonResponse(Model $model, $code = 200)
    {
        return $this->successJsonResponse(['data' => $model], $code);
    }

}
