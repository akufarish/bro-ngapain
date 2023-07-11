<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Validation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    function makePost(Request $request) {
        $validation = Validator::make($request->all(), [
            "token" => "required|exists:users,token",
            "thread" => "required",
            "slug" => "required"
        ]);

        if($validation->fails()) {
            return response()->json($validation->errors(), 401);
        }

        $user = User::firstWhere("token", $request->token);
        $validations = Validation::where("user_id", $user->id)->get();

        if($validations[0]->status == "pending") {
            return response()->json([
                "Header" => [
                    "Response code :" => 401
                ],
                "Body" => [
                    "message" => "Yout validation must be accepted by admin first"
                ]
            ], 401);
        }

        $data = [
            "user_id" => $user->id,
            "thread" => $request->thread,
            "slug" => $request->slug
        ];

        $post = Post::create($data);

        if($post) {
            return response()->json([
                "Header" => [
                    "Reponse code :" => 200
                ],
                "Body" => [
                    "message" => "Post uploaded successfull",
                    "post" => $post
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

    function getPost(Request $request) {
        $validation = Validator::make($request->all(), [
            "token" => "required|exists:users,token"
        ]);

        if($validation->fails()) {
            return response()->json($validation->errors(), 401);
        }

        $user = User::firstWhere("token", $request->token);
        $validations = Validation::where("user_id", $user->id)->get();

        if($validations[0]->status == "pending") {
            return response()->json([
                "Header" => [
                    "Response code :" => 401
                ],
                "Body" => [
                    "message" => "Yout validation must be accepted by admin first"
                ]
            ], 401);
        }

        $post = Post::with("user")->get();

        if(count($post) > 0) {
            return response()->json([
                "Header" => [
                    "Response code :" => 200
                ],
                "Body" => [
                    "post" => $post
                ]
            ], 200);
        } else {
            return response()->json([
                "Header" => [
                    "Response code :" => 200
                ],
                "Body" => [
                    "message" => "there's not post available yet"
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

    function getPostById(Request $request, Post $post) {
        $validation = Validator::make($request->all(), [
            "token" => "required|exists:users,token"
        ]);

        if($validation->fails()) {
            return response()->json($validation->errors(), 401);
        }

        $user = User::firstWhere("token", $request->token);
        $validations = Validation::where("user_id", $user->id)->get();

        if($validations[0]->status == "pending") {
            return response()->json([
                "Header" => [
                    "Response code :" => 401
                ],
                "Body" => [
                    "message" => "Yout validation must be accepted by admin first"
                ]
            ], 401);
        }

        $data = $post->load("user");

        if($data) {
            return response()->json([
                "Header" => [
                    "Response code :" => 200
                ],
                "Body" => [
                    "post" => $data
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
}
