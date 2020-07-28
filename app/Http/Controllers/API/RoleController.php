<?php

namespace App\Http\Controllers\API;

use App\Helpers\SearchFields;
use App\Http\Controllers\Controller;
use App\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

// Importing laravel-permission models

class RoleController extends Controller
{
    public function __construct()
    {
        /**
         * Super Admin role always granted in all permissions
         * Check User if there's a permission
         */
        $this->middleware('permission:Browse Role',['only'=>['index']]);
        $this->middleware('permission:Create Role',['only'=>['store']]);
        $this->middleware('permission:Edit Role',['only'=>['show','update']]);
        $this->middleware('permission:View Role',['only'=>['show']]);
        $this->middleware('permission:Delete Role',['only'=>['destroy']]);
        $this->middleware('permission:Restore Role',['only'=>['restore']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $request = app()->make('request');

        $data = QueryBuilder::for(Role::class)
            ->allowedFilters(AllowedFilter::custom('search', new SearchFields, 'id,name,created_at,updated_at'))
            ->allowedFields('id', 'name','created_at','updated_at')
            ->allowedSorts('id', 'name', 'created_at','updated_at')
            ->allowedAppends(['permissions_list'])
            ->paginate($request->has('per_page')?$request->per_page:10);

        return response($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $requestData = $request->only([
            'name'
        ]);

        $validator = Validator::make($requestData, [
            'name' => 'required|string|max:255|unique:roles,name',
        ]);

        if ($validator->fails()) return response(['message' => 'There is a problem with your request', 'errors' => $validator->errors()], 422);

        $data = Role::create($requestData);

        return response([
            'data' => $data
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Role::with(['permissions'])->findOrFail($id);

        return response([
            'data' => $data
        ], 200);
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
        $data = Role::findOrFail($id);

        $requestData = $request->only([
            'name'
        ]);

        $validator = Validator::make($requestData, [
            'name' => 'required|string|max:255|unique:roles,name,' . $id,
        ]);

        if ($validator->fails()) return response(['message' => 'There is a problem with your request', 'errors' => $validator->errors()], 422);

        $data->update($requestData);

        return response([
            'data' => $data
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Role::findOrFail($id);
        $data->delete();
        return response([
            'data' => $data
        ], 200);
    }

}
