<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Validation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ValidationController extends Controller
{
    function makeValidation(Request $request) {
        $validation = Validator::make($request->all(), [
            "token" => "required|exists:users,token",
            "notes" => "required"
        ]);

        if($validation->fails()) {
            return response()->json($validation->errors(), 401);
        }

        $user = User::firstWhere("token",  $request->token);

        $data = [
            "user_id" => $user->id,
            "notes" => $request->notes,
            "status" => "pending"
        ];

        $validations = Validation::create($data);

        if($validations) {
            return response()->json([
                "Header" => [
                    "Response code :" => 200
                ],
                "Body" => [
                    "message" => "Validation sent successfull"
                ]
            ], 200);
        }

        return response()->json([
            "Header" => [
                "Response code :" => 401
            ],
            "Body" => [
                "message" => "Unauthorized user"
            ]
        ], 401);
    }

    function getValidation(Request $request) {
        $validation = Validator::make($request->all(), [
            "token" => "required|exists:users,token",
        ]);

        if($validation->fails()) {
            return response()->json($validation->errors(), 401);
        }

        $user = User::firstWhere("token",  $request->token);

        $data = Validation::where("user_id", $user->id)->get();

        if(count($data) > 0) {
            return response()->json([
                "Header" => [
                    "Response code :" => 200
                ],
                "Body" => [
                    "validation" => $data
                ]
            ], 200);
        }  return response()->json([
            "Header" => [
                "Response code :" => 200
            ],
            "Body" => [
                "message" => "You didn't make any validation yet"
            ]
        ], 200);

        return response()->json([
            "Header" => [
                "Response code :" => 401
            ],
            "Body" => [
                "message" => "Unauthorized user"
            ]
        ], 401);
    }
}
