<?php
    require_once("./_sessao.php");

    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        $data_credito = filter_input(INPUT_POST, "data_credito", FILTER_SANITIZE_SPECIAL_CHARS);
        $descricao = filter_input(INPUT_POST, "descricao", FILTER_SANITIZE_SPECIAL_CHARS);
        $valor = filter_input(INPUT_POST, "valor", FILTER_SANITIZE_SPECIAL_CHARS);
        $categoria = filter_input(INPUT_POST, "categoria", FILTER_SANITIZE_SPECIAL_CHARS);
        $carteira = filter_input(INPUT_POST, "carteira", FILTER_SANITIZE_SPECIAL_CHARS);
        $codigo_usuario = $_SESSION['codigo'];
        $situacao = filter_input(INPUT_POST, "situacao", FILTER_SANITIZE_SPECIAL_CHARS);
        $codigo = filter_input(INPUT_POST, "codigo", FILTER_SANITIZE_SPECIAL_CHARS);
       
        require_once("./_conexao/conexao.php");

        $sql = "UPDATE `lancamentos_receitas` SET 
                    `data_credito` = :data_credito,
                    `descricao` = :descricao,
                    `valor` = :valor,
                    `categoria` = :categoria,
                    `carteira` = :carteira,
                    `codigo_usuario` = :codigo_usuario,
                    `situacao` = :situacao

                WHERE `codigo` = :codigo";

                $comandoSQL = $conexao->prepare($sql);  

                $comandoSQL->bindParam(':data_credito', $data_credito, PDO::PARAM_STR); 
                $comandoSQL->bindParam(':descricao', $descricao, PDO::PARAM_STR);
                $comandoSQL->bindParam(':valor', $valor, PDO::PARAM_STR);
                $comandoSQL->bindParam(':categoria', $categoria, PDO::PARAM_STR);
                $comandoSQL->bindParam(':carteira', $carteira, PDO::PARAM_STR);
                $comandoSQL->bindParam(':codigo_usuario', $codigo_usuario, PDO::PARAM_STR);
                $comandoSQL->bindParam(':situacao', $situacao, PDO::PARAM_STR);
                $comandoSQL->bindParam(':codigo', $codigo, PDO::PARAM_STR);

                $comandoSQL->execute();

                if ($comandoSQL->rowCount() == 1) {
                    header("Location: ./view-lancamento.php?status=altsucesso-receita");
                    exit();
                } else {
                    // Redirecionamento para altinsucesso se não houver alterações
                    header("Location: ./view-lancamento.php?status=altNever");
                    exit();
                }
    }else{
        header("Location: ./view-lancamento.php?status=altinsucesso");
    }
    $conexao=null;