
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Venda</title>
    <style>
        h2{
            margin: 0px;
        }
        h3{
            margin: 0px;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            display: flex;
            align-items: center;
            flex-direction: column;
        }
        .borda {
            border-radius: 25px;
            border: solid 5px black;
            padding: 0px;
            width: 700px;
            height: 1000px;
        }
        .header{
            padding: 20px;
            border-bottom: solid;
            border-color: black;
            display: block;
        }
        .footer {
            bottom: 0px;
        }
        .content {
            display: inline-block
        }
        .nameCell{
            display: block
        }
        .row{
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        .headerTable{
            text-align: right;
        }
    </style>
</head>
<body>
	<div class="borda">
        <div class="header">
            <table>
                <tr class="headerTable">
                    <td style="width: 50%; border: none;"><h2>Andramix</h2></td>
                    <td style="width: 50%; border: none;" class="headerTable"><h2>Data de emissão</h2></td>
                </tr>
                <tr class="headerTable">
                    <td style="width: 50%; border: none; "><h2>Nota Fiscal nº {{$id}}</h2></td>
                    <td style="width: 50%; border: none; " class="headerTable"><h2>{{$data}}</h2></td>
                </tr>
            </table>  
        </div>

        <div class="content">
            <div class="row">
                <h3>Cliente</h3>
                <table>
                    <tr>
                        <td rowspan="2" colspan="2" style="width: 30%;">
                            <div class="nameCell">Nome:</div>
                            <div class="nameCell"><span>{{$nomeCliente}}</span></div>
                        </td>
                        <td style="width: 70%;">CPF/CNPJ: {{$cpfCnpjCliente}}</td>
                    </tr>
                        
                    <tr>
                        <td>Telefone: {{$telefoneCliente}}</td>
                    </tr>
                </table>
            </div>

            <div class="row">
                <h3>Vendedores</h3>
                @if (count($vendedores) === 0)
                    <table>
                        <tr>
                            <td><i>Sem vendedores informados</i></td>
                        </tr>
                    </table>
                @else
                    @foreach ($vendedores as $v)
                        <table>
                            <tr>
                                <td rowspan="2" colspan="2" style="width: 30%;">
                                    <div class="nameCell">Nome:</div>
                                    <div class="nameCell">{{$v->nomeVendedor}}</div>
                                </td>
                                <td style="width: 70%;">
                                    Telefone: {{$v->telefoneVendedor}}
                                </td>
                            </tr>
                            <tr>
                                <td>Email: {{$v->emailVendedor}}</td>
                            </tr>
                        </table>
                    @endforeach
                @endif
            </div>

            <div class="row">
                <h3>Veículos</h3>
                @if (count($veiculos) === 0)
                    <table>
                        <tr>
                            <td><i>Sem veículos informados</i></td>
                        </tr>
                    </table>
                @else
                    @foreach ($veiculos as $vc)
                        <table>
                            <tr>
                                <td style="width: 50%;">
                                    <div class="nameCell">Placa:</div>
                                    <div class="nameCell">{{$vc->placa}}</div>
                                </td>
                                <td style="width: 50%;">
                                    <div class="nameCell">Modelo:</div>
                                    <div class="nameCell">{{$vc->modeloVeiculo}}</div>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                @endif
            </div>
            
            <div class="row">
                <h3>Lista de produtos</h3>

                <table>
                    <thead>
                        <tr style="text-align: left;">
                            <th>Código do Produto</th>
                            <th>Descrição do Produto</th>
                            <th>Unidade de Medida</th>
                            <th>Valor Unitário</th>
                            <th>Quantidade</th>
                            <th>Valor Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($produtos) === 0)
                            <tr colspan="6">
                                <i>Sem produtos informados</i>
                            </tr>
                        @else
                            @foreach ($produtos as $p)
                                <tr>
                                    <td>{{$p->id}}</td>
                                    <td>{{$p->descricao}}</td>
                                    <td>{{$p->medidaProduto}}</td>
                                    <td>R$ {{$p->valor}}</td>
                                    <td>{{$p->quantidadeProduto}}</td>
                                    <td>R$ {{$p->valorProduto}}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="5">Valor Total</td>
                                <td>R$ {{$valorTotal}}</td>
                            </tr>
                        @endif                    
                    </tbody>
                </table>
            </div>
            
        </div>
	</div>
</body>
</html>