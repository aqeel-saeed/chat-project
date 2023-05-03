<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\AuthenticationException;

class AuthController extends Controller
{
    // register a new user
    public function signUp (Request $request) {
        // writing the rules to validate register data
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users|max:255',
            'password' => 'required|string|min:8|max:255|confirmed|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/',
        ];

        // validate register data with the previous rules
        $validator = Validator::make($request->all(), $rules);

        // if validator fails, return all errors with details to know how to be valid
        if ($validator->fails()) {
            return response()->json(
                $validator->errors()->all(),
                Response::HTTP_UNPROCESSABLE_ENTITY // = 422
            );
        }

        // encrypting the pass by hashing
        $request['password'] = Hash::make($request['password']);

        // creating a user
        $user = User::query()->create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        // add token to the user
        $tokenResult = $user->createToken('Personal Access Token');

        // collect the data to return it in the response
        $data['user'] = $user;
        $data['token_type'] = 'Bearer';
        $data['access_token'] = $tokenResult->accessToken;

        return response()->json(
            $data,
            Response::HTTP_OK // = 200
        );
    }

    public function logIn (Request $request) {
        // writing the rules to validate register data
        $rules = [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
        ];

        // validate register data with the previous rules
        $validator = Validator::make($request->all(), $rules);

        // if validator fails, return all errors with details to know how to be valid
        if ($validator->fails()) {
            return response()->json(
                $validator->errors()->all(),
                Response::HTTP_UNPROCESSABLE_ENTITY // = 422
            );
        }

        $credentials = request(['email', 'password']);

        // check if email & pass are correct
        if (!Auth::attempt($credentials)) {
            throw new AuthenticationException();
        }

        $user = $request->user();

        // add token to the user
        $tokenResult = $user->createToken('authToken');

        // collect the data to return it in the response
        $data['user'] = $user;
        $data['token_type'] = 'Bearer';
        $data['access_token'] = $tokenResult->accessToken;

        return response()->json(
            $data,
            Response::HTTP_OK // = 200
        );
    }

    public function logOut (Request $request) {
        // revoke the token
        $request->user()->token()->revoke();

        return response()->json(
            [
                "message" => "logged out successfully"
            ],
            Response::HTTP_OK // = 200
        );
    }
}
