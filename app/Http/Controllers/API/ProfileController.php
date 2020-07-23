<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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

    /**
     * Change Password
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function changePassword(Request $request)
    {
        $requestData = $request->only([
            'current_password',
            'new_password',
            'new_password_confirmation',
        ]);

        $validator = Validator::make($requestData, [
            'current_password' => ['required', function ($attribute, $value, $fail) {
                if (!Hash::check($value, Auth::user()->password)) {
                    return $fail(__('The current password is incorrect.'));
                }
            }],
            'new_password' => 'required|string|min:8|max:16|confirmed',
            'new_password_confirmation' => 'required_with:new_password'
        ]);

        if ($validator->fails()) return response(['message' => 'There is a problem with your request', 'errors' => $validator->errors()], 422);

        $data = Auth::user()->update([
            'password' => Hash::make($requestData['new_password'])
        ]);

        return response([
            'data' => $data
        ],200);
    }
}
