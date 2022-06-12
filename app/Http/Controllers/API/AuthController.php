<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * login to app.
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $data = $request->all();
        $rules = [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ];
        $messages = [
            'required' => 'Atribut :attribute wajib diisi!.',
            'email' => 'Atribut :attribute harus berupa email yang valid!.',
            'string' => 'Atribut :attribute harus berupa string!.'
        ];
        $validator = Validator::make($data, $rules, $messages);
        $response = [
            'message' => 'Data tidak valid!',
            'errors' => $validator->errors()
        ];
        if($validator->fails()) return response()->json($response, 400);
        if(Auth::attempt($data)) {
            $user = Auth::user();
            if($user->tokens) $user->tokens()->delete();
            try {
                $user->api_token = $user->createToken($data['email'])->plainTextToken;
                $user->save();
            } catch (QueryException $e) {
                $response = [
                    'message' => 'Terjadi kesalahan!',
                    'error' => $e->getMessage()
                ];
                return response()->json($response, 500);
            }
            
            $response = [
                'message' => 'Login berhasil!',
                'data' => [$user]
            ];
            return response()->json($response, 200);
        } else {
            $response = [
                'message' => 'Login gagal!',
                'error' => 'Email atau password salah!'
            ];
            return response()->json($response, 400);
        }
    }

    /**
     * logout from app.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        auth()->user()->tokens()->delete();
        $response = [
            'message' => 'Logout berhasil!'
        ];
        return response()->json($response, 200);
    }
}
