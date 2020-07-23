<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * Update
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $requestData = $request->only([
            'name',
            'gender'
        ]);

        $validator = Validator::make($requestData, [
            'name' => 'required|string|max:255',
            'gender' => 'required|string|in:Male,Female',
        ]);

        if ($validator->fails()) return response(['message' => 'There is a problem with your request', 'errors' => $validator->errors()], 422);

        $data = Auth::user()->update($requestData);

        return response([
            'data' => $data
        ],200);
    }
}
