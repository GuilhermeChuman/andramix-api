<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\produtos;
use Illuminate\Support\Facades\DB;

class produtosController extends Controller
{
    public function createProdutos(Request $request) {

        if(!AuthController::autenticate($request->header('token'))){

            $response = array(
                "success" => false,
                "message" => "Usuário não autenticado"
            );
            return response(json_encode($response), 403);
        }
        else{

            $produtos = new produtos;
            $produtos->descricao = $request->descricao;
            $produtos->valor = $request->valor;
            $produtos->id_medida = $request->idMedida;

            $produtos->save();

            $response = array(
                "success" => true,
                "message" => "Produto cadastro!"
            );
            return response(json_encode($response), 200);
        }
    }

    public function updateProdutos(Request $request) {
        if(!AuthController::autenticate($request->header('token'))){

            $response = array(
                "success" => false,
                "message" => "Usuário não autenticado"
            );
            return response(json_encode($response), 403);
        }
        else{
            if (produtos::where('id', $request->id)->exists()) {
                $produtos = produtos::find($request->id);
                $produtos->descricao = is_null($request->descricao) ? $produtos->descricao : $request->descricao;
                $produtos->valor = is_null($request->valor) ? $produtos->valor : $request->valor;
                $produtos->id_medida = is_null($request->idMedida) ? $produtos->id_medida : $request->idMedida;
                $produtos->save();
            
                $response = array(
                    "success" => true,
                    "message" => "Registro atualizado!"
                );

                return response(json_encode($response), 200);

                } else {
                
                $response = array(
                    "success" => false,
                    "message" => "Produto não encontrado!"
                );
    
                return response(json_encode($response), 200);
            }
        }
        
    }

    public function deleteProdutos(Request $request, $id) {
        if(!AuthController::autenticate($request->header('token'))){

            $response = array(
                "success" => false,
                "message" => "Usuário não autenticado"
            );
            return response(json_encode($response), 403);
        }
        else{
            if(produtos::where('id', $id)->exists()) {

                $produtos = produtos::find($id);
                $produtos->delete();
    
                $response = array(
                    "success" => true,
                    "message" => "Produto excluído!"
                );

                return response(json_encode($response), 200);

            } else {
                
                $response = array(
                    "success" => false,
                    "message" => "Produto não encontrado!"
                );
    
                return response(json_encode($response), 200);
            }
        }
    }

    public function getAllProdutos(Request $request) {
        if(!AuthController::autenticate($request->header('token'))){

            $response = array(
                "success" => false,
                "message" => "Usuário não autenticado"
            );
            return response(json_encode($response), 403);
        }
        else{

            $produtos = DB::select('SELECT  produtos.id as id,
                                            produtos.descricao as descricao, 
                                            produtos.valor as valor, 
                                            produtos.id_medida as id_medida,
                                            medidas.descricao as medida
                                    FROM produtos INNER JOIN medidas on medidas.id = produtos.id_medida');

            $response = array(
                "success" => true,
                "produtos" => $produtos
            );

            return response(json_encode($response), 200);
        }
    }
}
