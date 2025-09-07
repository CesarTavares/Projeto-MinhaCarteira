<?php
    require_once("_sessao.php");

    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        $codigo= filter_input(INPUT_POST, "codigo", FILTER_SANITIZE_NUMBER_INT);

        require_once("./_conexao/conexao.php");

        $sql = "DELETE FROM `tipos_carteiras`
                WHERE `codigo` = :codigo";

                $comandoSQL = $conexao->prepare($sql);  

                $comandoSQL->bindParam(':codigo', $codigo, PDO::PARAM_STR);

                $comandoSQL->execute();  

                if($comandoSQL->rowCount() == 1){
                 //   echo "Usuário EXCLUÍDO com sucesso!";

                 header("Location: ./view-tipo-contas.php?status=excsucesso");
                 exit();

                }else{  
                    header("Location: ./view-tipo-contas.php?status=insucesso");
                    exit();

                    // echo("<pres>");
                    // $comandoSQL->debugDumpParams();
                    // echo "</pres>";
                }

    }else{
        echo("Entre em contato com o Administrador!");
    }

    $conexao=null;