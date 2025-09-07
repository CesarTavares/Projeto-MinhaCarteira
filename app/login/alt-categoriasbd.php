<?php
    require_once("./_sessao.php");

    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        $descricao = filter_input(INPUT_POST, "descricao",FILTER_SANITIZE_SPECIAL_CHARS);
         $tipo = filter_input(INPUT_POST, "tipo", FILTER_SANITIZE_SPECIAL_CHARS);
        $codigo = filter_input(INPUT_POST, "codigo", FILTER_SANITIZE_NUMBER_INT);


        require_once("./_conexao/conexao.php");

        $sql = "UPDATE `categorias` SET 
                    `descricao` = :descricao,
                    `tipo` = :tipo,
                    `codigo` = :codigo


                WHERE `codigo` = :codigo";

                $comandoSQL = $conexao->prepare($sql);  

                $comandoSQL->bindParam(':descricao', $descricao, PDO::PARAM_STR);
                 $comandoSQL->bindParam(':tipo', $tipo, PDO::PARAM_STR);
                $comandoSQL->bindParam(':codigo', $codigo, PDO::PARAM_STR);

                $comandoSQL->execute();

                if ($comandoSQL->rowCount() == 1) {
                    header("Location: ./view-categorias.php?status=altsucesso");
                    exit();
                } else if ($comandoSQL->rowCount() < 1) {
                    header("Location: ./view-categorias.php?status=altNever");
                    exit();
                }

        }else{
            echo("Entre em contato com o Administrador!");
        }

    $conexao=null;


    