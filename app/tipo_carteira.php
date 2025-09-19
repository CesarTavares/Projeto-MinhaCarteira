<?php
require_once dirname(__DIR__) . "/vendor/autoload.php";
require_once("./login/_sessao.php");

use App\Database\Connection;


$conexao = Connection::get();
$codigo = $_SESSION['codigo'];

try {
    $stmt = $conexao->prepare("SELECT codigo, descricao FROM tipos_carteiras WHERE codigo_usuario = :usuario ORDER BY descricao");
    $stmt->bindValue(':usuario', $codigo, PDO::PARAM_INT);
    $stmt->execute();
    $tipos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $tipos = [];
    error_log('Erro ao buscar usuários: ' . $e->getMessage());
}

// Configuração + conteúdo tudo em um arquivo
$pageTitle = 'Cadastro de Tipo de Carteira';
$basePath = './login';
$contentFile = __DIR__ . '/templates/pages/tipocarteira_page.php';


// Renderiza template
require_once __DIR__ . '/templates/base.php';
?>