<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;

class RegisterController extends BaseController
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'confirm_password' => 'required|same:password'
        ]);

        if ($validator->fails())
        {
            return $this->sendError('Validation error', $validator->errors());
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
            return $this->sendError('Validation error', $validator->errors());
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password))
        {
            $user = Auth::user();
            $success['token'] = $user->createToken('emqu')->plainTextToken;
            $success['name'] = $user->name;

            return $this->sendResponse($success, 'Logged in successfully!');
        }

        return $this->sendError('Unauthorized', ['error' => 'Unauthorized'], 401);
    }
}
