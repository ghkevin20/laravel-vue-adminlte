<?php

namespace App\Http\Controllers\API;

use App\Helpers\Datatable;
use App\Http\Controllers\Controller;
use App\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    public function __construct()
    {
        /**
         * Super Admin role always granted in all permissions
         * Check User if there's a permission
         */
        $this->middleware('permission:Browse Role', ['only' => ['index','countScoped']]);
        $this->middleware('permission:Create Role', ['only' => ['store']]);
        $this->middleware('permission:Edit Role', ['only' => ['show', 'update']]);
        $this->middleware('permission:View Role', ['only' => ['show']]);
        $this->middleware('permission:Delete Role', ['only' => ['destroy']]);
        $this->middleware('permission:Restore Role', ['only' => ['restore']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Datatable::make(
            Role::class,
            ['id', 'name', 'created_at', 'updated_at'], // searchFields
            ['id', 'name', 'created_at', 'updated_at'], // allowedFields
            ['id', 'name', 'created_at', 'updated_at'], // allowedSorts
            ['permissions_list'] // allowedAppends
        );
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
            'permissions.*' => 'required|exists:permissions,id',
        ]);

        if ($validator->fails()) return response(['message' => 'There is a problem with your request', 'errors' => $validator->errors()], 422);

        $data = Role::create($requestData);
        $data->permissions()->sync($request->permissions);

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
            'permissions.*' => 'required|exists:permissions,id',
        ]);

        if ($validator->fails()) return response(['message' => 'There is a problem with your request', 'errors' => $validator->errors()], 422);

        $data->update($requestData);
        $data->permissions()->sync($request->permissions);

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

    /**
     * Count total new rows scoped of the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function countScoped($scope)
    {
        $data = Role::query();
        if(strtolower($scope) === 'trashed'){
            $data->onlyTrashed();
        }else if (strtolower($scope) === 'all'){
            $data->withTrashed();
        }else{
            // active
        }
        return response([
            'count' => $data->get()->count()
        ], 200);
    }

}
