<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;

use App\Models\usuarios;
use Illuminate\Support\Facades\DB;

class usuariosController extends Controller
{
    public function createUsuarios(Request $request) {
        if(!AuthController::autenticate($request->header('token'))){

            $response = array(
                "success" => false,
                "message" => "Usuário não autenticado"
            );
            return response(json_encode($response), 403);
        }
        else{
            $usuarios = new usuarios;
            $usuarios->usuario = $request->usuario;
            $usuarios->senha = $request->senha;
            $usuarios->id_tipo = $request->idTipoUsuario;

            $usuarios->save();
            
            $response = array(
                "success" => true,
                "message" => "Usuário cadastrado!"
            );
            return response(json_encode($response), 200);
        }
        
    }

    public function updateUsuarios(Request $request) {

        if(!AuthController::autenticate($request->header('token'))){

            $response = array(
                "success" => false,
                "message" => "Usuário não autenticado"
            );
            return response(json_encode($response), 403);
        }
        else{
            if (usuarios::where('id', $request->id)->exists()) {

                $usuarios = usuarios::find($request->id);
                $usuarios->usuario = is_null($request->usuario) ? $usuarios->usuario : $request->usuario;
                $usuarios->senha = is_null($request->senha) ? $usuarios->senha : $request->senha;
                $usuarios->id_tipo = is_null($request->idTipoUsuario) ? $usuarios->id_tipo : $request->idTipoUsuario;
                $usuarios->save();
        
                $response = array(
                    "success" => true,
                    "message" => "Usuário atualizado!"
                );
                return response(json_encode($response), 200);

            } else {

                $response = array(
                    "success" => false,
                    "message" => "Usuário não encontrado!"
                );
                return response(json_encode($response), 200);
                
            }
        }
    }

    public function deleteUsuarios($id) {
        if(usuarios::where('id', $id)->exists()) {
            $usuarios = usuarios::find($id);
            $usuarios->delete();

                return response()->json([
                "message" => "records deleted"
                ], 202);
        } else {
            return response()->json([
            "message" => "tipo de usuario não encontrado"
            ], 404);
        }
    }

    public function login(Request $request) {

        $usuarios = DB::select('SELECT  usuarios.id as id,
                                        usuarios.usuario as usuario, 
                                        usuarios.senha as senha, 
                                        usuarios.id_tipo as id_tipo,
                                        tipos_usuario.descricao as tipo 
                                FROM usuarios INNER JOIN tipos_usuario ON usuarios.id_tipo = tipos_usuario.id
                                WHERE usuarios.usuario = "'.$request->usuario.'" AND usuarios.senha = "'.$request->senha.'"');

        if(!$usuarios){
            $response = array(
                "success" => false,
                "message" => "Usuário não encontrado"
            );

            return response(json_encode($response), 200);
        }
        else{
            $response = $usuarios[0];
            $response->token = AuthController::generateToken();          
            $response->success = true;

            return response(json_encode($response), 200);
        }
    }

    public function logout(Request $request){

        if(!AuthController::autenticate($request->header('token'))){
            $response = array(
                "success" => false,
                "message" => "Usuário não autenticado"
            );
            return response(json_encode($response), 403);
        }
        
        else{
            AuthController::deleteToken($request->header('token'));
            $response = array(
                "success" => true,
                "message" => "Logoff efetuado!"
            );
            return response($response, 200);
        }
    }

    public function getAllUsuarios(Request $request) {
        
        if(!AuthController::autenticate($request->header('token'))){

            $response = array(
                "success" => false,
                "message" => "Usuário não autenticado"
            );
            return response(json_encode($response), 403);
        }
        else{
            $usuarios = DB::select('SELECT  usuarios.id as id,
                                            usuarios.usuario as usuario, 
                                            usuarios.senha as senha, 
                                            usuarios.id_tipo as id_tipo,
                                            tipos_usuario.descricao as tipo 
                                    FROM usuarios INNER JOIN tipos_usuario ON usuarios.id_tipo = tipos_usuario.id');

            $response = array(
                "success" => true,
                "usuarios" => $usuarios
            );
            return response(json_encode($response), 200);
        }

        
    }
}
