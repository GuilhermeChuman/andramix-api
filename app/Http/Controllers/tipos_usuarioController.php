<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\tipos_usuario;

class tipos_usuarioController extends Controller
{
    public function createTiposUsuarios(Request $request) {
        $tipos_usuario = new tipos_usuario;
        $tipos_usuario->desc = $request->desc;

        $tipos_usuario->save();
    
        return response()->json([
            "message" => "tipos_usuario record created"
        ], 201);
    }

    public function updateTiposUsuarios($id) {
        if (tipos_usuario::where('id', $id)->exists()) {
            $tipos_usuario = tipos_usuario::find($id);
            $tipos_usuario->desc = is_null($request->desc) ? $tipos_usuario->desc : $request->desc;
            $tipos_usuario->save();
    
            return response()->json([
                "message" => "records updated successfully"
            ], 200);
            } else {
            return response()->json([
                "message" => "tipo de usuario não encontrado"
            ], 404);
            
        }
    }


    public function deleteTiposUsuarios($id) {
        if(tipos_usuario::where('id', $id)->exists()) {
            $tipos_usuario = tipos_usuario::find($id);
            $tipos_usuario->delete();

                return response()->json([
                "message" => "records deleted"
                ], 202);
        } else {
            return response()->json([
            "message" => "tipo de usuario não encontrado"
            ], 404);
        }
    }

    public function getAllTiposUsuarios(Request $request) {

        if(!AuthController::autenticate($request->header('token'))){

            $response = array(
                "success" => false,
                "message" => "Usuário não autenticado"
            );
            return response(json_encode($response), 403);
        }
        else{

            $tipos_usuario = tipos_usuario::get();

            $response = array(
                "success" => true,
                "tiposUsuario" => $tipos_usuario
            );

            return response(json_encode($response), 200);
        }
    }
}
