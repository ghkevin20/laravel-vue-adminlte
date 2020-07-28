<?php

namespace App\Http\Controllers\API;

use App\Helpers\Datatable;
use App\Helpers\SearchFields;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class UserController extends Controller
{

    public function __construct()
    {
        /**
         * Super Admin role always granted in all permissions
         * Check User if there's a permission
         */
        $this->middleware('permission:Browse User',['only'=>['index']]);
        $this->middleware('permission:Create User',['only'=>['store']]);
        $this->middleware('permission:Edit User',['only'=>['show','update']]);
        $this->middleware('permission:View User',['only'=>['show']]);
        $this->middleware('permission:Delete User',['only'=>['destroy']]);
        $this->middleware('permission:Restore User',['only'=>['restore']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $request = app()->make('request');

        $data = QueryBuilder::for(User::class)
            ->allowedFilters(
                AllowedFilter::custom(
                    'search',
                    new SearchFields,
                    'id,avatar,name,gender,email,created_at,updated_at'
                )
            )
            ->allowedFields('id','avatar', 'name','gender','email','created_at','updated_at','deleted_at')
            ->allowedSorts('id','avatar', 'name','gender','email', 'created_at','updated_at')
            ->allowedAppends(['permissions_list'])
            ->paginate($request->has('per_page')?$request->per_page:10);

        return response($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $requestData = $request->all();

        $validator = Validator::make($requestData, [
            'avatar' => 'image|dimensions:ratio=1|mimes:jpeg,jpg,png|nullable|max:2048',
            'name' => 'required|string|max:255',
            'gender' => 'required|string|in:Male,Female',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|max:16|confirmed',
            'password_confirmation' => 'required_with:password'
        ]);

        if ($validator->fails()) return response(['message' => 'There is a problem with your request', 'errors' => $validator->errors()], 422);

        if ($request->hasFile('avatar')) {
            // Update the final filename to store and make it unique
            $filename = uniqid(rand(100,999)).'_'.time().'.png';
            // Store in Storage
            $request->file('avatar')->storeAs('public/avatars',$filename);
            // Store in Database
            $requestData['avatar'] = $filename;
        }

        $requestData['password'] = Hash::make($requestData['password']);

        $data = User::create($requestData);

        return response([
            'data' => $data
        ],200);
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = User::findOrFail($id);

        return response([
            'data' => $data
        ],200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = User::findOrFail($id);
        $requestData = $request->all();

        $validator = Validator::make($requestData, [
            'avatar' => 'nullable|image|dimensions:ratio=1|mimes:jpeg,jpg,png|nullable|max:2048',
            'name' => 'required|string|max:255',
            'gender' => 'required|string|in:Male,Female',
            'email' => 'required|email|max:255|unique:users,email,'.$id,
            'password' => 'string|min:8|max:16|confirmed',
            'password_confirmation' => 'required_with:password'
        ]);

        if ($validator->fails()) return response(['message' => 'There is a problem with your request', 'errors' => $validator->errors()], 422);

        if(!$request->has('password')){
            $requestData['password'] = Hash::make($data['password']);
        }else{
            $requestData['password'] = Hash::make($requestData['password']);
        }

        if ($request->hasFile('avatar')) {
            // Delete Previous File
            if (!empty($data->checkavatar)){
                Storage::delete('public/avatars/'.$data->avatar);
            }

            // Update the final filename to store and make it unique
            $filename = uniqid(rand(100,999)).'_'.time().'.png';
            // Store in Storage
            $request->file('avatar')->storeAs('public/avatars',$filename);
            // Store in Database
            $requestData['avatar'] = $filename;
        }

        $data->update($requestData);

        return response([
            'data' => $data
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = User::findOrFail($id);
        $data->delete();
        return response([
            'data' => $data
        ],200);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();
        return response([
            'data' => $user
        ],200);
    }
}
