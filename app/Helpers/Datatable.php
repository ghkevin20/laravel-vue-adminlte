<?php
namespace App\Helpers;

class Datatable {

    public static function make($query,$columns){
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
            $query->orderBy($arrangedColumns[$request->get('sort')], strtolower($request->get('order')) === 'desc' ? 'desc' : 'asc');
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
