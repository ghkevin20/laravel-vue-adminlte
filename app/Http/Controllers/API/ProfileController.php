<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class ProfileController extends Controller
{
    /**
     * Update
     *
     * @param \Illuminate\Http\Request $request
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
        ], 200);
    }

    /**
     * Change Password
     *
     * @param \Illuminate\Http\Request $request
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
        ], 200);
    }

    /**
     * Upload Avatar
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function uploadAvatar(Request $request)
    {
        $requestData = $request->only([
            'avatar',
        ]);

        $validator = Validator::make($requestData, [
            'avatar' => 'nullable|image|dimensions:ratio=1|mimes:jpeg,jpg,png|nullable|max:2048',
        ]);

        if ($validator->fails()) return response(['message' => 'There is a problem with your request', 'errors' => $validator->errors()], 422);

        $data = Auth::user();

        if ($request->hasFile('avatar')) {
            // Delete Previous File
            if (!is_null($data->avatar)) {
                Storage::delete('public/avatars/' . $data->avatar);
            }

            $image = $request->file('avatar');

            $image_name = uniqid(rand(100, 999)) . '_' . time() . '.png';

            $destinationPath = storage_path('app/public/avatars');

            $resize_image = Image::make($image->getRealPath());

            $resize_image->resize(300, 300, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath . '/' . $image_name);

            // Store in Database
            $requestData['avatar'] = $image_name;
        }

        $data = $data->update([
            'avatar' => $requestData['avatar']
        ]);

        return response([
            'data' => $data
        ], 200);
    }
}
