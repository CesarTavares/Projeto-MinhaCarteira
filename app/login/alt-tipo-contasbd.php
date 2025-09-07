<?php
    require_once("./_sessao.php");

    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        $descricao = filter_input(INPUT_POST, "descricao",FILTER_SANITIZE_SPECIAL_CHARS);
        $codigo = filter_input(INPUT_POST, "codigo", FILTER_SANITIZE_NUMBER_INT);


        require_once("./_conexao/conexao.php");

        $sql = "UPDATE `tipos_carteiras` SET 
                    `descricao` = :descricao
                WHERE `codigo` = :codigo";

                $comandoSQL = $conexao->prepare($sql);  

                $comandoSQL->bindParam(':descricao', $descricao, PDO::PARAM_STR);
                $comandoSQL->bindParam(':codigo', $codigo, PDO::PARAM_STR);

               $comandoSQL->execute();  

                if($comandoSQL->rowCount() == 1){
                    echo "Usuário atualizado com sucesso!";
                   
                 //   header("Location: ./view-contas.php");
                    header("Location: ./view-tipo-contas.php?status=altsucesso");

                    exit();
                }else if($comandoSQL->rowCount() < 1){ 
                    // echo "Erro na atualização!";

                    // echo("<pres>");
                    // $comandoSQL->debugDumpParams();
                    // echo "</pres>";
                    header("Location: ./view-tipo-contas.php?status=altNever");
                }
                

    }else{
        echo("Entre em contato com o Administrador!");
    }

    $conexao=null;