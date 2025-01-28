<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\token;

class AuthController extends Controller
{
    public static function generateToken(){

        $token = new token;
        $tk = bin2hex(random_bytes(20));
        $token->token = $tk;

        //garantir que não haja tokens duplicados
        if(token::where('token', $tk)->exists())
            AuthController::generateToken();

        $token->save();

        return $tk;
    }

    public static function deleteToken($token){
        if(token::where('token', $token)->exists()) {
            $tokens = token::find($token);
            $tokens->delete();
        }
    }

    public static function autenticate($token){
        return token::where('token', $token)->exists();
    }
}