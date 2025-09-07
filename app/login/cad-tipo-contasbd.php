<?php
require_once("_sessao.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $descricao = filter_input(INPUT_POST, "descricao", FILTER_SANITIZE_SPECIAL_CHARS);
    $codigo = $_SESSION['codigo'];

    require_once("./_conexao/conexao.php");

    try {
        // Verificar se já existe uma carteira com a mesma descrição para o mesmo usuário
        $consultaExistente = $conexao->prepare("
            SELECT COUNT(*) AS total
            FROM `tipos_carteiras`
            WHERE 
                `descricao` = :descricao AND
                `codigo_usuario` = :codigo
        ");

        $consultaExistente->bindParam(':descricao', $descricao);
        $consultaExistente->bindParam(':codigo', $codigo);
        $consultaExistente->execute();

        $resultadoExistente = $consultaExistente->fetch(PDO::FETCH_ASSOC);

        if ($resultadoExistente['total'] > 0) {
            // Carteira igual já existe, redirecionar para a página "cad-tipo-contas.php" com os valores dos campos na URL
            $descricao = urlencode($descricao);
            header('Location: ./cad-tipo-contas.php?erro=lancamento-igual&descricao=' . $descricao);
            exit();
        }

        // Inserir nova carteira
        $comandoSQL = $conexao->prepare("
            INSERT INTO `tipos_carteiras` (
                `descricao`,
                `codigo_usuario`
            ) VALUES (
                :descricao,
                :codigo_usuario
            )
        ");

        $comandoSQL->execute(array(
            ':descricao' => $descricao,
            ':codigo_usuario' => $codigo
        ));

        if ($comandoSQL->rowCount() > 0) {
            header("Location: ./view-tipo-contas.php?status=sucesso");
            exit();
        } else {
            header("Location: ./view-contas.php?status=insucesso");
            exit();
        }
    } catch (PDOException $erro) {
        echo "Erro: " . $erro->getMessage();
    }
} else {
    echo "Entre em contato com o Administrador";
}
?>
