<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\clientesController;
use App\Http\Controllers\enderecosController;
use App\Http\Controllers\medidasController;
use App\Http\Controllers\produtosController;
use App\Http\Controllers\tipos_enderecoController;
use App\Http\Controllers\tipos_usuarioController;
use App\Http\Controllers\usuariosController;
use App\Http\Controllers\veiculosController;
use App\Http\Controllers\vendasController;
use App\Http\Controllers\vendedoresController;
use App\Http\Controllers\modelosController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::post('login', [usuariosController::class,'login']);
Route::get('logout', [usuariosController::class,'logout']);

//Tipos Usuários
Route::post('tiposUsuario', [tipos_usuarioController::class,'createTiposUsuarios']);
Route::get('getTiposUsuarios', [tipos_usuarioController::class,'getAllTiposUsuarios']);
Route::put('tiposUsuario/{id}', [tipos_usuarioController::class,'updateTiposUsuarios']);
Route::delete('tiposUsuario/{id}', [tipos_usuarioController::class,'deleteTiposUsuarios']);

//Usuários
Route::get('getUsuarios', [usuariosController::class,'getAllUsuarios']);
Route::post('insertUsuario', [usuariosController::class,'createUsuarios']);
Route::put('updateUsuario', [usuariosController::class,'updateUsuarios']);
Route::delete('deleteUsuario/{id}', [usuariosController::class,'deleteUsuarios']);

//Clientes
Route::post('insertCliente', [clientesController::class,'createClientes']);
Route::get('getClientes', [clientesController::class,'getAllClientes']);
Route::put('updateCliente', [clientesController::class,'updateClientes']);
Route::delete('deleteCliente/{id}', [clientesController::class,'deleteClientes']);

//Enderecos
Route::post('insertEndereco', [enderecosController::class,'createEnderecos']);
Route::get('getEnderecos', [enderecosController::class,'getAllEnderecos']);
Route::put('updateEndereco', [enderecosController::class,'updateEndereco']);
Route::delete('deleteEndereco/{id}', [enderecosController::class,'deleteEnderecos']);

//Medidas
Route::post('insertMedidas', [medidasController::class,'createMedidas']);
Route::get('getMedidas', [medidasController::class,'getAllMedidas']);
Route::put('updateMedidas', [medidasController::class,'updateMedidas']);
Route::delete('deleteMedidas/{id}', [medidasController::class,'deleteMedidas']);

//Produtos
Route::post('insertProdutos', [produtosController::class,'createProdutos']);
Route::get('getProdutos', [produtosController::class,'getAllProdutos']);
Route::put('updateProdutos', [produtosController::class,'updateProdutos']);
Route::delete('deleteProdutos/{id}', [produtosController::class,'deleteProdutos']);

//Tipos de Endereco
Route::post('tiposEndereco', [tipos_enderecoController::class,'createTiposEnderecos']);
Route::put('tiposEndereco/{id}', [tipos_enderecoController::class,'updateTiposEnderecos']);
Route::delete('tiposEndereco/{id}', [tipos_enderecoController::class,'deleteTiposEnderecos']);
Route::get('getTiposEndereco', [tipos_enderecoController::class,'getAllTiposEnderecos']);

//Veículos
Route::post('insertVeiculos', [veiculosController::class,'createVeiculos']);
Route::get('getVeiculos', [veiculosController::class,'getAllVeiculos']);
Route::put('updateVeiculos', [veiculosController::class,'updateVeiculos']);
Route::delete('deleteVeiculos/{id}', [veiculosController::class,'deleteVeiculos']);

//Vendas
Route::post('insertVendas', [vendasController::class,'createVendas']);
Route::get('getVendas', [vendasController::class,'getAllVendas']);
Route::get('homologarVenda/{id}', [vendasController::class,'homologarVenda']);
Route::get('pagarVenda/{id}', [vendasController::class,'pagarVenda']);
Route::put('updateVendas', [vendasController::class,'updateVendas']);
Route::delete('deleteVendas/{id}', [vendasController::class,'deleteVendas']);
Route::get('getVendedoresByVenda/{id}', [vendasController::class,'getVendedoresByVenda']);
Route::get('getProdutosByVenda/{id}', [vendasController::class,'getProdutosByVenda']);
Route::get('getVeiculosByVenda/{id}', [vendasController::class,'getVeiculosByVenda']);
Route::get('viewNota/{id}', [vendasController::class,'viewNota']);

//Vendedores
Route::post('insertVendedor', [vendedoresController::class,'createVendedores']);
Route::get('getVendedores', [vendedoresController::class,'getAllVendedores']);
Route::put('updateVendedor', [vendedoresController::class,'updateVendedores']);
Route::delete('deleteVendedor/{id}', [vendedoresController::class,'deleteVendedores']);

//Modelos
Route::post('insertModelos', [ModelosController::class,'createModelos']);
Route::get('getModelos', [ModelosController::class,'getAllModelos']);
Route::put('updateModelos', [ModelosController::class,'updateModelos']);
Route::delete('deleteModelos/{id}', [ModelosController::class,'deleteModelos']);