<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    /**
     * Login
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $user = $request->only(['email','password']);

        $validator = Validator::make($user, [
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) return response(['message' => 'There is a problem with your request', 'errors' => $validator->errors()], 422);

        if (!Auth::attempt($user,$request->filled('remember'))) return response(['message' => 'Invalid login credentials.'], 401);

        return response([
            'data' => Auth::user(),
        ], 200);
    }

    /**
     * Check api
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function check()
    {
        if (!Auth::check()) {
            return response([
                'authenticated' => false,
            ], 200);
        }else{
            $data = Auth::user();
            $data->load('roles');
            $data->load('permissions');
            return response([
                'authenticated' => true,
                'data' => $data
            ], 200);
        }
    }

    /**
     * Logout
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function logout()
    {
        if (!Auth::check()) {
            return response(['message' => 'Unauthenticated.'], 401);
        }
        Auth::logout();
        return response(['data' => true], 200);
    }

    /**
     * Register
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $requestData = $request->all();

        $validator = Validator::make($requestData, [
            'name' => 'required|string|max:255',
            'gender' => 'required|string|in:Male,Female',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|max:16|confirmed',
            'password_confirmation' => 'required_with:password'
        ]);

        if ($validator->fails()) return response(['message' => 'There is a problem with your request', 'errors' => $validator->errors()], 422);


        $requestData['password'] = Hash::make($requestData['password']);

        $data = User::create($requestData);

        return response([
            'data' => $data
        ],200);
    }
}
