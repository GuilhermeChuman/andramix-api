<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\enderecos;
use Illuminate\Support\Facades\DB;

class enderecosController extends Controller
{
    public function createEnderecos(Request $request) {
        if(!AuthController::autenticate($request->header('token'))){
            $response = array(
                "success" => false,
                "message" => "Usuário não autenticado"
            );
            return response(json_encode($response), 403);
        }
        
        else{

            $enderecos = new enderecos;
            $enderecos->rua = $request->rua;
            $enderecos->bairro = $request->bairro;
            $enderecos->cidade = $request->cidade;
            $enderecos->numero = $request->numero;
            $enderecos->id_tipo = $request->idTipo;

            $enderecos->save();
        
            $response = array(
                "success" => true,
                "message" => "enderecos record created!"
            );
            return response($response, 200);
        }
    }

    public function updateEndereco(Request $request) {

        if(!AuthController::autenticate($request->header('token'))){
            $response = array(
                "success" => false,
                "message" => "Usuário não autenticado"
            );
            return response(json_encode($response), 403);
        }
        
        else{

            if (enderecos::where('id', $request->id)->exists()) {
                $enderecos = enderecos::find($request->id);
                $enderecos->rua = is_null($request->rua) ? $enderecos->rua : $request->rua;
                $enderecos->bairro = is_null($request->bairro) ? $enderecos->bairro : $request->bairro;
                $enderecos->cidade = is_null($request->cidade) ? $enderecos->cidade : $request->cidade;
                $enderecos->numero = is_null($request->numero) ? $enderecos->numero : $request->numero;
                $enderecos->id_tipo = is_null($request->idTipo) ? $enderecos->id_tipo : $request->idTipo;
                $enderecos->save();
        
                $response = array(
                    "success" => true,
                    "message" => "Registro atualizado"
                );
                return response(json_encode($response), 200);

            } else {

                $response = array(
                    "success" => false,
                    "message" => "Registro não encontrado"
                );
                return response(json_encode($response), 200);
                
            }
        }
    }

    public function deleteEnderecos(Request $request, $id) {
        if(!AuthController::autenticate($request->header('token'))){
            $response = array(
                "success" => false,
                "message" => "Usuário não autenticado"
            );
            return response(json_encode($response), 403);
        }
        
        else{

            if(enderecos::where('id', $id)->exists()) {
                $enderecos = enderecos::find($id);
                $enderecos->delete();

                $response = array(
                    "success" => true,
                    "message" => "Endereço deletado"
                );

                return response(json_encode($response), 200);

            } else {

                $response = array(
                    "success" => false,
                    "message" => "Endereço não encontrado"
                );

                return response(json_encode($response), 200);
            }
        }
    }

    public function getAllEnderecos(Request $request) {

        if(!AuthController::autenticate($request->header('token'))){
            $response = array(
                "success" => false,
                "message" => "Usuário não autenticado"
            );
            return response(json_encode($response), 403);
        }
        
        else{

            $enderecos = DB::select('SELECT enderecos.id as id,
                                            enderecos.rua as rua, 
                                            enderecos.bairro as bairro, 
                                            enderecos.cidade as cidade,
                                            enderecos.numero as numero,
                                            enderecos.id_tipo as id_tipo,
                                            tipos_endereco.descricao as tipo
                                    FROM enderecos INNER JOIN tipos_endereco on enderecos.id_tipo = tipos_endereco.id');

            $response = array(
                "success" => true,
                "enderecos" => $enderecos
            );

            return response($response, 200);
        }
    }
}
