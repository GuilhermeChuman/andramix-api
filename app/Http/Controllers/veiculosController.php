<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\veiculos;
use Illuminate\Support\Facades\DB;

class veiculosController extends Controller
{
    public function createVeiculos(Request $request) {

        if(!AuthController::autenticate($request->header('token'))){

            $response = array(
                "success" => false,
                "message" => "Usuário não autenticado"
            );
            return response(json_encode($response), 403);
        }
        else{

            $veiculos = new veiculos;
            $veiculos->placa = $request->placa;
            $veiculos->tamanho = $request->tamanho;
            $veiculos->id_modelo = $request->idModelo;

            $veiculos->save();
        
            $response = array(
                "success" => true,
                "message" => "Veículo cadastrado"
            );

            return response(json_encode($response), 200);
        }
    }

    public function updateVeiculos(Request $request) {
        if(!AuthController::autenticate($request->header('token'))){

            $response = array(
                "success" => false,
                "message" => "Usuário não autenticado"
            );
            return response(json_encode($response), 403);
        }
        else{

            if (veiculos::where('id', $request->id)->exists()) {
                $veiculos = veiculos::find($request->id);
                $veiculos->placa = is_null($request->placa) ? $veiculos->placa : $request->placa;
                $veiculos->tamanho = is_null($request->tamanho) ? $veiculos->tamanho : $request->tamanho;
                $veiculos->id_modelo = is_null($request->idModelo) ? $veiculos->id_modelo : $request->idModelo;
                $veiculos->save();
        
                $response = array(
                    "success" => true,
                    "message" => "Veículo atualizado!"
                );
                return response(json_encode($response), 200);

            } else {
                
                $response = array(
                    "success" => false,
                    "message" => "Veículo não encontrado"
                );
                return response(json_encode($response), 200);
                
            }
        }
    }

    public function deleteVeiculos(Request $request, $id) {
        if(!AuthController::autenticate($request->header('token'))){

            $response = array(
                "success" => false,
                "message" => "Usuário não autenticado"
            );
            return response(json_encode($response), 403);
        }
        else{

            if(veiculos::where('id', $id)->exists()) {

                $veiculos = veiculos::find($id);
                $veiculos->delete();

                $response = array(
                    "success" => true,
                    "message" => "Veículo excluído"
                );
                return response(json_encode($response), 200);

            } else {

                $response = array(
                    "success" => false,
                    "message" => "Veículo não encontrado"
                );
                return response(json_encode($response), 200);
            }
        }
    }

    public function getAllVeiculos(Request $request) {

        if(!AuthController::autenticate($request->header('token'))){

            $response = array(
                "success" => false,
                "message" => "Usuário não autenticado"
            );
            return response(json_encode($response), 403);
        }
        else{

            $veiculos = veiculos::get()->toJson(JSON_PRETTY_PRINT);
            
            $modelos = DB::select(' SELECT  veiculos.id as id,
                                            veiculos.placa as placa, 
                                            veiculos.tamanho as tamanho, 
                                            veiculos.id_modelo as id_modelo,
                                            modelos.nome as modelo
                                    FROM veiculos INNER JOIN modelos on veiculos.id_modelo = modelos.id');

            $response = array(
                "success" => true,
                "veiculos" => $modelos
            );
            return response(json_encode($response), 200);
        }
    }
}
