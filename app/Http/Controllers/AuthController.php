<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $fields = $request->validate([
            'email'    => 'required|string',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $fields['email'])->first();

        if ( ! $user || ! Hash::check($fields['password'], $user->password)) {
            return $this->response(401, [], 'Bad Creds');
        }

        $token = $user->createToken('economist_token')->plainTextToken;

        $response = [
            'user'  => $user,
            'token' => $token
        ];

        return $this->response(200, $response, 'Success');
    }

    public function register(Request $request)
    {
        $fields = $request->validate([
            'name'     => 'required|string',
            'email'    => 'required|string|unique:users,email',
            'color'    => 'string',
            'password' => 'required|string|confirmed'
        ]);

        try {
            DB::beginTransaction();

            $user = User::create([
                'username' => $fields['name'],
                'email'    => $fields['email'],
                'color'    => $fields['color'] ?? '#041287',
                'password' => Hash::make($fields['password'])
            ]);

            $token = $user->createToken('economist_token')->plainTextToken;

            $response = [
                'user'  => $user,
                'token' => $token
            ];

            DB::commit();

            return $this->response(201, $response, 'Success');

        } catch (\Exception $e) {
            DB::rollBack();

            $this->response(500, ['error' => $e->getMessage()], 'Error while processing');
        }


    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Logged out'
        ];
    }
}
