<?php
require_once dirname(__DIR__) . "/vendor/autoload.php";
require_once("./login/_sessao.php");

use App\Database\Connection;

$conexao = Connection::get();
$pageTitle = 'Cadastro de Tipo de Carteira';
$basePath = './login';

// Buscar código do usuário da sessão
$codigo = $_SESSION['codigo'] ?? null;

if (!$codigo) {
    header('Location: ../index.php');
    exit;
}

// Processar mensagens de status
$status = $_GET['status'] ?? '';
$erro = $_GET['erro'] ?? '';
$sucesso = $_GET['sucesso'] ?? '';

$mensagem = '';
$tipoMensagem = '';

if ($sucesso === '1') {
    $mensagem = 'Tipo de carteira adicionado com sucesso!';
    $tipoMensagem = 'sucesso';
} elseif ($erro) {
    switch ($erro) {
        case 'descricao_vazia':
            $mensagem = 'Por favor, informe uma descrição válida.';
            break;
        case 'duplicado':
            $mensagem = 'Já existe um tipo de carteira com essa descrição.';
            break;
        case 'usuario_invalido':
            $mensagem = 'Usuário inválido.';
            break;
        case 'database':
            $mensagem = 'Erro no banco de dados. Tente novamente.';
            break;
        default:
            $mensagem = 'Ocorreu um erro inesperado.';
    }
    $tipoMensagem = 'erro';
}

// Buscar tipos de carteira existentes
try {
    $stmt = $conexao->prepare("SELECT codigo, descricao FROM tipos_carteiras WHERE codigo_usuario = :usuario ORDER BY descricao");
    $stmt->bindValue(':usuario', $codigo, PDO::PARAM_INT);
    $stmt->execute();
    $tipos = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log('Erro ao buscar tipos de carteira: ' . $e->getMessage());
    $tipos = [];
}

// Definir contentFile
$contentFile = __DIR__ . '/templates/pages/tipo_carteira-cad.php';

// CSS customizado
$customStyles = '
.centralizar-v {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 20px;
}

.centralizar-h {
    max-width: 800px;
    width: 100%;
}

.table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.table th, .table td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
}

.table th {
    background-color: #f2f2f2;
}

.btn {
    background-color: #007bff;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.btn:hover {
    background-color: #0056b3;
}

input[type="text"] {
    width: 300px;
    padding: 8px;
    margin: 5px 0;
    border: 1px solid #ddd;
    border-radius: 4px;
}

label {
    font-weight: bold;
}

.mensagem {
    padding: 10px;
    margin: 10px 0;
    border-radius: 5px;
    text-align: center;
}

.mensagem.sucesso {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.mensagem.erro {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}
';

// Renderizar template
require_once __DIR__ . '/templates/base.php';
?>