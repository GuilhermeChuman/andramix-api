<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\modelos;

class modelosController extends Controller
{
    public function createModelos(Request $request) {

        if(!AuthController::autenticate($request->header('token'))){

            $response = array(
                "success" => false,
                "message" => "Usuário não autenticado"
            );
            return response(json_encode($response), 403);
        }
        else{

            $modelos = new modelos;
            $modelos->nome = $request->nome;

            $modelos->save();
        
            $response = array(
                "success" => true,
                "message" => "Modelo criado!"
            );
            return response(json_encode($response), 200);
        }
    }

    public function updateModelos(Request $request) {

        if(!AuthController::autenticate($request->header('token'))){

            $response = array(
                "success" => false,
                "message" => "Usuário não autenticado"
            );
            return response(json_encode($response), 403);
        }
        else{

            if (modelos::where('id', $request->id)->exists()) {
                $modelos = modelos::find($request->id);
                $modelos->nome = is_null($request->nome) ? $modelos->nome : $request->nome;
                $modelos->save();
        
                $response = array(
                    "success" => true,
                    "message" => "Modelo atualizado!"
                );
                return response(json_encode($response), 200);

            } else {
                
                $response = array(
                    "success" => false,
                    "message" => "Modelo não encontrado!"
                );
                return response(json_encode($response), 200);
                
            }
        }
    }

    public function deleteModelos(Request $request, $id) {

        if(!AuthController::autenticate($request->header('token'))){

            $response = array(
                "success" => false,
                "message" => "Usuário não autenticado"
            );
            return response(json_encode($response), 403);
        }
        else{

            if(modelos::where('id', $id)->exists()) {
                $modelos = modelos::find($id);
                $modelos->delete();

                $response = array(
                    "success" => true,
                    "message" => "Modelo deletado"
                );

                return response(json_encode($response), 200);

            } else {

                $response = array(
                    "success" => false,
                    "message" => "Modelo não encontrado"
                );

                return response(json_encode($response), 200);
            }
        }
    }

    public function getAllModelos() {

        $modelos = modelos::get();

        $response = array(
            "success" => true,
            "modelos" => $modelos
        );

        return response(json_encode($response), 200);
    }
}
