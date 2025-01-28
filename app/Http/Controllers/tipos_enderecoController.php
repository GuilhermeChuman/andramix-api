<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\tipos_endereco;

class tipos_enderecoController extends Controller
{
    public function createTiposEnderecos(Request $request) {
        $tipos_endereco = new tipos_endereco;
        $tipos_endereco->desc = $request->desc;

        $tipos_endereco->save();
    
        return response()->json([
            "message" => "tipos_endereco record created"
        ], 201);
    }

    public function updateTiposenderecos($id) {
        if (tipos_endereco::where('id', $id)->exists()) {
            $tipos_endereco = tipos_endereco::find($id);
            $tipos_endereco->desc = is_null($request->desc) ? $tipos_endereco->desc : $request->desc;
            $tipos_endereco->save();
    
            return response()->json([
                "message" => "records updated successfully"
            ], 200);
            } else {
            return response()->json([
                "message" => "tipo de endereco não encontrado"
            ], 404);
            
        }
    }

    public function deleteTiposenderecos($id) {
        if(tipos_endereco::where('id', $id)->exists()) {
            $tipos_endereco = tipos_endereco::find($id);
            $tipos_endereco->delete();

                return response()->json([
                "message" => "records deleted"
                ], 202);
        } else {
            return response()->json([
            "message" => "tipo de endereco não encontrado"
            ], 404);
        }
    }

    public function getAllTiposenderecos(Request $request) {

        if(!AuthController::autenticate($request->header('token'))){
            $response = array(
                "success" => false,
                "message" => "Usuário não autenticado"
            );
            return response(json_encode($response), 403);
        }
        
        else{
            $response = array(
                "success" => true,
                "tiposEndereco" => tipos_endereco::get()
            );

            return response($response, 200);
        }

        
    }
}
