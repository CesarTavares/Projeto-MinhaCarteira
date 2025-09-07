<?php
    require_once("./_sessao.php");

    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        $codigo= filter_input(INPUT_POST, "codigo", FILTER_SANITIZE_NUMBER_INT);
        $nome = filter_input(INPUT_POST,"nome",FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST,"email",FILTER_SANITIZE_EMAIL);
        $senha1 = filter_input(INPUT_POST,"senha1",FILTER_SANITIZE_SPECIAL_CHARS);
        if($senha1 != "********"){
            $senha1 = password_hash($senha1, PASSWORD_DEFAULT); 
        }       
        $senha2 = filter_input(INPUT_POST,"senha2",FILTER_SANITIZE_SPECIAL_CHARS);
        $nivel = filter_input(INPUT_POST,"nivel",FILTER_SANITIZE_SPECIAL_CHARS);
        $status = filter_input(INPUT_POST,"status",FILTER_SANITIZE_SPECIAL_CHARS);
       

        require_once("./_conexao/conexao.php");

        if($senha1 != "********"){
            $sql = "UPDATE `usuarios` SET 
                        `nome` = :nome,
                        `email`  = :email,
                        `senha`  = :senha,
                        `nivel`  = :nivel,
                        `status` = :status
                
                    WHERE `codigo`   = :codigo";

            $comandoSQL = $conexao->prepare($sql);

            $comandoSQL->bindParam(':nome', $nome, PDO::PARAM_STR);
            $comandoSQL->bindParam(':email', $email, PDO::PARAM_STR);
            $comandoSQL->bindParam(':senha', $senha1, PDO::PARAM_STR);
            $comandoSQL->bindParam(':nivel', $nivel, PDO::PARAM_STR);
            $comandoSQL->bindParam(':status', $status, PDO::PARAM_STR);
            $comandoSQL->bindParam(':codigo', $codigo, PDO::PARAM_STR);
            
        }
        else{
            $sql = "UPDATE `usuarios` SET 
                        `nome` = :nome,
                        `email`  = :email,
                        `nivel`  = :nivel,
                        `status` = :status

                    WHERE `codigo`   = :codigo";

            $comandoSQL = $conexao->prepare($sql);

            $comandoSQL->bindParam(':nome', $nome, PDO::PARAM_STR);
            $comandoSQL->bindParam(':email', $email, PDO::PARAM_STR);
            $comandoSQL->bindParam(':nivel', $nivel, PDO::PARAM_STR);
            $comandoSQL->bindParam(':status', $status, PDO::PARAM_STR);
            $comandoSQL->bindParam(':codigo', $codigo, PDO::PARAM_STR);
        }
               $comandoSQL->execute();  

                if($comandoSQL->rowCount() == 1){
                    //echo "Usuário atualizado com sucesso!";

                    header("Location: ./view-login.php?status=altsucesso");

                    exit();
                    
                }else if($comandoSQL->rowCount() < 1){
                    header("Location: ./view-login.php?status=altNever");

                    exit();

                }
                

    }else{
        echo("Entre em contato com o Administrador!");
    }

    $conexao=null;