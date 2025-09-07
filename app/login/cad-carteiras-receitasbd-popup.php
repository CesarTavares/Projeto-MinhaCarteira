<?php
    require_once("_sessao.php");
    //session_start();

    
     if($_SERVER["REQUEST_METHOD"] == "POST"){
      
            //$id = filter_input(INPUT_POST, "id", FILTER_SANITIZE_NUMBER_INT);
            $descricao = filter_input(INPUT_POST, "descricao", FILTER_SANITIZE_SPECIAL_CHARS);
            $codigo = $_SESSION['codigo'];
           
     
                try{
                        require_once("./_conexao/conexao.php");

                        $comandoSQL = $conexao->prepare("
                        
                        INSERT INTO `carteiras` (
                        `descricao`,
                        `codigo_tipo_carteira`,
                        `codigo_usuario`
                        
                           )
                        VALUES (
                                :descricao,
                                :codigo_tipo_carteira,
                                :codigo_usuario
                                
                            )
                        ");

                        $comandoSQL->execute(array(
                            ':descricao'     => $descricao,
                            ':codigo_tipo_carteira' => $codigo_tipo_carteira,
                            ':codigo_usuario' => $codigo  
                        ));

                        if ($comandoSQL->rowCount() > 0) {
                           
                            // Redirecione para a página de cadastro de lançamento
                            header("Location: ./cad-lancamento-receita.php?status=sucesso_carteira&nova_carteira=" . urlencode($descricao));
                            exit();
                        } else {
                            header("Location: ./cad-lancamento-receita.php?status=altinsucesso");
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
    




