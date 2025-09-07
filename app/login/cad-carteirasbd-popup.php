<?php
    header("Content-type: application/json");
    require_once("_sessao.php");
    //session_start();

    $response = array();
    $response['status'] = 'failed';

    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $dadosJson = json_decode(file_get_contents('php://input'), true);

        if($dadosJson == null)
        {
            $response['msg'] = 'Dados Inválidos';
            die(json_encode($response));
        }

        $descricao = $dadosJson['descricao'];
        $tipo = $dadosJson['tipo'];
        $codigo = $_SESSION['codigo'];

        try{
            require_once("./_conexao/conexao.php");

            // Verificar se já existe uma carteira com a mesma descrição para o usuário atual
$query_verificar_carteira = "SELECT COUNT(*) as total FROM carteiras WHERE descricao = :descricao AND codigo_usuario = :codigo_usuario";
$stmt_verificar_carteira = $conexao->prepare($query_verificar_carteira);
$stmt_verificar_carteira->bindParam(':descricao', $descricao);
$stmt_verificar_carteira->bindParam(':codigo_usuario', $codigo);
$stmt_verificar_carteira->execute();
$row_verificar_carteira = $stmt_verificar_carteira->fetch(PDO::FETCH_ASSOC);
$total_carteiras = $row_verificar_carteira['total'];

if ($total_carteiras > 0) {
    $response['status'] = 'failed';
    $response['mensagem'] = 'Essa Carteira já existe! Você pode alterá-la ou inserir uma nova';
    die(json_encode($response));
}


            $query_tipo_carteira = "SELECT codigo FROM tipos_carteiras WHERE descricao = :descricao";
            $stmt_tipo_carteira = $conexao->prepare($query_tipo_carteira);
            $stmt_tipo_carteira->execute(array(':descricao' => $tipo));

            //verifica se a consulta do tipo de carteira foi feita com sucesso
            if ($stmt_tipo_carteira->rowCount() > 0) {
                $row_tipo_carteira = $stmt_tipo_carteira->fetch(PDO::FETCH_ASSOC);
                $tipo_carteira_id = $row_tipo_carteira['codigo'];
            } else {
                $response['msg'] = 'Erro ao manipular os dados';
                die(json_encode($response));
            }
        }
        catch(PDOException $e)
        {
            $response['msg'] = 'Erro ao manipular os dados';
            die(json_encode($response));
        }

         // Preparar e executar a consulta SQL para inserir o lançamento de despesas
         $comandoSQL = $conexao->prepare("
                        
         INSERT INTO `carteiras` (
         `descricao`,
         `codigo_tipo_carteira`,
         `codigo_usuario`
         
            )
         VALUES (
                 :descricao,
                 :tipo,
                 :codigo_usuario 
             )
         ");

        //bind param
         $comandoSQL->bindParam(':descricao', $descricao);
         $comandoSQL->bindParam(':tipo', $tipo_carteira_id);
         $comandoSQL->bindParam(':codigo_usuario', $codigo);
         $comandoSQL->execute();

         if($comandoSQL->rowCount() <= 0)
         {
            $response['msg'] = 'Erro ao manipular os dados';
            die(json_encode($response));
         }
         else {
            $response['status'] = 'success';
            $response['mensagem'] = 'Carteira sssCadastrada Com Sucesso!';
            $response['dados']['codigo_usuario'] = $codigo;
            $response['dados']['codigo_carteira'] = $conexao->lastInsertId();


            
         }

        $conexao = null; // fechando a conexão com o banco
    }

    

    echo json_encode($response);   