<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\Validator;

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
        //obtiene transformardor de acuerdo a la instancia
        $transformer = $collection->first()->transformer;
        //filtra los datos
        $collection = $this->filterData($collection, $transformer);
        //ordena por un atributo
        $collection = $this->sortData($collection, $transformer);
        //paginacion
        $collection = $this->paginate($collection);
        //transforma la coleccion
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

    protected function sortData(SupportCollection $collection, $transformer)
    {
        if (request()->has('sort_by')) {
            //obtiene el nombre original del parametro - evitando capa Fractal
            $attribute = $transformer::originalAttributes(request()->sort_by);
            $collection = $collection->sortBy->{$attribute};
        }
        return $collection;
    }

    protected function filterData(SupportCollection $collection, $transformer)
    {
        foreach (request()->query() as $query => $value) {
            //obtiene el nombre original del parametro - evitando capa Fractal
            $attribute = $transformer::originalAttributes($query);
            if (isset($attribute, $value)) {
                $collection = $collection->where($attribute, $value);
            }
        }
        return $collection;
    }

    protected function paginate(SupportCollection $collection)
    {
        //restricciones de paginacion
        $rules = [
            'per_page' => 'integer|max:50|min:2',
        ];

        Validator::validate(request()->all(), $rules);

        $page = LengthAwarePaginator::resolveCurrentPage();

        $perPage = 15;
        if (request()->has('per_page')) {
            $perPage = (int) request()->per_page;
        }
        $results = $collection->slice(($page - 1) * $perPage, $perPage)->values();

        $paginated = new LengthAwarePaginator($results, $collection->count(), $perPage, $page, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ]);
        //agrega los parametros de la query
        $paginated->appends(request()->all());
        return $paginated;
    }
}
