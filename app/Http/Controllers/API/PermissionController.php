<?php

namespace App\Http\Controllers\API;

use App\Helpers\Datatable;
use App\Http\Controllers\Controller;
use App\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PermissionController extends Controller
{
    public function __construct()
    {
        /**
         * Super Admin role always granted in all permissions
         * Check User if there's a permission
         */
        $this->middleware('permission:Browse Permission', ['only' => ['index']]);
        $this->middleware('permission:Create Permission', ['only' => ['store']]);
        $this->middleware('permission:Edit Permission', ['only' => ['show', 'update']]);
        $this->middleware('permission:View Permission', ['only' => ['show']]);
        $this->middleware('permission:Delete Permission', ['only' => ['destroy']]);
        $this->middleware('permission:Restore Permission', ['only' => ['restore']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Datatable::make(
            Permission::class,
            ['id', 'name', 'created_at', 'updated_at'], // searchFields
            ['id', 'name', 'created_at', 'updated_at'], // allowedFields
            ['id', 'name', 'created_at', 'updated_at'], // allowedSorts
            ['roles_list'] // allowedAppends
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
            'name' => 'required|string|max:255|unique:permissions,name',
            'roles.*' => 'required|exists:roles,id',
        ]);

        if ($validator->fails()) return response(['message' => 'There is a problem with your request', 'errors' => $validator->errors()], 422);

        $data = Permission::create($requestData);
        $data->roles()->sync($request->roles);

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
        $data = Permission::with(['roles'])->findOrFail($id);

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
        $data = Permission::findOrFail($id);

        $requestData = $request->only([
            'name'
        ]);

        $validator = Validator::make($requestData, [
            'name' => 'required|string|max:255|unique:permissions,name,' . $id,
            'roles.*' => 'required|exists:roles,id',
        ]);

        if ($validator->fails()) return response(['message' => 'There is a problem with your request', 'errors' => $validator->errors()], 422);

        $data->update($requestData);
        $data->roles()->sync($request->roles);

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
        $data = Permission::findOrFail($id);
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
        $data = Permission::query();
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
