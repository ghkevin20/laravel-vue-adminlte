<?php

namespace App\Helpers;

use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class Datatable
{

    public static function make($baseQuery, $searchFields, $allowedFields, $allowedSorts, $allowedAppends)
    {
        $request = app()->make('request');

        $data = QueryBuilder::for($baseQuery,$request)
            ->allowedFilters(
                AllowedFilter::custom(
                    'search',
                    new SearchFields,
                    implode(',',$searchFields)
                )
            )
            ->allowedFields($allowedFields)
            ->allowedSorts($allowedSorts)
            ->allowedAppends($allowedAppends);

        if ($request->has('scope')) {
            if(strtolower($request->scope) === 'trashed'){
                $data = $data->onlyTrashed();
            }elseif (strtolower($request->scope) === 'all'){
                $data = $data->withTrashed();
            }
            // default active
        }

        if ($request->has('per_page')) {
            $data = $data->paginate($request->per_page); // Get paginator
        } else {
            $data = $data->get();
        }

        return response($data);

    }

}
