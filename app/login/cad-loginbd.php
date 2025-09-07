<?php
require_once("_sessao.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $codigo = filter_input(INPUT_POST, "codigo", FILTER_SANITIZE_NUMBER_INT);
        $nome = filter_input(INPUT_POST, "nome", FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
        $senha1 = filter_input(INPUT_POST, "senha1", FILTER_SANITIZE_SPECIAL_CHARS);
        $senha2 = filter_input(INPUT_POST, "senha2", FILTER_SANITIZE_SPECIAL_CHARS);
        $nivel = filter_input(INPUT_POST, "nivel", FILTER_SANITIZE_SPECIAL_CHARS);
        $status = filter_input(INPUT_POST, "status", FILTER_SANITIZE_SPECIAL_CHARS);

        require_once("./_conexao/conexao.php");

        // Verificar se já existe um usuário com o mesmo nome ou e-mail
        $consultaExistente = $conexao->prepare("
            SELECT COUNT(*) AS total
            FROM `usuarios`
            WHERE 
                `nome` = :nome OR
                `email` = :email
        ");

        $consultaExistente->bindParam(':nome', $nome);
        $consultaExistente->bindParam(':email', $email);
        $consultaExistente->execute();

        $resultadoExistente = $consultaExistente->fetch(PDO::FETCH_ASSOC);

        if ($resultadoExistente['total'] > 0) {
            header('Location: ./cad-login.php?erro=lancamento-igual&nome=' . $nome . '&email=' . $email);
            exit();
        }

        // Inserir novo usuário
        $comandoSQL = $conexao->prepare("
            INSERT INTO `usuarios` (
                `nome`,
                `email`,
                `senha`,
                `nivel`,
                `status`
            ) VALUES (
                :nome,
                :email,
                :senha,
                :nivel,
                :status
            )
        ");

        $resultado = $comandoSQL->execute(array(
            ':nome' => $nome,
            ':email' => $email,
            ':senha' => password_hash($senha1, PASSWORD_DEFAULT),
            ':nivel' => $nivel,
            ':status' => $status
        ));

        if ($resultado) {
             header("Location: ./view-login.php?status=sucesso");
            exit();
        } else {
            header("Location: ../../tela-inicial.php?status=insucesso");
            exit();
        }
    } catch (PDOException $erro) {
        echo "Erro: " . $erro->getMessage();
    }
} else {
    echo "Entre em contato com o Administrador";
}
