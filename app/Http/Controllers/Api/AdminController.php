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
        $response = [];
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);
        if (!$token) {
            $response['message'] = "Unauthorized";
            return ResponseBase::error($response, 403);
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
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admin',
            'password' => 'required',
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

            return response()->json([
                'status' => "success",
                'message' => "Admin Register Successfully!",
                'data'    => $admin,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => "success",
                'message' => "Failed Register Admin!",
                'error' => $e->getMessage()
            ], 409);
        }
    }
}
