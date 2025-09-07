<?php
header("Content-type: application/json");
require_once("_sessao.php");
//session_start();

$response = array();
$response['status'] = 'failed';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dadosJson = json_decode(file_get_contents('php://input'), true);

    if ($dadosJson == null) {
        $response['msg'] = 'Dados Inválidos';
        die(json_encode($response));
    }

    $descricao = $dadosJson['descricao'];
    $tipo = $dadosJson['tipo'];
    $codigo = $_SESSION['codigo'];

    try {
        require_once("./_conexao/conexao.php");

        // Verificar se já existe uma categoria com a mesma descrição para o usuário atual
        $query_verificar_categoria = "SELECT COUNT(*) as total FROM categorias WHERE descricao = :descricao AND codigo_usuario = :codigo_usuario";
        $stmt_verificar_categoria = $conexao->prepare($query_verificar_categoria);
        $stmt_verificar_categoria->bindParam(':descricao', $descricao);
        $stmt_verificar_categoria->bindParam(':codigo_usuario', $codigo);
        $stmt_verificar_categoria->execute();
        $result_verificar_categoria = $stmt_verificar_categoria->fetch(PDO::FETCH_ASSOC);
        $total_categorias = $result_verificar_categoria['total'];

        if ($total_categorias > 0) {
            $response['mensagem'] = 'Essa Categoria já existe! Você pode alterá-la ou inserir uma nova';
            die(json_encode($response));
        }

        // Se não existir categoria com a mesma descrição, proceder com a inserção
        $comandoSQL = $conexao->prepare("
            INSERT INTO `categorias` (
                `descricao`,
                `tipo`,
                `codigo_usuario`
            ) VALUES (
                :descricao,
                :tipo,
                :codigo_usuario
            )
        ");
        // Vincular parâmetros
        $comandoSQL->bindParam(':descricao', $descricao);
        $comandoSQL->bindParam(':tipo', $tipo);
        $comandoSQL->bindParam(':codigo_usuario', $codigo);
        $comandoSQL->execute();

        if ($comandoSQL->rowCount() > 0) {
            $response['status'] = 'success';
            $response['mensagem'] = 'Categoria cadastrada com sucesso!';
            $response['dados']['codigo_usuario'] = $codigo;
            $response['dados']['codigo_categoria'] = $conexao->lastInsertId();
        } else {
            $response['mensagem'] = 'Erro ao manipular os dados';
            die(json_encode($response));
        }

        $conexao = null; // fechando a conexão com o banco
    } catch (PDOException $e) {
        $response['mensagem'] = 'Erro ao manipular os dados';
        die(json_encode($response));
    }
} else {
    $response['mensagem'] = 'Método de envio incorreto!';
    die(json_encode($response));
}

echo json_encode($response);
?>
