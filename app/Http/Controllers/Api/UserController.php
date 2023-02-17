<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Libraries\ResponseBase;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        if (Cache::has('users')) {
            $users = Cache::get('users');
        } else {
            $users = User::all();
            Cache::put('users', $users, 600);
        }
        if (count($users) > 0) {
            $response = [
                'data' => $users,
                'message' => "Successfully recieved users",
            ];
            return ResponseBase::success($response);
        }

        return ResponseBase::error("Users not found", 404);
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return response()->json($validator->errors(), 422);

        try {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->save();

            Cache::forget('users');

            $response = [
                'data' => $user,
                'message' => "Successfully added user",
            ];
            return ResponseBase::success($response, 201);

        } catch (\Exception $e) {
            return ResponseBase::error($e->getMessage(), 409);
        }
    }

    public function show($id)
    {
        $user = User::find($id);
        if ($user) {
            $response = [
                'data' => $user,
                'message' => "Successfully recieved user",
            ];
            return ResponseBase::success($response);
        }

        return ResponseBase::error("User not found", 404);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($id)],
            'password' => 'required|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return response()->json($validator->errors(), 422);

        try {
            $user = User::find($id);
            if (!$user){
                return ResponseBase::error("User not found", 404);
            }

            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->save();

            Cache::forget('users');

            $response = [
                'data' => $user,
                'message' => "Successfully updated user",
            ];
            return ResponseBase::success($response, 201);
        } catch (\Exception $e) {
            return ResponseBase::error($e->getMessage(), 409);
        }
    }

    public function delete($id)
    {
        try {
            $user = User::find($id);
            if (!$user){
                return ResponseBase::error("User not found", 404);
            }

            $user->delete();
            Cache::forget('courses');
            $response = [
                'message' => "Successfully deleted user",
            ];
            return ResponseBase::success($response, 201);
        } catch (\Exception $e) {
            return ResponseBase::error($e->getMessage(), 409);
        }
    }
}
