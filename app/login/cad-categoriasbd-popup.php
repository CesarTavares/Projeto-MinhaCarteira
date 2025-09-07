<?php
require_once("_sessao.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $descricao = filter_input(INPUT_POST, "descricao", FILTER_SANITIZE_SPECIAL_CHARS);
    $codigo_usuario = $_SESSION['codigo_usuario'];

    try {
        require_once("./_conexao/conexao.php");

        // Verificar se a categoria já existe para o usuário atual
        $consultaCategoriaExistente = $conexao->prepare("SELECT COUNT(*) AS total FROM categorias WHERE descricao = :descricao AND codigo_usuario = :codigo_usuario");
        $consultaCategoriaExistente->bindParam(':descricao', $descricao);
        $consultaCategoriaExistente->bindParam(':codigo_usuario', $codigo_usuario);
        $consultaCategoriaExistente->execute();
        $resultadoCategoriaExistente = $consultaCategoriaExistente->fetch(PDO::FETCH_ASSOC);

        if ($resultadoCategoriaExistente['total'] > 0) {
            // Categoria já existe para o usuário atual
            header("Location: ./cad-lancamento.php?status=categoria-existente");
            exit();
        }

        // Se a categoria não existir, proceda com a inserção
        $inserirCategoria = $conexao->prepare("
            INSERT INTO `categorias` (
                `descricao`,
                `codigo_usuario`
            )
            VALUES (
                :descricao,
                :codigo_usuario
            )
        ");

        $inserirCategoria->bindParam(':descricao', $descricao);
        $inserirCategoria->bindParam(':codigo_usuario', $codigo_usuario);
        $inserirCategoria->execute();

        if ($inserirCategoria->rowCount() > 0) {
            // Redirecione para a página de cadastro de lançamento com uma mensagem de sucesso
            header("Location: ./cad-lancamento.php?status=sucesso&nova_categoria=" . urlencode($descricao));
            exit();
        } else {
            // Redirecione para a página de visualização de categorias com uma mensagem de erro
            header("Location: ./cad-lancamento.php?status=altinsucesso");
            exit();
        }

        $conexao = null; // fechando a conexão com o banco
    } catch (PDOException $erro) {
        // Log do erro
        error_log("Erro no cad-categoriasbd-popup.php: " . $erro->getMessage());

        // Exibir mensagem de erro
        echo "Erro durante o processamento. Entre em contato com o administrador.";
    }
}
?>
