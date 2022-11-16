<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;

class AuthController extends BaseController
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'confirm_password' => 'required|same:password'
        ]);

        if ($validator->fails())
        {
            return $this->sendError('Validation error', $validator->errors(), 400);
        }

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $user = User::create($input);
        $success['token'] = $user->createToken('emqu')->plainTextToken();
        $success['name'] = $user->name;

        return $this->sendResponse($success, 'User registered successfully!');
    }

    public function token(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails())
        {
            return $this->sendError('Validation error', $validator->errors(), 400);
        }

        if (Auth::attempt($request->only('email', 'password')))
        {
            $user = Auth::user();
            $success['token'] = $request->user()->createToken('emqu')->plainTextToken;
            $success['email'] = $user->email;

            return $this->sendResponse($success, 'Logged in successfully!');
        }

        return $this->sendError('Unauthorized', ['error' => 'Unauthorized'], 401);
    }
}
