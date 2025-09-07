<?php
require_once("_sessao.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $descricao = filter_input(INPUT_POST, "descricao", FILTER_SANITIZE_SPECIAL_CHARS);
        $tipo = filter_input(INPUT_POST, "tipo", FILTER_SANITIZE_SPECIAL_CHARS);
        $codigo = $_SESSION['codigo'];

        require_once("./_conexao/conexao.php");

        // Verificar se já existe uma categoria igual cadastrada
        $consultaExistente = $conexao->prepare("
            SELECT COUNT(*) AS total
            FROM `categorias`
            WHERE 
                `descricao` = :descricao AND
                `tipo` = :tipo AND
                `codigo_usuario` = :codigo
        ");

        $consultaExistente->bindParam(':descricao', $descricao);
        $consultaExistente->bindParam(':tipo', $tipo);
        $consultaExistente->bindParam(':codigo', $codigo);
        $consultaExistente->execute();

        $resultadoExistente = $consultaExistente->fetch(PDO::FETCH_ASSOC);

        if ($resultadoExistente['total'] > 0) {
            // Categoria igual já existe, redirecionar para a página "cad-categorias.php" com os valores dos campos na URL
            $descricao = urlencode($descricao);
            $tipo = urlencode($tipo);
            header('Location: ./cad-categorias.php?erro=lancamento-igual&descricao=' . $descricao . '&tipo=' . $tipo);
            exit();
        }

        // Inserir nova categoria
        $comandoSQL = $conexao->prepare("
            INSERT INTO `categorias` (`descricao`, `tipo`, `codigo_usuario`)
            VALUES (:descricao, :tipo, :codigo_usuario)
        ");

        $resultado = $comandoSQL->execute(array(
            ':descricao' => $descricao,
            ':tipo' => $tipo,
            ':codigo_usuario' => $codigo
        ));

        if ($resultado) {
            header("Location: ./view-categorias.php?status=sucesso");
            exit();
        } else {
            header("Location: ./view-categorias.php?status=altinsucesso");
            exit();
        }
    } catch (PDOException $erro) {
        echo "Erro: " . $erro->getMessage();
    }
} else {
    echo "Entre em contato com o Administrador";
}
?>
