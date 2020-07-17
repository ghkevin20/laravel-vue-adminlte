<?php

namespace App\Http\Controllers\API;

use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Datatable;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


        $query = Category::query()
            ->join('users', 'user_id', '=', 'users.id');

        return response([
            'request' => $query
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function datatable(Request $request)
    {
        $columns = [
            'categories.id',
            'categories.name',
            'users.name AS user_name',
            'categories.created_at',
            'categories.updated_at',
            'categories.deleted_at',
        ];

        $query = Category::query()
            ->join('users', 'user_id', '=', 'users.id')
            ->select($columns);

        return response([
            'model' => Datatable::make($query,$columns),
        ]);
    }
}
