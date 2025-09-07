<?php

// valida se os dados vieram pelo método POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    require_once("./app/login/_conexao/conexao.php");

    $usuario = filter_input(INPUT_POST, "usuario", FILTER_SANITIZE_EMAIL);
    $senha = filter_input(INPUT_POST, "senha", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    try {
        $comandoSQL = "SELECT * FROM usuarios WHERE email = :usuario";
        
        $comandoSQL = $conexao->prepare($comandoSQL);

        $comandoSQL->bindParam(":usuario", $usuario);

        $comandoSQL->execute();

        if ($comandoSQL->rowCount() > 0) {
            $linha = $comandoSQL->fetch();

            // Adicionando logs para depuração
            error_log("Situação do usuário: " . $linha["situacao"]);

            if ($linha["status"] === "Desativado") {
                // Se a situação do usuário é "Desativado", redireciona para uma página de erro
                header("Location: ./index.php?status=contaDesativada");
                exit();
            }

            if (password_verify($senha, $linha["senha"])) {
                session_start();
                $_SESSION["nome"] = $linha["nome"];
                $_SESSION["nivel"] = $linha["nivel"];
                $_SESSION["codigo"] = $linha["codigo"];
        
                header("Location: ./tela-inicial.php?status=sucesso");
                exit();
            } else {
                header("Location: ./index.php?status=erroSenha");
                exit();
            }
        } else {
            header("Location: ./index.php?status=erroUsuario");
            exit();
        }

    } catch (PDOException $erro) {
        echo "Erro..: " . $erro->getMessage();
    }

} else {
    // echo("Entre em contato com o Administrador");
    header("location:./index.php");
    exit();
}
