<?php
    require("./_sessao.php");
    include_once("./_conexao/conexao.php");
    require_once("./funcoes_gerais.php");

    //Receber os dados do formulario
    $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

    //Inicializar variaveis
    $data_inicio = isset($dados['data_inicio']) ? $dados['data_inicio'] : "";
    $data_final = isset($dados['data_final']) ? $dados['data_final'] : "";
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minha Carteira - Relatório de Lançamentos de Receitas por Data</title>
    <link rel="stylesheet" href="./css/estilo.css">

</head>

<body>
    <div class="container">
        <h1>Minha Carteira - Controle Financeiro Pessoal</h1>
        <h1>Relatório de Lançamentos de Receitas por Data </h1>
    </div>

    <div class="containercad" style="margin-top: 0px;">
        <form method="POST" action="">
            <div class="containercad" style="margin-top: 0px;">
                <div class="col">
                    <label>Data de Inicio</label>
                    <input type="date" style="padding:10px; font-size: 25px;" name="data_inicio" value="<?php echo htmlspecialchars($data_inicio, ENT_QUOTES, 'UTF-8'); ?>">

                    <label>Data Final</label>
                    <input type="date" style="padding:10px; font-size: 25px;" name="data_final" value="<?php echo htmlspecialchars($data_final, ENT_QUOTES, 'UTF-8'); ?>">
                </div>
            </div>

            <div class="containercad" style="margin-top: 35px;">

                <div class="espaco-m"></div>
                <input type="submit" value="Pesquisar" name="PesqData">

                <?php
                $query_params = http_build_query(['data_inicio' => $data_inicio, 'data_final' => $data_final]);
                echo "<a class='link-as-button' href='gerar_lancamentos_receitas_pdf.php?$query_params'>GERAR PDF DA PESQUISA</a><br><br>";
                ?>

                <div class="voltar" style="background-color: dimgray">
                    <input type="submit" style="background-color:dimgray; border: 1px solid dimgray; font-size: 16px" value="Fechar" formaction="../../tela-inicial.php">
                </div>
            </div>
        </form>
    </div>

    <div class="containercad">
        <?php
        $idUsuario = $_SESSION['codigo'];
        if (!empty($dados['PesqData'])) {
            //Prepara para a consulta                    
            $query_lancamentos = "SELECT ld.descricao, ld.valor, ld.data_credito, 
                            cat.descricao AS descricao_categoria, 
                            car.descricao AS descricao_carteira, 
                            ld.situacao 
                      FROM lancamentos_receitas ld
                      INNER JOIN categorias cat ON ld.categoria = cat.codigo
                      INNER JOIN carteiras car ON ld.carteira = car.codigo
                      WHERE ld.data_credito BETWEEN :data_inicio AND :data_final
                      AND ld.codigo_usuario = :codigo_usuario
                      ORDER BY data_credito ASC";

            $result_lancamentos = $conexao->prepare($query_lancamentos);
            $result_lancamentos->bindParam(':data_inicio', $dados['data_inicio']);
            $result_lancamentos->bindParam(':data_final', $dados['data_final']);
            $result_lancamentos->bindParam(':codigo_usuario', $idUsuario);
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
                echo "<p style='color: #f00;'>Erro: Nenhum lançamento encontrado!</p>";
            }
        }
        ?>
    </div>
</body>

</html>