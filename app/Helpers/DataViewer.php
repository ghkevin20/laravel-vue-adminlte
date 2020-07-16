<?php
namespace App\Helpers;
use Validator;

trait DataViewer {

    protected $operators = [
        'equal' => '=',
        'not_equal' => '<>',
        'less_than' => '<',
        'greater_than' => '>',
        'less_than_or_equal_to' => '<=',
        'greater_than_or_equal_to' => '>=',
        'in' => 'IN',
        'like' => 'LIKE'
    ];

    public function scopeSearchPaginateAndOrder($query)
    {
        $request = app()->make('request');

        $v = Validator::make($request->only([
            'column', 'direction', 'per_page',
            'search_column', 'search_operator', 'search_input'
        ]), [
            'column' => 'nullable',
            'direction' => 'in:asc,desc',
            'per_page' => 'integer|min:1',
            'search_input' => 'max:255'
        ]);

        if($v->fails()) {
            throw new \Illuminate\Validation\ValidationException($v);
        }


        if($request->has('column') && $request->has('direction')) {
            $query->orderBy($request->column, $request->direction);
        }

        if($request->has('search_input') && $request->search_input <> ""){
            $query->where(function($query) use ($request) {
                foreach(self::$columns as $column){
                    $query->orWhere($column, 'LIKE', '%'.$request->search_input.'%');
                }
            });
        }
        if($request->has('filter') && $request->filter <> ""){
            if(strtolower($request->filter) === 'trashed'){
                $query->onlyTrashed();
            }elseif (strtolower($request->filter) === 'all'){
                $query->withTrashed();
            }
            // else active default
        }

        $per_page = $request->has('per_page')?$request->per_page:10;

        $result = $query->paginate($per_page);

        return $result;
    }

}
