<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\vendas;
use Illuminate\Support\Facades\DB;

use PDF;

class vendasController extends Controller
{
    public function createVendas(Request $request) {

        if(!AuthController::autenticate($request->header('token'))){

            $response = array(
                "success" => false,
                "message" => "Usuário não autenticado"
            );

            return response(json_encode($response), 403);
        }
        else{

            DB::beginTransaction();

            try{
                $vendas = new vendas;
                $vendas->data = $request->data;
                $vendas->complemento = $request->complemento;
                $vendas->id_clientes = $request->idCliente;
                $vendas->homologado = 0;
                $vendas->pago = 0;

                $vendas->save();

                $vendedores = json_decode($request->vendedores);
                foreach ($vendedores as $v){
                    DB::table('vendedores_has_vendas')->insert([
                        'id_vendas' => $vendas->id,
                        'id_vendedores' => $v->id
                    ]);
                }

                $produtos = json_decode($request->produtos);
                foreach ($produtos as $p){
                    DB::table('produtos_has_vendas')->insert([
                        'id_venda' => $vendas->id,
                        'id_produto' => $p->prod->id,
                        'quantidade' => $p->qtd
                    ]);
                }

                $veiculos = json_decode($request->veiculos);
                foreach ($veiculos as $v){
                    DB::table('venda_has_veiculo')->insert([
                        'id_venda' => $vendas->id,
                        'id_veiculo' => $v->id
                    ]);
                }

                DB::commit();
            }
            catch (\Exception $e){
                DB::rollBack();
                $response = array(
                    "success" => false,
                    "message" => $e
                );
                
                return response(json_encode($response), 200);
            }

            $response = array(
                "success" => true,
                "message" => "Venda cadastrada!"
            );
            
            return response(json_encode($response), 200);

        }
    }

    public function updateVendas(Request $request) {

        if(!AuthController::autenticate($request->header('token'))){

            $response = array(
                "success" => false,
                "message" => "Usuário não autenticado"
            );

            return response(json_encode($response), 403);
        }
        else{

            if (vendas::where('id', $request->id)->exists()) {

                $vendas = vendas::find($request->id);

                if($vendas->homologado == 1){
                        $response = array(
                            "success" => false,
                            "message" => "Venda homologada não pode ser atualizada!"
                        );
            
                        return response(json_encode($response), 200);
                }
                else{

                    DB::beginTransaction();

                    try{

                        $vendas->data = is_null($request->data) ? $vendas->data : $request->data;
                        $vendas->complemento = is_null($request->complemento) ? $vendas->complemento : $request->complemento;
                        $vendas->id_clientes = is_null($request->idCliente) ? $vendas->id_clientes : $request->idCliente;

                        $vendas->save();

                        DB::table('vendedores_has_vendas')->where('id_vendas', $vendas->id)->delete();
                        $vendedores = json_decode($request->vendedores);
                        foreach ($vendedores as $v){
                            DB::table('vendedores_has_vendas')->insert([
                                'id_vendas' => $vendas->id,
                                'id_vendedores' => $v->id
                            ]);
                        }

                        DB::table('produtos_has_vendas')->where('id_venda', $vendas->id)->delete();
                        $produtos = json_decode($request->produtos);
                        foreach ($produtos as $p){
                            DB::table('produtos_has_vendas')->insert([
                                'id_venda' => $vendas->id,
                                'id_produto' => $p->prod->id,
                                'quantidade' => $p->qtd
                            ]);
                        }

                        DB::table('venda_has_veiculo')->where('id_venda', $vendas->id)->delete();
                        $veiculos = json_decode($request->veiculos);
                        foreach ($veiculos as $v){
                            DB::table('venda_has_veiculo')->insert([
                                'id_venda' => $vendas->id,
                                'id_veiculo' => $v->id
                            ]);
                        }

                        DB::commit();

                        $response = array(
                            "success" => true,
                            "message" => "Venda atualizada!"
                        );
            
                        return response(json_encode($response), 200);

                    }
                    catch (\Exception $e){

                        DB::rollBack();
                        $response = array(
                            "success" => false,
                            "message" => $e
                        );
                        
                        return response(json_encode($response), 200);
                    }
                }       

            } else {
                
                $response = array(
                    "success" => false,
                    "message" => "Venda não encontrada"
                );
    
                return response(json_encode($response), 200);
                
            }
        }
    }

    public function pagarVenda(Request $request, $id) {

        if(!AuthController::autenticate($request->header('token'))){

            $response = array(
                "success" => false,
                "message" => "Usuário não autenticado"
            );

            return response(json_encode($response), 403);
        }
        else{

            if (vendas::where('id', $id)->exists()) {

                $vendas = vendas::find($id);

                $vendas->pago = 1;
                $vendas->save();
            
                $response = array(
                    "success" => true,
                    "message" => "Venda paga!"
                );
        
                return response(json_encode($response), 200);
            
            } else {

                $response = array(
                    "success" => false,
                    "message" => "Venda não encontrada"
                );

                return response(json_encode($response), 200);
            }   
        }
    }

    public function homologarVenda(Request $request, $id) {

        if(!AuthController::autenticate($request->header('token'))){

            $response = array(
                "success" => false,
                "message" => "Usuário não autenticado"
            );

            return response(json_encode($response), 403);
        }
        else{

            if (vendas::where('id', $id)->exists()) {

                $vendas = vendas::find($id);

                $vendas->homologado = 1;
                $vendas->save();
            
                $response = array(
                    "success" => true,
                    "message" => "Venda homologada!"
                );
        
                return response(json_encode($response), 200);

            } else {

                $response = array(
                    "success" => false,
                    "message" => "Venda não encontrada"
                );

                return response(json_encode($response), 200);
            }           
        }
    }

    public function deleteVendas(Request $request, $id) {

        if(!AuthController::autenticate($request->header('token'))){

            $response = array(
                "success" => false,
                "message" => "Usuário não autenticado"
            );

            return response(json_encode($response), 403);
        }
        else{
            if(vendas::where('id', $id)->exists()) {
                $vendas = vendas::find($id);
                $vendas->delete();

                $response = array(
                    "success" => true,
                    "message" => "Venda deletada"
                );
    
                return response(json_encode($response), 200);

            } else {

                $response = array(
                    "success" => false,
                    "message" => "Venda não encontrada"
                );
    
                return response(json_encode($response), 200);
            }
        }

    }

    public function getAllVendas(Request $request) {

        if(!AuthController::autenticate($request->header('token'))){
            $response = array(
                "success" => false,
                "message" => "Usuário não autenticado"
            );
            return response(json_encode($response), 403);
        }
        
        else{

            $vendas =  DB::select('SELECT   vendas.id as id, 
                                            vendas.data as data, 
                                            vendas.complemento as complemento,
                                            vendas.homologado as homologado,
                                            vendas.pago as pago,
                                            clientes.id as idCliente,
                                            clientes.cpf_cnpj as cpfCnpjCliente,
                                            clientes.nome as nomeCliente,
                                            clientes.telefone as telefoneCliente,
                                            clientes.porcentagem_areia as areia,
                                            clientes.porcentagem_concreto as concreto,
                                            sum(produtos_has_vendas.quantidade * produtos.valor) as valorTotal

                                    FROM vendas 
                                    INNER JOIN clientes ON vendas.id_clientes = clientes.id
                                    INNER JOIN venda_has_veiculo ON venda_has_veiculo.id_venda = vendas.id 
                                    INNER JOIN produtos_has_vendas ON produtos_has_vendas.id_venda = vendas.id 
                                    INNER JOIN produtos ON produtos_has_vendas.id_produto = produtos.id
                                    GROUP BY vendas.id
            ');

            $response = array(
                "success" => true,
                "vendas" =>  $vendas
            );

            return response($response, 200);
        }

        
    }

    public function getVendedoresByVenda(Request $request, $id){

        if(!AuthController::autenticate($request->header('token'))){
            $response = array(
                "success" => false,
                "message" => "Usuário não autenticado"
            );
            return response(json_encode($response), 403);
        }
        
        else{

            $vendedores = DB::select("  SELECT  vendedores.nome,
                                                vendedores.id
                                        FROM vendedores
                                        INNER JOIN vendedores_has_vendas ON vendedores_has_vendas.id_vendedores = vendedores.id
                                        WHERE vendedores_has_vendas.id_vendas = ".$id);

            $response = array(
                "success" => true,
                "vendedores" => $vendedores
            );
            return response(json_encode($response), 200);
        
        }

    }

    public function getProdutosByVenda(Request $request, $id){

        if(!AuthController::autenticate($request->header('token'))){
            $response = array(
                "success" => false,
                "message" => "Usuário não autenticado"
            );
            return response(json_encode($response), 403);
        }
        
        else{

            $produtos = DB::select("  SELECT    produtos.descricao,
                                                produtos.id,
                                                produtos.valor,
                                                produtos_has_vendas.quantidade
                                        FROM produtos
                                        INNER JOIN produtos_has_vendas ON produtos_has_vendas.id_produto = produtos.id
                                        WHERE produtos_has_vendas.id_venda = ".$id);

            $response = array(
                "success" => true,
                "produtos" => $produtos
            );
            return response(json_encode($response), 200);
        
        }
    }

    public function getVeiculosByVenda(Request $request, $id){

        if(!AuthController::autenticate($request->header('token'))){
            $response = array(
                "success" => false,
                "message" => "Usuário não autenticado"
            );
            return response(json_encode($response), 403);
        }
        
        else{

            $veiculos = DB::select("  SELECT    veiculos.placa,
                                                veiculos.id,
                                                modelos.nome as modelo
                                        FROM veiculos
                                        INNER JOIN venda_has_veiculo ON venda_has_veiculo.id_veiculo = veiculos.id
                                        INNER JOIN modelos ON veiculos.id_modelo = modelos.id
                                        WHERE venda_has_veiculo.id_venda = ".$id);

            $response = array(
                "success" => true,
                "veiculos" => $veiculos
            );
            return response(json_encode($response), 200);
        
        }
    }

    public function viewNota(Request $request, $id){

        if(!AuthController::autenticate($request->header('token'))){
            $response = array(
                "success" => false,
                "message" => "Usuário não autenticado"
            );
            return response(json_encode($response), 403);
        }
        
        else{
            if(vendas::where('id', $id)->exists()) {
                
                $vendas =  DB::select('SELECT   vendas.id as id, 
                                                vendas.data as data, 
                                                vendas.complemento as complemento,
                                                vendas.homologado as homologado,
                                                vendas.pago as pago,
                                                clientes.id as idCliente,
                                                clientes.cpf_cnpj as cpfCnpjCliente,
                                                clientes.nome as nomeCliente,
                                                clientes.telefone as telefoneCliente,
                                                clientes.porcentagem_areia as areia,
                                                clientes.porcentagem_concreto as concreto,
                                                sum(produtos_has_vendas.quantidade * produtos.valor) as valorTotal
                                        FROM vendas 
                                        INNER JOIN clientes ON vendas.id_clientes = clientes.id
                                        INNER JOIN produtos_has_vendas ON produtos_has_vendas.id_venda = vendas.id 
                                        INNER JOIN produtos ON produtos_has_vendas.id_produto = produtos.id
                                        WHERE vendas.id = '.$id.' GROUP BY vendas.id')[0];

                $vendedores = DB::select('SELECT    vendedores.id as id, 
                                                    vendedores.nome as nomeVendedor, 
                                                    vendedores.telefone as telefoneVendedor,
                                                    vendedores.email as emailVendedor
                                        FROM vendas 
                                        INNER JOIN vendedores_has_vendas ON vendedores_has_vendas.id_vendas = vendas.id 
                                        INNER JOIN vendedores ON vendedores.id = vendedores_has_vendas.id_vendedores
                                        WHERE vendas.id = '.$id);

                $veiculos = DB::select('SELECT      veiculos.id as id, 
                                                    veiculos.placa as placa, 
                                                    veiculos.tamanho as tamanho,
                                                    veiculos.id_modelo as idModeloVeiculo,
                                                    modelos.nome as modeloVeiculo
                                        FROM vendas 
                                        INNER JOIN venda_has_veiculo ON venda_has_veiculo.id_venda = vendas.id 
                                        INNER JOIN veiculos ON venda_has_veiculo.id_veiculo = veiculos.id 
                                        INNER JOIN modelos ON modelos.id = veiculos.id_modelo
                                        WHERE vendas.id = '.$id);

                                                    
                $produtos = DB::select('SELECT      produtos.id as id, 
                                                    produtos.descricao as descricao, 
                                                    produtos.valor as valor,
                                                    produtos_has_vendas.quantidade as quantidadeProduto,
                                                    medidas.descricao as medidaProduto,
                                                    produtos_has_vendas.quantidade * produtos.valor as valorProduto
                                        FROM vendas 
                                        INNER JOIN produtos_has_vendas ON vendas.id = produtos_has_vendas.id_venda
                                        INNER JOIN produtos ON produtos_has_vendas.id_produto = produtos.id 
                                        INNER JOIN medidas ON medidas.id = produtos.id_medida
                                        WHERE vendas.id = '.$id);

                $data = [
                    'id' => $vendas->id,
                    'data' => $vendas->data,
                    'nomeCliente' => $vendas->nomeCliente,
                    'cpfCnpjCliente' => $vendas->cpfCnpjCliente,
                    'telefoneCliente' => $vendas->telefoneCliente,

                    'vendedores' => $vendedores,
                    'veiculos' => $veiculos,
                    'produtos' => $produtos,

                    'valorTotal' => $vendas->valorTotal
                ];

                $pdf = PDF::loadView('nf', $data);
                return $pdf->download('nf'.$id.'.pdf');

            } else {
                
                $response = array(
                    "success" => false,
                    "message" => "Venda não encontrada"
                );
    
                return response(json_encode($response), 200);

            }
        }

    }
}
