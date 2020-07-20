<?php

namespace App\Http\Controllers\API;

use App\Helpers\Datatable;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $columns = [
            'users.id',
            'users.avatar',
            'users.name',
            'users.gender',
            'users.email',
            'users.created_at',
            'users.updated_at',
            'users.deleted_at',
        ];

        $query = User::query()
            ->select($columns);

        return response(Datatable::make($query,$columns));
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
            'avatar' => 'nullable|image|dimensions:ratio=1|mimes:jpeg,jpg,png|nullable|max:1999',
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
        $user = User::findOrFail($id);
        $user->delete();
        return response([
            'data' => $user
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

    public function getData(){
        $data = User::class;
        return response([
            'model' => $data::searchPaginateAndOrder(),
        ]);
    }
}
