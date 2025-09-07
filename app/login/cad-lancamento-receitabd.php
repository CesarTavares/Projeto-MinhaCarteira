<?php
require_once("_sessao.php");
require_once("./_conexao/conexao.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $descricao = filter_input(INPUT_POST, "descricao", FILTER_SANITIZE_SPECIAL_CHARS);
        $valor = filter_input(INPUT_POST, "valor", FILTER_SANITIZE_SPECIAL_CHARS);
        $data_credito = filter_input(INPUT_POST, "data_credito", FILTER_SANITIZE_SPECIAL_CHARS);
        $categoria_id = filter_input(INPUT_POST, "categoria", FILTER_SANITIZE_SPECIAL_CHARS);
        $carteira_id = filter_input(INPUT_POST, "carteira", FILTER_SANITIZE_SPECIAL_CHARS);
        $codigo = $_SESSION['codigo'];
        $situacao = filter_input(INPUT_POST, "situacao", FILTER_SANITIZE_SPECIAL_CHARS);
        
        // Verificar se já existe um lançamento igual cadastrado
        $consultaExistente = $conexao->prepare("
            SELECT COUNT(*) AS total
            FROM `lancamentos_receitas`
            WHERE 
                `descricao` = :descricao AND
                `valor` = :valor AND
                `data_credito` = :data_credito AND
                `categoria` = :categoria AND
                `carteira` = :carteira AND
                `codigo_usuario` = :codigo
        ");

        // Bind dos parâmetros
        $consultaExistente->bindParam(':descricao', $descricao);
        $consultaExistente->bindParam(':valor', $valor);
        $consultaExistente->bindParam(':data_credito', $data_credito);
        $consultaExistente->bindParam(':categoria', $categoria_id);
        $consultaExistente->bindParam(':carteira', $carteira_id);
        $consultaExistente->bindParam(':codigo', $codigo);

        // Executar a consulta
        $consultaExistente->execute();

     // Obter o resultado da consulta
$resultadoExistente = $consultaExistente->fetch(PDO::FETCH_ASSOC);

        // Verificar se já existe um lançamento igual
        if ($resultadoExistente['total'] > 0) {
            // Lançamento igual já existe, redirecionar para a página "cad-lancamento.php" com os valores dos campos na URL
            $descricao = urlencode($descricao);
            $valor = urlencode($valor);
            $data_credito = urlencode($data_credito); 
            header('Location: ./cad-lancamento-receita.php?erro=lancamento-igual&descricao='.$descricao.'&valor='.$valor.'&data_credito='.$data_credito .'&categoria='.$categoria.'&carteira='.$carteira.'&situacao='.$situacao);
            exit();
        }

        // Preparar e executar a consulta SQL para inserir o novo lançamento
        $comandoSQL = $conexao->prepare("
            INSERT INTO `lancamentos_receitas` (
                `descricao`,
                `valor`,
                `data_credito`,
                `categoria`,
                `carteira`,
                `codigo_usuario`,
                `situacao`
            )
            VALUES (
                :descricao,
                :valor,
                :data_credito,
                :categoria,
                :carteira,
                :codigo,
                :situacao
            )
        ");

        $comandoSQL->execute(array(
            ':descricao'     => $descricao,
            ':valor'         => $valor,
            ':data_credito' => $data_credito,
            ':categoria'     => $categoria_id,
            ':carteira'         => $carteira_id,
            ':codigo'        => $codigo,
            ':situacao'        => $situacao
        ));

        if ($comandoSQL->rowCount() == 1) {
            header("Location: ./view-lancamento.php?status=sucesso-receita");
            exit();
        } else {
            header("Location: ./view-lancamento.php?status=insucesso-receita");
            exit();
        }
        $conexao = null; //fechando a conexão com o banco de dados

    } catch (PDOException $erro) {
        header("Location: ./view-lancamento.php?status=insucesso");
        // echo "Erro no banco de dados: " . $erro->getMessage();
    }
} else {
    echo "Método de requisição inválido.";
}
?>
