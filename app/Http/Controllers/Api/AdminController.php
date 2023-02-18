<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Libraries\ResponseBase;
use Illuminate\Http\Request;
use App\Models\Admin;

class AdminController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);
        if (!$token) {
            return ResponseBase::error("Unauthorized", 403);
        }

        $response = [
            'data' => [
                'token' => $token,
                'type' => 'bearer',
            ],
            'message' => 'Successfully Login'
        ];

        return ResponseBase::success($response);
    }

    public function register(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255|regex:/^[a-zA-Z ]+$/',
            'email' => 'required|string|email|max:255|unique:admin',
            'password' => 'required|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return response()->json($validator->errors(), 422);

        try {
            $admin = new Admin;
            $admin->name = $request->name;
            $admin->email = $request->email;
            $admin->password = bcrypt($request->password);
            $admin->save();

            $response = [
                'data' => $admin,
                'message' => "Successfully registered admin",
            ];
            return ResponseBase::success($response, 201);
        } catch (\Exception $e) {
            return ResponseBase::error($e->getMessage(), 409);
        }
    }
}
