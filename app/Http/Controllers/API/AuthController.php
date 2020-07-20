<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $user = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($user)) return response(['message' => 'Invalid login credentials.'], 401);

        return response([
            'data' => Auth::user(),
        ],200);
    }

    /**
     * Me api
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function me()
    {

        if (!Auth::check()) {
            return response(['data'=>'Unauthorized'],401);
        }

        $user = Auth::user();

        return response([
            'data' => $user
        ],200);
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
            return response(['data'=>'Unauthorized'],401);
        }
        Auth::logout();
        return response(['data'=>true],200);
    }
}
