<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    function doRegister(Request $request) {
        $validation = Validator::make($request->all(), [
            "name" => "required",
            "email" => "required|email|unique:users,email",
            "password" => "required"
        ]);

        if($validation->fails()) {
            return response()->json($validation->errors(), 401);
        }

        $data = [
            "name" => $request->name,
            "email" => $request->email,
            "password" => $request->password,
            "token" => Str::random(5)
        ];

        Hash::make($data["password"]);
        Hash::make($data["token"]);
        $user = User::create($data);

        if($user) {
            return response()->json([
                "Header" => [
                    "Response code :" => 200
                ],
                "Body" => [
                    "message" => "Register successfull" 
                ]
            ], 200);
        } else {
            return response()->json([
                "Header" => [
                    "Response code :" => 401
                ],
                "Body" => [
                    "message" => $validation->errors() 
                ]
            ], 401);
        }

        return response()->json([
            "Header" => [
                "Response code :" => 401
            ],
            "Body" => [
                "message" => $validation->errors() 
            ]
        ], 401);
    }
    
    function doLogin(Request $request) {
        $validation = Validator::make($request->all(), [
            "email" => "required|email|exists:users,email",
            "password" => "required"
        ]);

        if($validation->fails()) {
            return response()->json($validation->errors(), 401);
        }

        $user = User::firstWhere("email", $request->email);

        if($user) {
            if(Hash::check($request->password, $user->password)) {
                return response()->json([
                    "Header" => [
                        "Response code :" => 200
                    ],
                    "Body" => [
                        "message" => "Login successfull",
                        "user" => $user
                    ]
                ], 200);
            } else {
                return response()->json([
                    "Header" => [
                        "Response code :" => 401
                    ],
                    "Body" => [
                        "message" => "incorrect password",
                    ]
                ], 401);
            }
        }


        return response()->json([
            "Header" => [
                "Response code :" => 401
            ],
            "Body" => [
                "message" => $validation->errors() 
            ]
        ], 401);
    }
}
