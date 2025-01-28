<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\vendedores;
use Illuminate\Support\Facades\DB;

class vendedoresController extends Controller
{
    public function createVendedores(Request $request) {

        if(!AuthController::autenticate($request->header('token'))){
            $response = array(
                "success" => false,
                "message" => "Usuário não autenticado"
            );
            return response(json_encode($response), 403);
        }
        
        else{

            $vendedores = new vendedores;
            $vendedores->nome = $request->nome;
            $vendedores->telefone = $request->telefone;
            $vendedores->email = $request->email;
            $vendedores->save();

            $response = array(
                "success" => true,
                "message" => "Vendedor cadastrado"
            );
            return response(json_encode($response), 200);

        }
    }

    public function updateVendedores(Request $request) {

        if(!AuthController::autenticate($request->header('token'))){
            $response = array(
                "success" => false,
                "message" => "Usuário não autenticado"
            );
            return response(json_encode($response), 403);
        }
        else{
            if (vendedores::where('id', $request->id)->exists()) {
                $vendedores = vendedores::find($request->id);
                $vendedores->nome = is_null($request->nome) ? $vendedores->nome : $request->nome;
                $vendedores->telefone = is_null($request->telefone) ? $vendedores->telefone : $request->telefone;
                $vendedores->email = is_null($request->email) ? $vendedores->email : $request->email;
                $vendedores->id_usuario = is_null($request->id_usuario) ? $vendedores->id_usuario : $request->id_usuario;
                $vendedores->save();

                $response = array(
                    "success" => true,
                    "message" => "Registro atualizado!"
                );
                return response(json_encode($response), 200);
        
            } else {
            
                $response = array(
                    "success" => false,
                    "message" => "Erro ao atualizar!"
                );
                return response(json_encode($response), 200);
            }
        }
    }

    public function deleteVendedores(Request $request, $id) {
        if(!AuthController::autenticate($request->header('token'))){
            $response = array(
                "success" => false,
                "message" => "Usuário não autenticado"
            );
            return response(json_encode($response), 403);
        }
        else{

            if(vendedores::where('id', $id)->exists()) {
                $vendedores = vendedores::find($id);
                $vendedores->delete();

                $response = array(
                    "success" => true,
                    "message" => "Registro apagado com sucesso!"
                );

                return response(json_encode($response), 200);

            } else {
                
                $response = array(
                    "success" => false,
                    "message" => "Erro ao excluir!"
                );

                return response(json_encode($response), 200);
            }
        }
    }

    public function getAllVendedores(Request $request) {

        if(!AuthController::autenticate($request->header('token'))){
            $response = array(
                "success" => false,
                "message" => "Usuário não autenticado"
            );
            return response(json_encode($response), 403);
        }
        
        else{

            $vendedores = DB::select('SELECT    vendedores.id as id,
                                                vendedores.nome as nome, 
                                                vendedores.telefone as telefone, 
                                                vendedores.email as email
                                    FROM vendedores');

            $response = array(
                "success" => true,
                "vendedores" => $vendedores
            );

            return response(json_encode($response), 200);
        }

    }
}
