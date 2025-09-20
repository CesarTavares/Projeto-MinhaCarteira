<?php
require_once dirname(__DIR__) . "/vendor/autoload.php";
require_once("./login/_sessao.php");

use App\Database\Connection;


$conexao = Connection::get();
$codigo = $_SESSION['codigo'];


// Configuração + conteúdo tudo em um arquivo
$pageTitle = 'Cadastro de Contas';
$basePath = './login';
$contentFile = __DIR__ . '/templates/pages/cad-contas.php';

// Renderiza template
require_once __DIR__ . '/templates/base.php';
?>