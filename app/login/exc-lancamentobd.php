<?php
    require_once("_sessao.php");

    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        $codigo= filter_input(INPUT_POST, "codigo", FILTER_SANITIZE_NUMBER_INT);

        require_once("./_conexao/conexao.php");

        $sql = "DELETE FROM `lancamentos_despesas`
                WHERE `codigo` = :codigo";

                $comandoSQL = $conexao->prepare($sql);  

                $comandoSQL->bindParam(':codigo', $codigo, PDO::PARAM_STR);

                $comandoSQL->execute();  

                if ($comandoSQL->rowCount() == 1) {
                    echo "Lançamento de Despesa excluído com sucesso!";
                    header("Location: ./view-lancamento.php?status=excsucesso");
                    exit();
                } else {
                    // Redirecionamento para altinsucesso se não houver alterações
                    header("Location: ./view-lancamento.php?status=excinsucesso");
                    exit();
                }

    }else{
        echo("Entre em contato com o Administrador!");
    }

    $conexao=null;