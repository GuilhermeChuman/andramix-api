<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\medidas;

class medidasController extends Controller
{
    public function createMedidas(Request $request) {
        if(!AuthController::autenticate($request->header('token'))){
            $response = array(
                "success" => false,
                "message" => "Usuário não autenticado"
            );
            return response(json_encode($response), 403);
        }
        
        else{

            $medidas = new medidas;
            $medidas->descricao = $request->descricao;

            $medidas->save();
        
            $response = array(
                "success" => true,
                "medidas" => $medidas
            );

            return response($response, 200);
        }
    }

    public function updateMedidas(Request $request) {
        if(!AuthController::autenticate($request->header('token'))){
            $response = array(
                "success" => false,
                "message" => "Usuário não autenticado"
            );
            return response(json_encode($response), 403);
        }
        

        else{
            if (medidas::where('id', $request->id)->exists()) {
                $medidas = medidas::find($request->id);
                $medidas->descricao = is_null($request->descricao) ? $medidas->descricao : $request->descricao;
                $medidas->save();
        
                $response = array(
                    "success" => true,
                    "message" => "Medida atualizada!"
                );
                return response(json_encode($response), 200);

                } 
            else {

                $response = array(
                    "success" => false,
                    "message" => "Medida não encontrada"
                );
                return response(json_encode($response), 200);
            }
        }
        
    }

    public function deleteMedidas(Request $request, $id) {
        if(!AuthController::autenticate($request->header('token'))){
            $response = array(
                "success" => false,
                "message" => "Usuário não autenticado"
            );
            return response(json_encode($response), 403);
        }
        

        else{
            if(medidas::where('id', $id)->exists()) {
                $medidas = medidas::find($id);
                $medidas->delete();

                $response = array(
                    "success" => true,
                    "message" => "Medida apagada!"
                );
                return response(json_encode($response), 200);

            } else {
                
                $response = array(
                    "success" => false,
                    "message" => "Medida não encontrada!"
                );
                return response(json_encode($response), 200);
            }
        }
        
    }

    public function getAllMedidas(Request $request) {

        if(!AuthController::autenticate($request->header('token'))){
            $response = array(
                "success" => false,
                "message" => "Usuário não autenticado"
            );
            return response(json_encode($response), 403);
        }
        
        else{
            $medidas = medidas::get();

            $response = array(
                "success" => true,
                "medidas" => $medidas
            );
            return response(json_encode($response), 200);
        }
    }
}
