<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends BaseController
{
    public function login(LoginRequest $request)
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $success['token'] =  $user->createToken('MyApp')->plainTextToken;
            $success['user'] =  $user;

            return $this->sendResponse($success, 'User login successfully.');
        } else {
            return $this->sendError('Wrong Password!', ['error'=>'Password is wrong']);
        }
    }

    public function register(RegisterRequest $request)
    {
        try {
            $user = User::create($request->all());
            $success['token'] =  $user->createToken('MyApp')->plainTextToken;
            $success['name'] =  $user->name;
            return $this->sendResponse($success, 'User register successfully.');

        } catch (\Throwable $th) {
            return $this->sendError([$th->getMessage()], '', $th->getCode());
        }

    }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->sendResponse('', 'Logout successfully.');
    }

    public function me(Request $request)
    {
        return $this->sendResponse(new UserResource($request->user()->load('preferences')), 'User Retrieved successfully.');
    }


}
