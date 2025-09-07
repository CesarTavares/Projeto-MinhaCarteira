<?php
require_once("_sessao.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $descricao = filter_input(INPUT_POST, "descricao", FILTER_SANITIZE_SPECIAL_CHARS);
    $descricaoCarteira = filter_input(INPUT_POST, "descricaoCarteira", FILTER_SANITIZE_SPECIAL_CHARS);
    $codigo = $_SESSION['codigo'];

    try {
        require_once("./_conexao/conexao.php");

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
            $descricaoCarteira = urlencode($descricaoCarteira);
            header('Location: ./cad-contas.php?erro=lancamento-igual-tipo&tipo=' . $descricao. '&descricaoCarteira=' . $descricaoCarteira);
            exit();
        }

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
            // Sucesso, redirecionar com a nova descrição
            header("Location: ./cad-contas.php?status=sucesso&novo_tipo_carteira=" . urlencode($descricao) . '&descricaoCarteira=' . urlencode($descricaoCarteira));
        exit();

        } else {
            echo ("Erro no Registro.");
        }

        $conexao = null; // Fechando a conexão com o banco

    } catch (PDOException $erro) {
        header("Location: ./view-tipo-contas.php?status=insucesso");
        echo ("<pre>");
        $comandoSQL->debugDumpParams();
        echo ("</pre>");
    }
} else {
    echo ("Entre em contato com o Administrador");
}
?>
