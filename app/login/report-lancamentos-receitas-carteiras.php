<?php
require("./_sessao.php");
include_once("./_conexao/conexao.php");
require_once("./funcoes_gerais.php");

//Receber os dados do formulario
$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

//Inicializar variaveis
$data_inicio = isset($dados['data_inicio']) ? $dados['data_inicio']: "";
$data_final = isset($dados['data_final']) ? $dados['data_final']: "";
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minha Carteira - Relatório de Lançamentos de Receitas por Carteiras</title>    
    <link rel="stylesheet" href="./css/estilo.css">  
</head>

<body>
    <div class="container">
        <h1>Minha Carteira - Controle Financeiro Pessoal</h1>
        <h1>Relatório de Lançamentos de Receitas por Carteiras</h1>
    </div>

    <div class="containercad" style="margin-top: 0px;">
            <form method="POST" action="">                
                <div class="containercad" style="margin-top: 0px;">
                    <div class="col">
                        <label>Data de Inicio</label>
                        <input type="date" style="padding: 10px; font-size: 20px;" name="data_inicio" value="<?php echo htmlspecialchars($data_inicio, ENT_QUOTES, 'UTF-8'); ?>">                    
                    
                        <label>Data Final</label>
                        <input type="date" style="padding: 10px; font-size: 20px;" name="data_final" value="<?php echo htmlspecialchars($data_final, ENT_QUOTES, 'UTF-8'); ?>">
                    </div>
                </div> 
    <div class="containercad" style="margin-top: 0px;">        
            <div class="row-flex">
                <div class="col">                    
                            <label for="carteira">Carteira</label>
                            <select name="carteira" id="carteira">
                                <?php

                                // Obtém o código da sessão do usuário
                                $codigo = $_SESSION['codigo'];

                                // Verifique se a conexão foi estabelecida com sucesso
                                if ($conexao) {
                                    // Execute a consulta para buscar as categorias do usuário
                                    $query = "SELECT codigo, descricao FROM minhacarteira.carteiras WHERE codigo_usuario  = :codigo_usuario";
                                    $stmt_report = $conexao->prepare($query);
                                    $stmt_report->execute(array(':codigo_usuario' => $_SESSION['codigo']));

                                    $row_carteiras = $stmt_report->fetchAll();

                                    echo '<option value="">Selecione</option>'; // Opção padrão "Selecione"

                                        // Itera sobre as categorias e cria as opções do campo de seleção
                                        foreach ($row_carteiras as $linha) {

                                            $codigoCarteira = (string)$linha["codigo"];
                                            $nomeCarteira = $linha["descricao"];

                                            // Exibe a opção com o código e a descrição
                                            echo "<option value=\"$codigoCarteira\">$nomeCarteira</option>";
                                        }

                                    // Verifica se há uma nova categoria na URL e exibe no campo específico
                                    if (isset($_GET['nova_carteira'])) {
                                        $novaCarteira = htmlspecialchars($_GET['nova_carteira']);

                                        // Exibe o campo de seleção da categoria com a nova categoria selecionada
                                        echo "<option value=\"$novaCarteira\" selected>$novaCarteira</option>";
                                        // Limpa a variável da URL após usar
                                        unset($_GET['nova_carteira']);
                                        }
                                    } else {
                                        echo '<option value=\"\">Erro na Conexãocom o banco de dados</option>';
                                    }                                
                                ?>
                            </select>
                        </div>
                    </div>
                </div>           

            <div class="containercad" style="margin-top: 35px;">
                <div class="espaco-m"></div>
                <input type="submit" value="Pesquisar" name="PesqCarteiras"><br><br>
            <?php
                $carteira = isset($dados['carteira']) ? htmlspecialchars($dados['carteira'], ENT_QUOTES, 'UTF-8') : "";
                $data_inicio = isset($dados['data_inicio']) ? htmlspecialchars($dados['data_inicio'], ENT_QUOTES, 'UTF-8') : "";
                $data_final = isset($dados['data_final']) ? htmlspecialchars($dados['data_final'], ENT_QUOTES, 'UTF-8') : "";
                if ($carteira && $data_inicio && $data_final) {                  
                echo "<a class='link-as-button' href='gerar_lancamentos_receitas_carteiras.php?carteira=$carteira&data_inicio=$data_inicio&data_final=$data_final'>GERAR PDF DA PESQUISA</a><br><br>";
            } else {
                // echo "Por favor, preencha tosos os campos do formulário.";
            }
            ?>
            
                        <div class="voltar" style="background-color: dimgray">
                            <input type="submit" style="background-color:dimgray; border: 1px solid dimgray; font-size: 16px"
                                value="Fechar" formaction="../../tela-inicial.php">
                        </div>
            
            </div>    
            
        </form>
        </div>    

    </div>

    <div class="container" style="margin-left:30px">  
    <?php
    if (!empty($dados['PesqCarteiras'])) {
        //Preparar para a Consulta
        $query_lancamentos = "SELECT ld.codigo, ld.descricao, ld.valor, ld.data_credito, cat.descricao AS descricao_categoria, car.descricao AS descricao_carteira, ld.situacao  
                              FROM lancamentos_receitas ld
                              INNER JOIN categorias cat ON ld.categoria = cat.codigo
                              INNER JOIN carteiras car ON ld.carteira = car.codigo
                              WHERE ld.carteira = :carteira AND ld.data_credito BETWEEN :data_inicio AND :data_final
                              ORDER BY ld.codigo DESC";

        $result_lancamentos = $conexao->prepare($query_lancamentos);
        $result_lancamentos->bindParam(':carteira', $dados['carteira'], PDO::PARAM_STR);
            $result_lancamentos->bindParam(':data_inicio', $dados['data_inicio'], PDO::PARAM_STR);
            $result_lancamentos->bindParam(':data_final', $dados['data_final'], PDO::PARAM_STR);
            $result_lancamentos->execute();

        //Verificar se há resultados
        if ($result_lancamentos->rowCount() != 0) {
            echo "<table border='1'>
            <tr>
            <th>Descriçao</th>
            <th>Valor</th>
            <th>Data Credito</th>
            <th>Categorias</th>
            <th>Carteira de Pagamento</th>
            <th>Situação</th>
            </tr>";

            while ($row_lancamento = $result_lancamentos->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>
                            <td>{$row_lancamento['descricao']}</td>
                            <td>" . formatarMoeda($row_lancamento['valor']) . "</td>
                            <td>" . formatarData($row_lancamento['data_credito']) . "</td>
                            <td>{$row_lancamento['descricao_categoria']}</td>
                            <td>{$row_lancamento['descricao_carteira']}</td>
                            <td>{$row_lancamento['situacao']}</td>
                        </tr>";    
                    }
                    echo "</table>";
        } else {
            echo "<p style='color: #f00;'>ATENÇÃO!! Nenhum lançamento encontrado!</p>";
        }
    }
    ?>
    </div>
</body>
</html>