<?php
require("./_sessao.php");
require_once("./layout/cabecalho.php");
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minha Carteira - Consulta de Lançamentos</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./css/estilo.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>    
    <link rel="shortcut icon" href="../../logo_minha_carteira_ICO.ico" type="image/x-icon">
</head>


<body> 
        <h1 class="titulo-2">Consulta de Lançamentos</h1>
        <?php include("./_menu-telas-consultas.php"); ?>
   
    <?php
    $status = filter_input(INPUT_GET, 'status', FILTER_SANITIZE_SPECIAL_CHARS);
    if (isset($status) && ($status == "sucesso")) {
        echo '<div id="sucesso">Lançamento de Despesa cadastrado com sucesso!</div>';
    }
    if (isset($status) && ($status == "excsucesso")) {
        echo '<div id="excsucesso">Lançamento de Despesa Excluído com sucesso!</div>';
    }
    if (isset($status) && ($status == "altsucesso")) {
        echo '<div id="altsucesso">Lançamento de Despesa Alterado com sucesso!</div>';
    }

    if (isset($status) && ($status == "insucesso")) {
        echo '<div id="erroSenha">Não foi possível inserir este Lançamento!</div>';
    }

    $status = filter_input(INPUT_GET, 'status', FILTER_SANITIZE_SPECIAL_CHARS);
    if (isset($status) && ($status == "sucesso-receita")) {
        echo '<div id="sucesso">Lançamento de Receita cadastrado com sucesso!</div>';
    }

    if (isset($status) && ($status == "excsucesso-receita")) {
        echo '<div id="excsucesso">Lançamento de Receita Excluído com sucesso!</div>';
    }
    if (isset($status) && ($status == "altsucesso-receita")) {
        echo '<div id="altsucesso">Lançamento de Receita Alterado com sucesso!</div>';
    }

    if (isset($_GET['status']) && $_GET['status'] === 'altNever') {
        echo '<div id="altNever">Nenhuma alteração foi realizada pelo Usuário.</div>';
    }
    ?>


    <div class="centralizar-tabela-lancamentos">
        <table id="tabela-usuarios">
            <tbody>
                <?php
                echo "<tr>";
                echo '<th colspan="9" style="background-color: #ff3333; font-size: 26px;">DESPESAS</th>';
                echo '</tr>';
                // Obtém o nome do mês atual em inglês, por exemplo, "July"
                $mesAtual = date("F");
                require_once("./view-lancamentosbd.php");

                //Faz a Associação dos nomes em inglês dos meses para o Português
                $mesesTraduzidos = array(
                    "January" => "Janeiro",
                    "February" => "Fevereiro",
                    "March" => "Março",
                    "April" => "Abril",
                    "May" => "Maio",
                    "June" => "Junho",
                    "July" => "Julho",
                    "August" => "Agosto",
                    "September" => "Setembro",
                    "October" => "Outubro",
                    "November" => "Novembro",
                    "December" => "Dezembro"
                );
                $anos = array();
                $meses = array();
                $totalDespesasPorMes = array();
                $totalReceitas = 0;
                $totalDespesas = 0;
                $totalDespesaPorAno = 0;
                $saldo = 0;

                foreach ($dados as $linha) {
                    if (!empty($linha["data_vencimento"])) {
                        $dataRegistro = strtotime($linha["data_vencimento"]);
                        $nomeDoMes = $mesesTraduzidos[date("F", $dataRegistro)];
                        $ano = date("Y", $dataRegistro);

                        if (!isset($anos[$ano])) {
                            $anos[$ano] = array();
                            $totalDespesasPorAno[$ano] = 0;
                            $totalReceitasPorAno[$ano] = 0;
                        }

                        if (!isset($anos[$ano][$nomeDoMes])) {
                            $anos[$ano][$nomeDoMes] = array();
                        }

                        if (!isset($meses[$nomeDoMes])) {
                            $meses[$nomeDoMes] = array();
                            //Para calcular cada mês de forma individual
                            $totalDespesasPorMes[$nomeDoMes] = 0;
                            $totalReceitasPorMes[$nomeDoMes] = 0;
                        }
                        $meses[$nomeDoMes][] = $linha;
                        $anos[$ano][$nomeDoMes][] = $linha;
                        $totalDespesasPorAno[$ano] += floatval($linha["valor"]);

                        // Verifica se a linha é uma despesa ou uma receita para calcular o total de receitas por ano
                        if (!empty($linha["valor"])) {
                            $totalReceitasPorAno[$ano] += floatval($linha["valor"]);
                        }

                        $totalDespesasPorMes[$nomeDoMes] += floatval($linha["valor"]);
                    }
                }
                ?>
               <div class="centralizar-select">
                    <label for="anoSelect">Selecione o Ano:</label>
                    <?php $anoSelecionado = $get['ano']?? 'todos'; ?>
                        <select id="anoSelect" name="anoSelect" class="select-ano" onchange="filtrarPorAno()">
                            <option value="todos" <?= $anoSelecionado === 'todos' ? 'selected' : '' ?>>Todos</option>
                            <?php foreach ($anos as $ano => $mesesDoAno) : ?>
                                <option value="<?= htmlspecialchars($ano) ?>" <?= $anoSelecionado == $ano ? 'selected' : '' ?>> 
                                <?= htmlspecialchars($ano) ?>
                                </option>
                            <?php endforeach; ?>
                            
                        </select>
                </div>

            <?php
                //código para calcular os totais de receitas por mês dentro de cada ano
                $totalDespesasPorMesAno = array();

                foreach ($anos as $ano => $mesesDoAno) {
                    foreach ($mesesDoAno as $mes => $dadosDoMes) {
                        if (!isset($totalDespesasPorMesAno[$mes])) {
                            $totalDespesasPorMesAno[$mes] = array();
                        }
                        if (!isset($totalDespesasPorMesAno[$mes][$ano])) {
                            $totalDespesasPorMesAno[$mes][$ano] = 0;
                        }

                        foreach ($dadosDoMes as $linha) {
                            $totalDespesasPorMesAno[$mes][$ano] += floatval($linha["valor"]);
                        }
                    }
                }
               
                foreach ($anos as $ano => $mesesDoAno) {
                    echo '<tr class="ano-header abrir-fechar ano-' . $ano . '" style="background-color: #CC6666; font-size: 21px; color: black;">';
                    echo '<td colspan="9">';
                    echo '<span class="ano-nome">' . $ano . '</span>';
                    echo '</td>';
                    echo '</tr>';
                
                    echo '<tr class="mes-detalhes ano-' . $ano . '" style="background-color: #ff9999; font-size: 19px; display: none;">';
                    echo '<td colspan="9">';
                    echo '<table>';
                
                    foreach ($mesesDoAno as $nomeDoMes => $registros) {
                        echo '<tr class="mes-header abrir-fechar ano-' . $ano . '" style="background-color: #ff9999;">';
                        echo '<td colspan="9">';
                        echo '<span class="mes-nome">' . $nomeDoMes . '</span>';
                        echo '</td>';
                        echo '</tr>';
                
                        echo '<tr class="mes-detalhes ano-' . $ano . '" style="background-color: #ff9999; display: none;">';
                        echo '<td colspan="9">';
                        echo '<table>';
                        echo '<tr>';?>

                        <th width="400">
                            Descrição
                            <input type="text" id="filtroDescricao" class="filtro-coluna" data-coluna="0">
                        </th>
                        <th width="150">
                            Data Pagamento
                            <input type="text" id="filtroDescricao" class="filtro-coluna" data-coluna="2">
                        </th>
                        <th width="150">
                            Data Vencimento
                            <input type="text" id="filtroCodigo" class="filtro-coluna" data-coluna="3">
                        </th>
                        <th width="150">
                            Valor
                            <input type="text" id="filtroCodigo" class="filtro-coluna" data-coluna="4">
                        </th>
                        <th width="150">
                            Categoria
                            <input type="text" id="filtroCodigo" class="filtro-coluna" data-coluna="5">
                        </th>
                        <th width="150">
                            Conta
                            <input type="text" id="filtroCodigo" class="filtro-coluna" data-coluna="6">
                        </th>
                        <th width="90" class="status">Situação
                            <select class="filtro-coluna" data-coluna="7">
                                <option value="Pago">Pago</option>
                                <option value="Em Aberto">Em Aberto</option>
                            </select>
                        </th>
                        <th width="100" class="status">
                            Comprovante
                        </th>
                        <th class="status" width="80"></th>
                        <th width="80"></th>
                        </tr>

                        <?php

                        //Loop para exibir despesas
                        foreach ($registros as $registro) {
                            echo "<tr class='usuario' data-descricao='" .
                                strtolower($registro["descricao"]) . "'>";
                            echo "<td>" . $registro["descricao"] . "</td>";
                            echo "<td>" . $registro["data_pagamento"] . "</td>";
                            echo "<td>" . $registro["data_vencimento"] . "</td>";
                            echo "<td>R$" . $registro["valor"] . "</td>";
                            echo "<td>" . $registro["categoria_descricao"] . "</td>";
                            echo "<td>" . $registro["carteira_descricao"] . "</td>";
                            echo "<td class='status'>" . $registro["situacao"] . "</td>";

                            //Exiba o comprovante (imagem/arquivo) na coluna Comprovante
                            $comprovantePath = "";
                            if (!empty($registro['comprovante'])) {
                                $comprovantePath = $registro['comprovante'];
                            }
                        ?>

                            <!--Exiba o botão "visualizar arquivo" na coluna Comprovante-->
                            <td class="comprovante">
                                <button class="visualizar-arquivo-btn" data-comprovante="<?php
                                echo $comprovantePath; ?>">Visualizar Arquivo</button>
                            <?php

                            //$totalDespesas = 0;
                            //Adiciona o valor atual a soma total
                            $totalDespesas += floatval($registro["valor"]);
                            if (!empty($comprovantePath)) {
                                if (in_array(pathinfo($comprovantePath, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif', 'bmp'])) {
                                    echo '<img src="' . $comprovantePath . '" type="application/pdf" width="100" height="100">';
                                } elseif (pathinfo($comprovantePath, PATHINFO_EXTENSION) === 'pdf') {
                                    // É um arquivo PDF, use o elemento <embed> para exibi-lo
                                    echo '<embed src="' . $comprovantePath . '" type="application/pdf" width="100" height="100">';
                                } else {
                                    // Tipo de arquivo não suportado, exiba uma mensagem de erro
                                    echo 'Tipo de arquivo não suportado.';
                                }
                            }
                            $situacao = $registro["situacao"];

                            // Verificar se categoria está vinculada a algum registro de lançamentos com situação "Pago"
                            $verificarVinculo = $conexao->prepare("SELECT COUNT(*) FROM lancamentos_despesas WHERE codigo = :codigo AND situacao = 'Pago'");
                            $verificarVinculo->bindParam(':codigo', $codigoCategoria, PDO::PARAM_INT);
                            $verificarVinculo->execute();
                            $vinculado = $verificarVinculo->fetchColumn();

                            echo '</td>'; // Feche a tag <td> aqui

                            // Abra outra tag <td> para o botão de edição
                            echo '<td align="center">
                                        <a href="alt-lancamento.php?codigo=' . $registro['codigo'] . '">
                                            <img src="../../btn_editar.png" alt="Atualizar" width="30">
                                        </a>
                                      </td>';

                            // Abra uma nova tag <td> para o botão de excluir
                            echo '<td align="center">';
                            // Verifique se a situação selecionada no filtro é 'Pago'
                            if ($situacao === 'Pago') {
                                // Se for 'Pago', desabilite o botão de excluir
                                echo '<button type="button" class="disabled-button-lancamentos" disabled>
                                            <img src="../../btn_excluir.png" alt="Excluir" width="30">
                                          </button>';
                            } else {
                                // Se não for 'Pago', exiba o botão de excluir normalmente
                                echo '<a href="exc-lancamento.php?codigo=' . $registro['codigo'] . '">
                                <img src="../../btn_excluir.png" alt="Excluir" width="30">
                            </a>';

                            }
                            echo '</td>'; // Feche a tag <td> para o botão de excluir

                        
                            echo "</tr>";
                        }
                        // Após o loop dos registros, exiba a soma dos valores
                        echo '<tr class="total-valor"><td colspan="11">Despesas de ' . $nomeDoMes . '' . '
                                    R$' . number_format($totalDespesasPorMesAno[$nomeDoMes][$ano], 2, ',', '.') . '</td>
                                    </tr>';

                            // Calcula o saldo total do mes (Receitas - Despesas)
                            $saldo = $totalReceitas - $totalDespesas;


                ?>
            </tbody>


    <?php
                        echo '</tr>';
                        echo '</table>';
                        echo '</td>';
                        echo '</tr>';
                    }


                    // Exibe o total de despesas para o ano atual
                    echo '<tr><td colspan="9" style="font-size: 20px; font-weight: bold; background-color: #FFDDDD; /* Cor de fundo */
                        border-color: #FFCCCC; /* Cor da borda */
                        color: #; /* Cor do texto */
                        ;">Total de Despesas em ' . $ano . ': R$' . number_format($totalDespesasPorAno[$ano], 2, ',', '.') . '</td></tr>';


                    echo '</table>';
                    echo '</td>';
                    echo '</tr>';
                }


    ?>

    </div>
    <?php

    //Calcula o valor do Mês inteiro
    //Calcula o total geral de despesas
    $totalGeralDespesas = array_sum($totalDespesasPorMes);
    echo '<tr class="total-valor"><td style="background-color: #ff3333; font-size: 21px;">Total de Despesas: R$' . number_format($totalDespesas, 2, ',', '.') . '</td></tr>';

    ?>
    <tr>
        <td></td>

    </tr>
    <tr>
        <td></td>
    </tr>
    

    <div class="centralizar-tabela-lancamentos">
    <table id="tabela-usuarios">
        <tbody>
            <?php
            echo "<tr>";
            echo '<th colspan="8" style="background-color: #3CB371; font-size: 26px;">RECEITAS</th>';
            echo '</tr>';
                //Obtém o nome do mês atual em inglês, por exemplo, "July"   
                $mesAtual = date("F");
                require_once("./view-lancamentos-receitasbd.php");

                //Associação dos nomes em inglês dos meses para o Português
                $mesesTraduzidos = array(
                    "January" => "Janeiro",
                    "February" => "Fevereiro",
                    "March" => "Março",
                    "April" => "Abril",
                    "May" => "Maio",
                    "June" => "Junho",
                    "July" => "Julho",
                    "August" => "Agosto",
                    "September" => "Setembro",
                    "October" => "Outubro",
                    "November" => "Novembro",
                    "December" => "Dezembro"
                );
                $anos = array();
                $meses = array();
                $totalReceitasPorMes = array();
                $totalReceitas = 0;
                $saldo = 0;

                foreach ($dados as $linha) {
                    if (!empty($linha["data_credito"])) {
                        $dataRegistro = strtotime($linha["data_credito"]);
                        $nomeDoMes = $mesesTraduzidos[date("F", $dataRegistro)];
                        $ano = date("Y", $dataRegistro);

                        // Inicialização dos totais por ano
                        if (!isset($anos[$ano])) {
                            $anos[$ano] = array();
                            $totalReceitasPorAno[$ano] = 0;
                        }

                        // Inicialização dos totais por mês
                        if (!isset($anos[$ano][$nomeDoMes])) {
                            $anos[$ano][$nomeDoMes] = array();
                        }

                        if (!isset($meses[$nomeDoMes])) {
                            $meses[$nomeDoMes] = array();
                            // Para calcular cada mês de forma individual
                            $totalReceitasPorMes[$nomeDoMes] = 0;
                        }

                        $meses[$nomeDoMes][] = $linha;
                        $anos[$ano][$nomeDoMes][] = $linha;
                        $totalReceitasPorAno[$ano] += floatval($linha["valor"]);
                        // Armazena na variável os valores para somar no final
                        $totalReceitasPorMes[$nomeDoMes] += floatval($linha["valor"]);
                    }
                }
                ?>
                <?php
                //código para calcular os totais de receitas por mês dentro de cada ano
                $totalReceitasPorMesAno = array();

                foreach ($anos as $ano => $mesesDoAno) {
                    foreach ($mesesDoAno as $mes => $dadosDoMes) {
                        if (!isset($totalReceitasPorMesAno[$mes])) {
                            $totalReceitasPorMesAno[$mes] = array();
                        }
                        if (!isset($totalReceitasPorMesAno[$mes][$ano])) {
                            $totalReceitasPorMesAno[$mes][$ano] = 0;
                        }

                        foreach ($dadosDoMes as $linha) {
                            $totalReceitasPorMesAno[$mes][$ano] += floatval($linha["valor"]);
                        }
                    }
                }

                foreach ($anos as $ano => $mesesDoAno) {
                    echo '<tr class="ano-header abrir-fechar ano-' . $ano . '"style="background-color: #77a174; font-size: 21px; color: black;">';
                    echo '<td colspan="9">';
                    echo '<span class="ano-nome">' . $ano . '</span>';
                    echo '</td>';
                    echo '</tr>';

                    echo '<tr class="mes-detalhes" ano-' . $ano . '" style="background-color: #3CB371; font-size: 19px; display: none;">';
                    echo '<td colspan="9">';
                    echo '<table>';

                    foreach ($mesesDoAno as $nomeDoMes => $registros) {
                        echo '<tr class="mes-header abrir-fechar ano-' . $ano . '" style="background-color: #90EE90;">';
                        echo '<td colspan="9">';
                        echo '<span class="mes-nome">' . $nomeDoMes . '</span>';
                        echo '</td>';
                        echo '</tr>';

                        echo '<tr class="mes-detalhes ano-' . $ano . '" style="background-color: #ADD8E6; display: none;">';
                        echo '<td colspan="9">';
                        echo '<table>';
                        echo '<tr>';?>

                        <th width="400">
                            Descrição
                            <input type="text" id="filtroDescricao" class="filtro-coluna" data-coluna="0">                           
                        </th>
                        <th width="150">
                            Data do Crédito
                            <input type="text" id="filtroDescricao" class="filtro-coluna" data-coluna="2">
                        </th>

                        <th width="150">
                            Valor
                            <input type="text" id="filtroCodigo" class="filtro-coluna" data-coluna="4">
                        </th>
                        <th width="150">
                            Categoria
                            <input type="text" id="filtroCodigo" class="filtro-coluna" data-coluna="5">
                        </th>
                        <th width="150">
                            Carteira
                            <input type="text" id="filtroCodigo" class="filtro-coluna" data-coluna="6">
                        </th>
                        <th width="90" class="status">Situação
                            <select class="filtro-coluna" data-coluna="7">
                                <option value="Em Aberto ">Em Aberto</option>
                                <option value="Recebido">Recebido</option>
                            </select>
                        </th>

                        <th width="90" class="status">Editar
                        </th>
                        <th width="90" class="status">Excluir
                        </th>
                        <?php
                        echo '</tr>';

                        // Loop para exibir receitas
                        foreach ($registros as $registro) {

                            // Detalhes do lançamento de Despesas
                            echo "<tr class='usuario' data-descricao='" . strtolower($registro["descricao"]) . "'>";
                            echo "<td>" . $registro["descricao"] . "</td>";
                            echo "<td>" . $registro["data_credito"] . "</td>";
                            echo "<td>R$" . $registro["valor"] . "</td>";
                            echo "<td>" . $registro["categoria_descricao"] . "</td>";
                            echo "<td>" . $registro["carteira_descricao"] . "</td>";
                            echo "<td class='status2'>" . $registro["situacao"] . "</td>";

                            // Exiba o comprovante na coluna Comprovante
                            $comprovantePath = ""; // Inicializa a variável com um valor vazio

                            if (!empty($registro['comprovante'])) {
                                $comprovantePath = $registro['comprovante'];
                            }

                            // Exiba o comprovante na coluna Comprovante
                            $comprovantePath = !empty($registro['comprovante']) ? $registro['comprovante'] : '';

                            // Adicione o valor atual à soma total
                            $totalReceitas += floatval($registro["valor"]);
                            if (!empty($comprovantePath)) {
                                if (in_array(pathinfo($comprovantePath, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif', 'bmp'])) {
                                    echo '<img src="' . $comprovantePath . '" type="application/pdf" width="100" height="100">';
                                } elseif (pathinfo($comprovantePath, PATHINFO_EXTENSION) === 'pdf') {
                                    // É um arquivo PDF, use o elemento <embed> para exibi-lo
                                    echo '<embed src="' . $comprovantePath . '" type="application/pdf" width="100" height="100">';
                                } else {
                                    // Tipo de arquivo não suportado, exiba uma mensagem de erro
                                    echo 'Tipo de arquivo não suportado.';
                                }
                            }

                            $situacao = $registro["situacao"];

                            // Verificar se categoria está vinculada a algum registro de lançamentos com situação "Pago"
                            $verificarVinculo = $conexao->prepare("SELECT COUNT(*) FROM lancamentos_receitas WHERE codigo = :codigo AND situacao = 'Recebido'");
                            $verificarVinculo->bindParam('codigo', $codigoCategoria, PDO::PARAM_INT);
                            $verificarVinculo->execute();
                            $vinculado = $verificarVinculo->fetchColumn();

                            echo '</td>'; // Feche a tag <td> aqui

                            echo '</td>';
                        ?>
                            <td align="center">
                                <a href="alt-lancamento-receita.php?codigo=<?= $registro['codigo']; ?>">
                                    <img src="../../btn_editar.png" alt="Atualizar" width="30">
                                </a>
                            </td>
                        <?php
                            // Abra uma nova tag <td> para o botão de excluir
                            echo '<td align="center">';
                            // Verifique se a situação selecionada no filtro é 'Pago'
                            if ($situacao === 'Recebido') {
                                // Se for 'Pago', desabilite o botão de excluir
                                echo '<button type="button" class="disabled-button-lancamentos-receitas" disabled>
                                            <img src="../../btn_excluir.png" alt="Excluir" width="30">
                                          </button>';
                            } else {
                                // Se não for 'Pago', exiba o botão de excluir normalmente
                                echo '<a href="exc-lancamento-receita.php?codigo=' . $registro['codigo'] . '">
                                            <img src="../../btn_excluir.png" alt="Excluir" width="30">
                                          </a>';
                            }
                            echo '</td>'; // Feche a tag <td> para o botão de excluir
                            echo "</tr>";
                            echo "</tr>";
                        }
                        // Após o loop dos registros, exiba a soma dos valores
                        echo '<tr class="total-valor"><td colspan="11">Receitas de ' . $nomeDoMes . '' . '
                                    R$' . number_format($totalReceitasPorMesAno[$nomeDoMes][$ano], 2, ',', '.') . '
                                    </tr>';

                        // Calcula o saldo total das receitas do mes (Receitas - Despesas)
                        $saldo = $totalReceitas - $totalDespesas;

                        ?>


            </tbody>
    <?php
                        echo '</tr>';
                        echo '</table>';
                        echo '</td>';
                        echo '</tr>';
                    }
                    // Exibe o total de receitas para o ano atual
                    echo '<tr><td colspan="9" style="font-size: 20px; font-weight: bold; background-color: #f2f2f2;">Total de Receitas em ' . $ano . ': R$' . number_format($totalReceitasPorAno[$ano], 2, ',', '.') . '</td></tr>';
                    echo '</table>';
                    echo '</td>';
                    echo '</tr>';
                }
    ?>

    </div>
    <?php
    // Exibe o total de receitas
    echo '<tr class="total-valor"><td style="background-color: #3CB371; font-size: 21px;">Total de Receitas: R$' . number_format($totalReceitas, 2, ',', '.') . '</td></tr>';


    ?>


    <?php
    // Exibe o saldo atual (despesas - receitas)
    echo '<tr class="total-valor"><td style= "font-size: 26px;">Saldo: R$' . number_format($saldo, 2, ',', '.') . '</td></tr>';

    ?>

    </table>
    <div class="containercad" style="margin-top: 35px;">
        <form>
            <div class="voltar" style="background-color: dimgray">
                <input type="submit" style="background-color:dimgray; border: 1px solid dimgray; font-size: 16px" value="Fechar" formaction="../../tela-inicial.php">
            </div>
        </form>
        <div class="espaco-m"></div>
        <a href="./cad-lancamento.php"><input type="submit" style="background-color: green; border: 1px solid green; font-size: 16px" value="Adicionar"></a>
    </div>


    <script>
        //função para controlar o tempo e forma que aparece a mensagem de inclusão com sucesso(verde)
        window.addEventListener('DOMContentLoaded', function() {
            var mensagem = document.getElementById('sucesso');
            sucesso.style.display = 'block';

            setTimeout(function() {
                mensagem.style.opacity = 0;
                setTimeout(function() {
                    mensagem.style.display = 'none';
                }, 900);
            }, 5000);
        });

        //função para controlar o tempo e forma que aparece a mensagens de insucesso
        window.addEventListener('DOMContentLoaded', function() {
            var mensagem = document.getElementById('excsucesso');
            excsucesso.style.display = 'block';

            setTimeout(function() {
                mensagem.style.opacity = 0;
                setTimeout(function() {
                    mensagem.style.display = 'none';
                }, 900);
            }, 4000);
        });

        //função para controlar o tempo e forma que aparece a mensagens de insucesso
        window.addEventListener('DOMContentLoaded', function() {
            var mensagem = document.getElementById('altsucesso');
            altsucesso.style.display = 'block';

            setTimeout(function() {
                mensagem.style.opacity = 0;
                setTimeout(function() {
                    mensagem.style.display = 'none';
                }, 900);
            }, 4000);
        });

        //função para controlar o tempo e forma que aparece a mensagens de sem alteração
        window.addEventListener('DOMContentLoaded', function() {
            var mensagemAltSucesso = document.getElementById('altNever');
            if (mensagemAltSucesso) {
                mensagemAltSucesso.style.display = 'block';

                setTimeout(function() {
                    mensagemAltSucesso.style.opacity = 0;
                    setTimeout(function() {
                        mensagemAltSucesso.style.display = 'none';
                    }, 800);
                }, 4000);
            }
        });

        //função para controlar o tempo e forma que aparece a mensagen de alteração não realizada
        window.addEventListener('DOMContentLoaded', function() {
            var mensagemAltInsucesso = document.getElementById('altinsucesso');
            if (mensagemAltInsucesso) {
                mensagemAltInsucesso.style.display = 'block';

                setTimeout(function() {
                    mensagemAltInsucesso.style.opacity = 0;
                    setTimeout(function() {
                        mensagemAltInsucesso.style.display = 'none';
                    }, 900);
                }, 5000);
            }
        });


        //função para controlar a abertura do colapse dos Meses
        $(document).ready(function() {
            $('.mes-header').click(function() {
                var mesHeader = $(this);
                var mesDetalhes = mesHeader.next('.mes-detalhes');


                mesDetalhes.toggle();
                if (mesDetalhes.is(':visible')) {
                    mesHeader.find('.btn-collapse').text('Colapsar');
                } else {
                    mesHeader.find('.btn-collapse').text('Expandir');
                }
            });
        });

        //função para controlar a abertura colapse dos Anos
        $(document).ready(function() {
            $('.ano-header').click(function() {
                var mesHeader = $(this);
                var mesDetalhes = mesHeader.next('.mes-detalhes');

                mesDetalhes.toggle();
                if (mesDetalhes.is(':visible')) {
                    mesHeader.find('.btn-collapse').text('Colapsar');
                } else {
                    mesHeader.find('.btn-collapse').text('Expandir');
                }
            });
        });

        //Botão "Visualizar Arquivo"
        const visualizarArquivoButtons = document.querySelectorAll('.visualizar-arquivo-btn');


        visualizarArquivoButtons.forEach(button => {
            button.addEventListener('click', () => {
                const comprovantePath = button.getAttribute('data-comprovante');

                if (comprovantePath) {
                    // Abra o comprovante em um pop-up (substitua 'popup.html' pelo caminho real do arquivo)
                    window.open(comprovantePath, 'Comprovante', 'width=800,height=600');
                } else {
                    alert('Nenhum comprovante disponível para visualização.');
                }
            });
        });


        //Filtro Principal dos dados 
        document.addEventListener('DOMContentLoaded', function() {
            var filtrosColuna = document.querySelectorAll('.filtro-coluna');
            var lancamentos = document.querySelectorAll('#tabela-usuarios tbody tr.usuario');

            filtrosColuna.forEach(function(filtro) {
                filtro.addEventListener('input', function() {
                    var colunaIndex = this.getAttribute('data-coluna');
                    var valorFiltro = this.value.toLowerCase();

                    lancamentos.forEach(function(lancamento) {

                        var colunaValor = lancamento.querySelector('td:nth-child(' + (parseInt(colunaIndex) + 1) +

                            ')').textContent.toLowerCase();
                        if (colunaValor.includes(valorFiltro)) {
                            lancamento.style.display = 'table-row';
                        } else {
                            lancamento.style.display = 'none';
                        }
                    });
                });
            });
        });

        //Filtro dos dados das colunas na visualização
        var filtroPago = document.querySelector('.filtro-coluna[data-coluna="7"]');
        filtroPago.addEventListener('change', function() {
            var valorFiltro = this.value;

            lancamentos.forEach(function(lancamento) {
                var statusPago = lancamento.querySelector('.status2').textContent;

                if (valorFiltro === '' || statusPago === valorFiltro) {
                    lancamento.style.display = 'table-row';
                } else {
                    lancamento.style.display = 'none';
                }
            });

            //Recalcular a soma
            recalcularSoma();
        });

        //Botão limpar
        var botoesLimpar = document.querySelectorAll('.botao-limpar');
        botoesLimpar.forEach(function(botao) {
            botao.addEventListener('click', function() {

                //Redefinir os valores dos campos de filtro
                var filtros = document.querySelectorAll('.filtro-coluna');
                filtros.forEach(function(filtro) {
                    filtro.value = '';
                });

                //Mostrar todos os registros
                lancamentos.forEach(function(lancamento) {
                    lancamento.style.display = 'table-row';
                });


                //Recalcular a soma
                recalcularSoma();
            });
        });

        function obterTotal() {
            $saldo = totalDespesas - $totalReceitas;
            return $saldo;
        }

        //Função para calcular valor da soma
        document.addEventListener('DOMContentLoaded', function() {
            recalcularSoma();

            function recalcularSoma() {
                var tabela = document.getElementById("tabela-usuarios");
                var valores = tabela.getElementsByClassName("valor");
                var soma = 0;
                for (var i = 0; i < valores.length; i++) {
                    soma += parseFloat(valores[i].textContent);
                }
                document.getElementById("resultado").textContent = "Soma: R$" + soma.toFixed(2);
            }
        });

        //Função para alterar a cor de fundo do campo "Situação" - verde para "pago" e vermelho para "em aberto"
        document.addEventListener('DOMContentLoaded', function() {
            var statusFields = document.getElementsByClassName("status");

            for (var i = 0; i < statusFields.length; i++) {
                var fieldValue = statusFields[i].textContent.toLowerCase();

                if (fieldValue === "pago") {
                    statusFields[i].classList.add("verde");
                } else if (fieldValue === "em aberto") {
                    statusFields[i].classList.add("vermelho");
                }
            }

            //Função para habilitar colapse
            var coll = document.getElementsByClassName("collapsible");

            for (var i = 0; i < coll.length; i++) {
                coll[i].addEventListener("click", function() {
                    this.classList.toggle("active");
                    var content = this.nextElementSibling;
                    if (content.style.display === "block") {
                        content.style.display = "none";
                    } else {
                        content.style.display = "block";
                    }
                });
            }
        });

        //Verifica o status das Receitas na coluna Situação e pinta o fundo do campo 
        //de verde se esta "Recebido" e de vermelho se a situação está como "em aberto"
        var status2Fields = document.getElementsByClassName("status2");

        for (var i = 0; i < status2Fields.length; i++) {
            var fieldValue = status2Fields[i].textContent.toLowerCase();
            if (fieldValue === "recebido") {
                status2Fields[i].classList.add("verde");
            } else if (fieldValue === "em aberto") {
                status2Fields[i].classList.add("vermelho");
            }
        }

        //Função para calcular a soma na tabela de visualização
        function calcularSoma() {
            var tabela = document.getElementById("tabela-usuarios");
            var valores = tabela.getElementsByClassName("valor");
            var soma = 0;

            for (var i = 0; i < valores.length; i++) {
                soma += parseFloat(valores[i].textContent);
            }

            document.getElementById("resultado").textContent = "Soma: R$" + soma.toFixed(2);
            $soma = soma;
        }

        //Já esta Função é usada para recalcular automaticamente
        function recalcularSoma() {
            var filtro = document.getElementById("filtro").value;
            var linhas = document.getElementsByClassName("valor");
            var soma = 0;

            for (var i = 0; i < linhas.length; i++) {
                var linha = linhas[i];
                var descricao = linha.getElementsByClassName("data_vencimento")[0].textContent;
                var valor = parseFloat(linha.getElementsByClassName("valor")[0].textContent);

                if (descricao.includes(filtro) || filtro === "") {
                    soma += valor;
                    linha.style.display = "table-row";
                } else {
                    linha.style.display = "none";
                }
            }

            document.getElementById("resultado").textContent = "Soma: R$" + soma.toFixed(2);
            var linhas = document.getElementsByClassName("valor");
            var soma = 0;

            for (var i = 0; i < linhas.length; i++) {
                var linha = linhas[i];
                var valor = parseFloat(linha.getElementsByClassName("valor")[0].textContent);
                soma += valor;
            }
            document.getElementById("resultado").textContent = "Soma: R$" + soma.toFixed(2);
        }
        document.getElementById("filtro").addEventListener("input", recalcularSoma);


        // para manipular o botão "Parcelar"
        // Selecione todos os checkboxes monitorados
        const checkboxes = document.querySelectorAll('.select-monitorado');

        // Selecione o botão "PARCELAR"
        const botaoParcelar = document.getElementById('botao-selecionar');

        // Adicione um evento de mudança a cada checkbox
        checkboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                console.log('Checkbox mudou'); // Adicionando o log para testar

                // Verifique se pelo menos um checkbox está selecionado
                const algumSelecionado = [...checkboxes].some(function(checkbox) {
                    return checkbox.checked;
                });

                // Se pelo menos um checkbox estiver selecionado, mostre o botão "PARCELAR". Caso contrário, oculte-o.
                if (algumSelecionado) {
                    botaoParcelar.style.display = 'block';
                } else {
                    botaoParcelar.style.display = 'none';
                }
            });
        });

        //filtrar os dados de despesas por ano na tabela
        function filtrarPorAno() {
    var anoSelecionado = document.getElementById("anoSelect").value;
    var linhasAno = document.querySelectorAll('tr.ano-header, tr.mes-header, tr.mes-detalhes');

    linhasAno.forEach(function(linha) {
        linha.style.display = 'none'; // Esconde todas as linhas
    });

    if (anoSelecionado === 'todos') {
        document.querySelectorAll('tr.ano-header').forEach(function(linha) {
            linha.style.display = ''; // Mostra os headers dos anos
        });
        document.querySelectorAll('tr.mes-header').forEach(function(linha) {
            linha.style.display = ''; // Mostra os headers dos meses
        });
    } else {
        var linhasSelecionadas = document.querySelectorAll('.ano-' + anoSelecionado);
        linhasSelecionadas.forEach(function(linha) {
            linha.style.display = ''; // Mostra as linhas do ano selecionado
        });
    }
}

document.querySelectorAll('.mes-header').forEach(function(header) {
    header.addEventListener('click', function() {
        var ano = this.classList[1].split('-')[1];
        var mesNome = this.querySelector('.mes-nome').textContent.trim();
        var mesDetalhes = document.querySelector('.mes-' + ano + '-' + mesNome);

        if (mesDetalhes.style.display === 'none') {
            mesDetalhes.style.display = '';
        } else {
            mesDetalhes.style.display = 'none';
        }
    });
});

// Chamando a função para garantir que o estado inicial seja "fechado"
filtrarPorAno();
    </script>
    <br><br><br><br><br><br><br><br><br><br>
    <div class="footer">
        <!-- <?php include("./_footer.php"); ?>  -->
    </div>
</body>

</html>