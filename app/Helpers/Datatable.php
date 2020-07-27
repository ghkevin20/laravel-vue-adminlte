<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Validator;

class Datatable
{

    public static function make($query, $columns)
    {
        $request = app()->make('request');

        $result = [
            'data' => []
        ];

        $arrangedColumns = [];

        foreach ($columns as $column) {
            $array = explode('AS', $column);

            if (isset($array[1])) {
                $arrangedColumns[trim($array[1])] = trim($array[0]);
            } else {
                $array = explode('.', $column);
                $arrangedColumns[trim($array[1])] = $column;
            }
        }

        $validator = Validator::make($request->only([
            'per_page', 'page', 'search',
            'filter', 'sort', 'order'
        ]), [
            'per_page' => 'integer|min:1',
            'page' => 'integer|min:1',
            'search' => 'nullable|string|max:255',
            'sort' => 'nullable|alpha_dash|in:'.implode(',',array_keys($arrangedColumns)),
            'order' => 'required_with:sort|in:asc,desc',
            'filter' => 'nullable|in:Active,Trashed,All',
        ]);

        if($validator->fails()) {
            return ['errors'=>$validator->getMessageBag()];
        }

        if ($request->has('search') && !is_null($request->get('search'))) {
            foreach ($arrangedColumns as $key => $value) {
                $query->orWhere($value, 'LIKE', '%' . $request->get('search') . '%');
            }
        }

        if (
            ($request->has('sort') && !is_null($request->get('sort')))
            &&
            ($request->has('order') && !is_null($request->get('order')))
        ) {
            $query->orderBy($request->get('sort'), strtolower($request->get('order')) === 'desc' ? 'desc' : 'asc');
        }


        if ($request->has('filter') && !is_null($request->get('filter'))) {
            $filter = trim(strtolower($request->get('filter')));
            if ($filter == 'trashed') {
                $query->onlyTrashed();
            } elseif ($filter === 'all') {
                $query->withTrashed();
            }
            // active no default filter
        }

        $result['data'] = $query->get();
        $result['total'] = count($result['data']);

        if ($request->has('per_page')) {
            $result = $query->paginate($request->get('per_page'));
        }

        return $result;
    }

}
