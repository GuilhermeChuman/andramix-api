<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\clientes;
use App\Models\enderecos;
use Illuminate\Support\Facades\DB;

class clientesController extends Controller
{
    public function createClientes(Request $request) {

        if(!AuthController::autenticate($request->header('token'))){
            $response = array(
                "success" => false,
                "message" => "Usuário não autenticado"
            );
            return response(json_encode($response), 403);
        }
        
        else{
            if(enderecos::where('id', $request->idEndereco)->exists()) {
                
                $clientes = new clientes;
                $clientes->cpf_cnpj = $request->cpfCnpj;
                $clientes->nome = $request->nome;
                $clientes->telefone = $request->telefone;
                $clientes->porcentagem_areia = $request->areia;
                $clientes->porcentagem_concreto = $request->concreto;
                $clientes->save();

                DB::table('enderecos_has_clientes')->insert([
                    'id_enderecos' => $request->idEndereco,
                    'id_clientes' => $clientes->id
                ]);
            
                $response = array(
                    "success" => true,
                    "message" => "Cliente cadastrado"
                );
                return response(json_encode($response), 200);
            }

            else{

                $response = array(
                    "success" => false,
                    "message" => "Endereco não localizado"
                );
                return response(json_encode($response), 200);
            }
        }
    }

    public function updateClientes(Request $request) {

        if(!AuthController::autenticate($request->header('token'))){
            $response = array(
                "success" => false,
                "message" => "Usuário não autenticado"
            );
            return response(json_encode($response), 403);
        }
        
        else{

            if (clientes::where('id', $request->id)->exists()) {

                if(enderecos::where('id', $request->idEndereco)->exists()) {

                    $clientes = clientes::find($request->id);
                    $clientes->cpf_cnpj = is_null($request->cpfCnpj) ? $clientes->cpf_cnpj : $request->cpfCnpj;
                    $clientes->nome = is_null($request->nome) ? $clientes->nome : $request->nome;
                    $clientes->telefone = is_null($request->telefone) ? $clientes->telefone : $request->telefone;
                    $clientes->porcentagem_areia = is_null($request->areia) ? $clientes->porcentagem_areia : $request->areia;
                    $clientes->porcentagem_concreto = is_null($request->concreto) ? $clientes->porcentagem_concreto : $request->concreto;
                    $clientes->save();

                    DB::table('enderecos_has_clientes')
                        ->where('id_clientes',$clientes->id)
                        ->update(['id_enderecos' =>$request->idEndereco]);
            
                    $response = array(
                        "success" => true,
                        "message" => "Cliente atualizado"
                    );
                    return response(json_encode($response), 200);
                }

                else{

                    $response = array(
                        "success" => false,
                        "message" => "Endereco não localizado"
                    );
                    return response(json_encode($response), 200);
                }

            } else {
                
                $response = array(
                    "success" => false,
                    "message" => "Cliente não localizado"
                );
                return response(json_encode($response), 200);
            }
        }
    }

    public function deleteClientes(Request $request, $id) {

        if(!AuthController::autenticate($request->header('token'))){
            $response = array(
                "success" => false,
                "message" => "Usuário não autenticado"
            );
            return response(json_encode($response), 403);
        }
        
        else{

            if(clientes::where('id', $id)->exists()) {

                DB::table('enderecos_has_clientes')->delete([
                    'id_clientes' => $id
                ]);

                $clientes = clientes::find($id);
                $clientes->delete();

                $response = array(
                    "success" => true,
                    "message" => "Cliente deletado"
                );
                return response(json_encode($response), 200);

            } else {

                $response = array(
                    "success" => false,
                    "message" => "Cliente não localizado"
                );
                return response(json_encode($response), 200);
            }
        }
    }

    public function getAllClientes() {

        $clientes = DB::select('SELECT      clientes.id as id,
                                            clientes.cpf_cnpj as cpf_cnpj, 
                                            clientes.nome as nome, 
                                            clientes.telefone as telefone,
                                            clientes.porcentagem_areia as porcentagem_areia,
                                            clientes.porcentagem_concreto as porcentagem_concreto,
                                            enderecos.id as id_endereco,
                                            enderecos.rua as rua,
                                            enderecos.bairro as bairro,
                                            enderecos.cidade as cidade,
                                            enderecos.numero as numero
                                    FROM clientes INNER JOIN enderecos_has_clientes on enderecos_has_clientes.id_clientes = clientes.id
                                    INNER JOIN enderecos on enderecos_has_clientes.id_enderecos = enderecos.id');

        $response = array(
            "success" => true,
            "clientes" => $clientes
        );
        return response(json_encode($response), 200);
    }
}
