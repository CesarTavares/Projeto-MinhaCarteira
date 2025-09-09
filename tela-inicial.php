<?php
require("./app/login/_sessao.php");
require_once("./app/login/funcoes_gerais.php");
require_once("./app/login/_conexao/conexao.php");
require_once("./app/login/ver_saldo.php");
require_once("./app/login/layout/cabecalho.php");
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minha Carteira - Tela Inicial</title>
    <link rel="stylesheet" href="./app/login/css/estilo.css">
    <link rel="shortcut icon" href="logo_minha_carteira_ICO.ico" type="image/x-icon">

</head>

<body>    
    <?php include("./app/login/_menu-pagina-inicial.php"); ?>
   

    <!-- <div class="centralizar-select">
        <label for="anoSelect">Selecione o Ano:</label>
        <select id="anoSelect" onchange="filtrarPorAno()">
            <option value="todos">Todos</option>
            <?php
            require_once("../minhacarteira/app/login/_conexao/conexao.php");

            // Consulta SQL para obter os anos de lançamentos, excluindo o ano atual
            $sql = "SELECT DISTINCT ano
        FROM (
            SELECT YEAR(data_pagamento) AS ano
            FROM lancamentos_despesas
            WHERE YEAR(data_pagamento) != YEAR(CURRENT_DATE())
            UNION
            SELECT YEAR(data_credito) AS ano
            FROM lancamentos_receitas
            WHERE YEAR(data_credito) != YEAR(CURRENT_DATE())
        ) AS anos_lancamentos
        ORDER BY ano DESC";

            try {
                $stmt = $conexao->prepare($sql);
                $stmt->execute();
                $anos_lancamentos = $stmt->fetchAll(PDO::FETCH_COLUMN);

                // Itera sobre os anos para criar as opções do select
                foreach ($anos_lancamentos as $ano) {
                    echo '<option value="' . $ano . '">' . $ano . '</option>';
                }
            } catch (PDOException $e) {
                echo '<option value="">Erro ao carregar anos</option>';
            }
            ?>
        </select>
    </div> -->
    
    <div class="centralizar-r">
        <p>
            <?php if (isset($_SESSION['nome'])): ?> 

                <?php $saldoTelaPrincipal = getSaldo($_SESSION['codigo'], $conexao) ?>
                <p class='saldo-principal'>
                    Olá, <?= $_SESSION['nome'] ?><br>
                    Seu Saldo atual é: <?= formatarMoeda($saldoTelaPrincipal) ?></p>
            <?php endif; ?>
        </p>
        <?php
        $status = filter_input(INPUT_GET, 'status', FILTER_SANITIZE_SPECIAL_CHARS);
        if (isset($status) && ($status == "sucesso")) {
            echo '<div id="sucesso" style="margin-top: 5px;">Login Realizado com sucesso!</div>';
        }

        $status = filter_input(INPUT_GET, 'status', FILTER_SANITIZE_SPECIAL_CHARS);
        if (isset($status) && ($status == "sucesso-cad")) {
            echo '<div id="sucesso" style="margin-top: 5px;">Usuário Cadastrado com sucesso!</div>';
        }
        ?>
    </div>   

    <?php
    $conn = mysqli_connect("localhost", "root", "1234", "minhacarteira");
    ?>

    <?php
    $idUsuario = $_SESSION['codigo'];
    $sqlContasVencidas = "SELECT descricao, data_vencimento, valor 
                      FROM lancamentos_despesas 
                      WHERE data_vencimento < CURDATE() 
                      AND situacao != 'Pago'
                      AND codigo_usuario = $idUsuario
                      ORDER BY data_vencimento ASC 
                      LIMIT 5";

    $resultContasVencidas = mysqli_query($conn, $sqlContasVencidas);

    //Consulta SQL para despesas a vencer
    $sqlContasAVencer = "SELECT descricao, data_vencimento, valor 
    FROM lancamentos_despesas 
    WHERE data_vencimento >= CURDATE() 
    AND situacao != 'Pago' 
    AND codigo_usuario = $idUsuario
    ORDER BY data_vencimento ASC 
    LIMIT 5";
    $resultContasAVencer = mysqli_query($conn, $sqlContasAVencer);

    //Consulta SQL para despesas em aberto
    $sqlDespesasEmAberto = "SELECT descricao, data_vencimento, valor 
    FROM lancamentos_despesas 
    WHERE situacao = 'Em Aberto' 
    AND codigo_usuario = $idUsuario
    ORDER BY data_vencimento ASC 
    LIMIT 5";
    $resultDespesasEmAberto = mysqli_query($conn, $sqlDespesasEmAberto);

    //Consulta para obter receitas em aberto
    $sqlReceitasEmAberto = "SELECT descricao, data_credito, valor 
    FROM lancamentos_receitas 
    WHERE situacao = 'Em Aberto'
    AND codigo_usuario = $idUsuario
    ORDER BY data_credito ASC 
    LIMIT 5";
    $resultReceitasEmAberto = mysqli_query($conn, $sqlReceitasEmAberto);

    //Consulta SQL para receitas vencidas e não pagas
    $sqlReceitasVencidas = "SELECT descricao, data_credito, valor 
    FROM lancamentos_receitas 
    WHERE data_credito < CURDATE() 
    AND situacao != 'RECEBIDO'
    AND codigo_usuario = $idUsuario
    ORDER BY data_credito ASC 
    LIMIT 5";
    $resultReceitasVencidas = mysqli_query($conn, $sqlReceitasVencidas);

    //SQL cont de despesas vencidas e não pagas
    $sqlCountContasVencidas = "SELECT COUNT(*) as count FROM lancamentos_despesas WHERE data_vencimento < CURDATE() AND situacao != 'Pago'AND codigo_usuario = $idUsuario";
    $resultCountContasVencidas = mysqli_query($conn, $sqlCountContasVencidas);
    $countContasVencidas = mysqli_fetch_assoc($resultCountContasVencidas)['count'];

    //SQL cont despesas a vencer e não pagas
    $sqlCountContasAVencer = "SELECT COUNT(*) as count FROM lancamentos_despesas WHERE data_vencimento >= CURDATE() AND situacao != 'Pago' AND codigo_usuario = $idUsuario";
    $resultCountContasAVencer = mysqli_query($conn, $sqlCountContasAVencer);
    $countContasAVencer = mysqli_fetch_assoc($resultCountContasAVencer)['count'];

    //SQL cont despesas em aberto
    $sqlCountContasPagas = "SELECT COUNT(*) as count FROM lancamentos_despesas WHERE situacao = 'Em Aberto'AND codigo_usuario = $idUsuario";
    $resultCountContasPagas = mysqli_query($conn, $sqlCountContasPagas);
    $countContasPagas = mysqli_fetch_assoc($resultCountContasPagas)['count'];

    //SQL cont de receitas em aberto
    $sqlCountReceitasAbertas = "SELECT COUNT(*) as count FROM lancamentos_receitas WHERE situacao = 'Em Aberto'AND codigo_usuario = $idUsuario";
    $resultReceitasAbertas = mysqli_query($conn, $sqlCountReceitasAbertas);
    $countReceitasAbertas = mysqli_fetch_assoc($resultReceitasAbertas)['count'];


    //SQL cont de Rceitas vencidas e não pagas
    $sqlCountReceitasVencidas = "SELECT COUNT(*) as count FROM lancamentos_receitas WHERE data_credito < CURDATE() AND situacao != 'Recebido'AND codigo_usuario = $idUsuario";
    $resultCountReceitasVencidas = mysqli_query($conn, $sqlCountReceitasVencidas);
    $countReceitasVencidas = mysqli_fetch_assoc($resultCountReceitasVencidas)['count'];

    ?>

    <div class="dashboard">
        <div class="card">
            <h3>Visão Geral</h3>
            <p>
            <div style="display: inline-block; font-weight: bold; margin-right: 70px; color:#ff3333">
                <?php echo 'Lançamentos de DESPESAS VENCIDOS: '; ?>
            </div>
            <div class="number" style="display: inline-block;
            align-items: stretch;
            font-size: 24px;
            color: #333;
            background-color: #ff3333;
            border: 2px solid white;
            border-radius: 10px;
            font-weight: bold; 
            padding: 10px 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transform: translateY(-8px);
            align-items: center;
            margin-left: -17px;"><?php echo $countContasVencidas; ?></div>
            </p>

            <p>
            <div style="display: inline-block; font-weight: bold; margin-right: 70px; color:#ff9999">
                <?php echo 'Lançamentos de DESPESA A VENCER:  '; ?>
            </div>
            <div class="number" style="display: inline-block;
            align-items: stretch;
            font-size: 24px;
            color: #333;
            background-color: #ff9999;
            border: 2px solid white;
            border-radius: 10px;
            font-weight: bold; 
            padding: 10px 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transform: translateY(-8px);
            margin-left: -10px;"><?php echo $countContasAVencer; ?></div>
            </p>

            <p>
            <div style="display: inline-block; margin-right: 70px; font-weight: bold; color:#FFA500">
                <?php echo 'Lançamentos de DESPESA EM ABERTO:  '; ?>
            </div>
            <div class="number" style="display: inline-block;
            align-items: stretch;
            font-size: 24px;
            color: #333;
            background-color: #FFA500;
            border: 2px solid White;
            border-radius: 10px;
            font-weight: bold; 
            padding: 10px 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transform: translateY(-8px);
            margin-left: -18px;"><?php echo $countContasPagas; ?></div>
            </p>

            <p>
            <div style="display: inline-block; margin-right: 70px; font-weight: bold; color:#3CB371">
                <?php echo 'Lançamentos de RECEITA EM ABERTO: '; ?>
            </div>
            <div class="number" style="display: inline-block;
            align-items: stretch;
            font-size: 24px;
            color: #333;
            background-color: #3CB371;
            border: 2px solid white;
            border-radius: 10px;
            font-weight: bold; 
            padding: 10px 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transform: translateY(-8px);
            margin-left: -14px;"><?php echo $countReceitasAbertas; ?></div>
            </p>

            <p>
            <div style="display: inline-block; margin-right: 70px; font-weight: bold; color:#004b23">
                <?php echo 'RECEITAS com DATA CRÉDITO VENCIDO: '; ?>
            </div>
            <div class="number" style="display: inline-block;
            align-items: stretch;
            font-size: 24px;
            color: WHITE;
            background-color: #004b23;
            border: 2px solid white;
            border-radius: 10px;
            font-weight: bold; 
            padding: 10px 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transform: translateY(-8px);
            margin-left: -25px;"><?php echo $countReceitasVencidas; ?></div>
            </p>
        </div>

        <div class="card-despesa">
            <h3>Despesas Vencidas</h3>
            <br>
            <table>
                <?php
                if (mysqli_num_rows($resultContasVencidas) > 0) {
                    echo '<table>';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th>Vencimento</th>';
                    echo '<th>Descrição</th>';
                    echo '<th>Valor</th>';
                    echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';
                    while ($row = mysqli_fetch_assoc($resultContasVencidas)) {
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars(formatarData($row['data_vencimento'])) . '</td>';
                        echo '<td>' . htmlspecialchars($row['descricao']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['valor']) . '</td>';
                        echo '</tr>';
                    }
                    echo '</tbody>';
                    echo '</table>';
                } else {
                    echo '<p style="font-size: 18px; background-color: #007bff; color: white; padding: 10px; border-radius: 5px; text-align: center;">Nenhuma DESPESA VENCIDA    encontrada</p>';
                }

                ?>
                </tbody>
            </table>
        </div>
        <div class="card-despesa">
            <h3>Despesas a vencer</h3>
            <br>
            <table>
                <?php
                if (mysqli_num_rows($resultContasAVencer) > 0) {
                    echo '<table>';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th>Vencimento</th>';
                    echo '<th>Descrição</th>';
                    echo '<th>Valor</th>';
                    echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';
                    while ($row = mysqli_fetch_assoc($resultContasAVencer)) {
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars(formatarData($row['data_vencimento'])) . '</td>';
                        echo '<td>' . htmlspecialchars($row['descricao']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['valor']) . '</td>';
                        echo '</tr>';
                    }
                    echo '</tbody>';
                    echo '</table>';
                } else {
                    echo '<p style="font-size: 18px; background-color: #007bff; color: white; padding: 10px; border-radius: 5px; text-align: center;">Nenhuma DESPESA A VENCER encontrada</p>';
                }

                ?>
                </tbody>
            </table>
        </div>

        <div class="card-despesa">
            <h3>Lançamentos de Despesas em Aberto</h3>
            <br>
            <table>
                <?php
                if (mysqli_num_rows($resultDespesasEmAberto) > 0) {
                    echo '<table>';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th>Vencimento</th>';
                    echo '<th>Descrição</th>';
                    echo '<th>Valor</th>';
                    echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';
                    while ($row = mysqli_fetch_assoc($resultDespesasEmAberto)) {
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars(formatarData($row['data_vencimento'])) . '</td>';
                        echo '<td>' . htmlspecialchars($row['descricao']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['valor']) . '</td>';
                        echo '</tr>';
                    }
                    echo '</tbody>';
                    echo '</table>';
                } else {
                    echo '<p style="font-size: 18px; background-color: #007bff; color: white; padding: 10px; border-radius: 5px; text-align: center;">Nenhuma DESPESA EM ABERTO encontrada</p>';
                }

                ?>
                </tbody>
            </table>
        </div>

        <div class="card-receita">
            <h3>Lançamentos de Receita em Aberto</h3>
            <br>
            <table>
                <tbody>
                    <?php
                    if (mysqli_num_rows($resultReceitasEmAberto) > 0) {
                        echo '<table>';
                        echo '<thead>';
                        echo '<tr>';
                        echo '<th>Data Crédito</th>';
                        echo '<th>Descrição</th>';
                        echo '<th>Valor</th>';
                        echo '</tr>';
                        echo '</thead>';
                        echo '<tbody>';
                        while ($row = mysqli_fetch_assoc($resultReceitasEmAberto)) {
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars(formatarData($row['data_credito'])) . '</td>';
                            echo '<td>' . htmlspecialchars($row['descricao']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['valor']) . '</td>';
                            echo '</tr>';
                        }
                        echo '</tbody>';
                        echo '</table>';
                    } else {
                        echo '<p style="font-size: 18px; background-color: #007bff; color: white; padding: 10px; border-radius: 5px; text-align: center;">Nenhuma RECEITA EM ABERTO encontrada</p>';
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="card-receita">
            <h3>Lançamentos de Receita Vencidas</h3>
            <br>
            <table>
                <tbody>
                    <?php
                    if (mysqli_num_rows($resultReceitasVencidas) > 0) {
                        echo '<table>';
                        echo '<thead>';
                        echo '<tr>';
                        echo '<th>Data Crédito</th>';
                        echo '<th>Descrição</th>';
                        echo '<th>Valor</th>';
                        echo '</tr>';
                        echo '</thead>';
                        echo '<tbody>';
                        while ($row = mysqli_fetch_assoc($resultReceitasVencidas)) {
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars(formatarData($row['data_credito'])) . '</td>';
                            echo '<td>' . htmlspecialchars($row['descricao']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['valor']) . '</td>';
                            echo '</tr>';
                        }
                        echo '</tbody>';
                        echo '</table>';
                    } else {
                        echo '<p style="font-size: 18px; background-color: #007bff; color: white; padding: 10px; border-radius: 5px; text-align: center;">Nenhuma RECEITA VENCIDA encontrada</p>';
                    }

                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <footer>
        <h5>&copy; 2024 Minha Carteira. Todos os direitos reservados.</h5>        
    </footer>

    <script>
        // Função para controlar o tempo e forma que aparece a mensagem de inclusão com sucesso (verde)
        window.addEventListener('DOMContentLoaded', function() {
            var mensagem = document.getElementById('sucesso');
            sucesso.style.display = 'block';

            setTimeout(function() {
                mensagem.style.opacity = 0;
                setTimeout(function() {
                    mensagem.style.display = 'none';
                }, 650);
            }, 1650);
        });

        var statusFields = document.getElementsByClassName("status");
        for (var i = 0; i < statusFields.length; i++) {
            var fieldValue = statusFields[i].textContent.toLowerCase();
            if (fieldValue === "pago") {
                statusFields[i].classList.add("verde");
            } else if (fieldValue === "em aberto") {
                statusFields[i].classList.add("vermelho");
            }
        }

        function mostrarTabelaSelecionada() {
            var opcaoSelecionada = document.getElementById("opcoes").value;
            var tabelaUsuarios = document.getElementById("tabela-usuarios");
            var lancamentos7dias = document.getElementById("lancamentos-7dias");
            var lancamentosDeHoje = document.getElementById("lancamentos-de-hoje");
            var tabelaMesAnterior = document.getElementById("tabela-mes-anterior");
            var paragrafoLancamentos7Dias = document.querySelector(".paragrafo-lancamentos7dias");
            var paragrafoLancamentosDeHoje = document.querySelector(".paragrafo-lancamentosDeHoje");
            var paragrafoLancamentosMesAnterior = document.querySelector(".paragrafo-lancamentosMesAnterior");

            // Oculta todas as tabelas e parágrafos
            var tabelas = document.getElementsByTagName("table");
            for (var i = 0; i < tabelas.length; i++) {
                tabelas[i].style.display = "none";
            }

            var paragrafos = document.querySelectorAll(".paragrafo-lancamentos7dias, .paragrafo-lancamentosDeHoje, .paragrafo-lancamentosMesAnterior");
            for (var i = 0; i < paragrafos.length; i++) {
                paragrafos[i].style.display = "none";
            }

            // Exibe a tabela selecionada e o parágrafo correspondente
            if (opcaoSelecionada === "tabela-usuarios") {
                tabelaUsuarios.style.display = "table";
            } else if (opcaoSelecionada === "lancamentos-7dias") {
                lancamentos7dias.style.display = "table";
                paragrafoLancamentos7Dias.style.display = "block";
            } else if (opcaoSelecionada === "lancamentos-de-hoje") {
                lancamentosDeHoje.style.display = "table";
                paragrafoLancamentosDeHoje.style.display = "block";
            } else if (opcaoSelecionada === "tabela-mes-anterior") {
                tabelaMesAnterior.style.display = "table";
                paragrafoLancamentosMesAnterior.style.display = "block";
            }
        }

        function filtrarPorAno() {
            var anoSelecionado = document.getElementById("anoSelect").value;

            // Fazer requisição AJAX para atualizar o saldo
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "atualizar_saldo.php?ano=" + encodeURIComponent(anoSelecionado), true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    // Atualizar o saldo na tela
                    var saldoElemento = document.getElementById("saldoAtual");
                    if (saldoElemento) {
                        saldoElemento.innerHTML = xhr.responseText;
                    }
                }
            };
            xhr.send();
        }
    </script>
</body>

</html>