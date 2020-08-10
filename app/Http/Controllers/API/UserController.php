<?php

namespace App\Http\Controllers\API;

use App\Helpers\Datatable;
use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class UserController extends Controller
{

    public function __construct()
    {
        /**
         * Super Admin role always granted in all permissions
         * Check User if there's a permission
         */
        $this->middleware('permission:Browse User', ['only' => ['index', 'countNew', 'countScoped', 'reportPerMonth', 'reportGender']]);
        $this->middleware('permission:Create User', ['only' => ['store']]);
        $this->middleware('permission:Edit User', ['only' => ['show', 'update']]);
        $this->middleware('permission:View User', ['only' => ['show']]);
        $this->middleware('permission:Delete User', ['only' => ['destroy']]);
        $this->middleware('permission:Restore User', ['only' => ['restore']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Datatable::make(
            User::class,
            ['id', 'name', 'gender', 'email', 'created_at', 'updated_at'], // searchFields
            ['id', 'avatar', 'name', 'gender', 'email', 'created_at', 'updated_at', 'deleted_at'], // allowedFields
            ['id', 'avatar', 'name', 'gender', 'email', 'created_at', 'updated_at'], // allowedSorts
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
            'avatar',
            'name',
            'gender',
            'email',
            'password',
            'password_confirmation'
        ]);

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

        $requestData['password'] = Hash::make($requestData['password']);

        $data = User::create($requestData);
        $data->syncRoles($request->roles);

        return response([
            'data' => $data
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = User::with('roles')->findOrFail($id);

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
        $data = User::findOrFail($id);
        $requestData = $request->only([
            'avatar',
            'name',
            'gender',
            'email',
            'password',
            'password_confirmation'
        ]);

        $validator = Validator::make($requestData, [
            'avatar' => 'nullable|image|dimensions:ratio=1|mimes:jpeg,jpg,png|nullable|max:2048',
            'name' => 'required|string|max:255',
            'gender' => 'required|string|in:Male,Female',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|max:16|confirmed',
            'password_confirmation' => 'required_with:password'
        ]);

        if ($validator->fails()) return response(['message' => 'There is a problem with your request', 'errors' => $validator->errors()], 422);

        if ($request->filled('password')) {
            $requestData['password'] = Hash::make($requestData['password']);
        } else {
            unset($requestData['password']);
        }


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

        $data->update($requestData);
        $data->syncRoles($request->roles);

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
        $data = User::findOrFail($id);
        $data->delete();
        return response([
            'data' => $data
        ], 200);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();
        return response([
            'data' => $user
        ], 200);
    }

    /**
     * Count total new rows of the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function countNew($secondsAgo)
    {
        $pastDate = Carbon::now()->subSecond($secondsAgo)->format('Y-m-d H:i:s');
        $data = User::where('created_at', '>=', $pastDate)->withoutTrashed()->count();
        return response([
            'count' => $data
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
        $data = User::query();
        if (strtolower($scope) === 'trashed') {
            $data->onlyTrashed();
        } else if (strtolower($scope) === 'all') {
            $data->withTrashed();
        } else {
            // active
        }
        return response([
            'count' => $data->get()->count()
        ], 200);
    }

    /**
     * Per month count the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function reportPerMonth()
    {
        $users = User::select('id', 'created_at')
            ->get()
            ->groupBy(function ($date) {
                return Carbon::parse($date->created_at)->format('m'); // grouping by months
            });

        $months = [];
        $data = [];

        foreach ($users as $key => $value) {
            $months[(int)$key] = count($value);
        }

        for ($i = 1; $i <= 12; $i++) {
            if (!empty($months[$i])) {
                $data[$i] = $months[$i];
            } else {
                $data[$i] = 0;
            }
        }

        return response([
            'data' => $data
        ], 200);
    }

    /**
     * Gender count the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function reportGender()
    {
        $male = User::where('gender', 'Male')->count();
        $female = User::where('gender', 'Female')->count();
        return response([
            'male' => $male,
            'female' => $female
        ], 200);
    }
}
