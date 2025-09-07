<?php
header("Content-type: application/json");
require_once("_sessao.php");

$response = array();
$response['status'] = 'failed';

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $dadosJson = json_decode(file_get_contents('php://input'), true);

    if($dadosJson == null || !isset($dadosJson['descricao']) || empty($dadosJson['descricao'])) {
        $response['msg'] = 'Dados Inválidos';
        die(json_encode($response));
    }

    $descricao = $dadosJson['descricao'];
    $codigo = $_SESSION['codigo'];

    try {
        require_once("./_conexao/conexao.php");

        // Verificar se já existe uma carteira com a mesma descrição para o usuário atual
        $query_verificar_carteira = "SELECT COUNT(*) as total FROM tipos_carteiras WHERE descricao = :descricao AND codigo_usuario = :codigo_usuario";
        $stmt_verificar_carteira = $conexao->prepare($query_verificar_carteira);
        $stmt_verificar_carteira->bindParam(':descricao', $descricao);
        $stmt_verificar_carteira->bindParam(':codigo_usuario', $codigo);
        $stmt_verificar_carteira->execute();
        $total_carteiras = $stmt_verificar_carteira->fetchColumn();

        if ($total_carteiras > 0) {
            $response['status'] = 'failed';
            $response['mensagem'] = 'Este Tipo de Carteira já existe! Você pode alterá-lo ou inserir um novo';
            die(json_encode($response));
        }

        // Preparar e executar a consulta SQL para inserir o tipo de carteira
        $comandoSQL = $conexao->prepare("
            INSERT INTO `tipos_carteiras` (
                `descricao`,
                `codigo_usuario`
            ) VALUES (
                :descricao,
                :codigo_usuario
            )
        ");

        // Bind params
        $comandoSQL->bindParam(':descricao', $descricao);
        $comandoSQL->bindParam(':codigo_usuario', $codigo);
        $comandoSQL->execute();

        if($comandoSQL->rowCount() <= 0) {
            $response['msg'] = 'Erro ao manipular os dados';
            die(json_encode($response));
        } else {
            $response['status'] = 'success';
            $response['mensagem'] = 'Tipo de Carteira Cadastrado Com Sucesso!';
            $response['dados']['codigo_usuario'] = $codigo;
            $response['dados']['codigo_carteira'] = $conexao->lastInsertId();
        }

    } catch(PDOException $e) {
        $response['msg'] = 'Erro ao manipular os dados';
        die(json_encode($response));
    }

    // Fechar conexão com o banco
    $conexao = null;
}

echo json_encode($response);
?>
