<?php
/**
 * Helper para simplificar criação de páginas com template
 */
function renderPage($title, $content, $options = []) {
    // Configurações padrão
    $pageTitle = $title;
    $basePath = $options['basePath'] ?? './login';
    $additionalCSS = $options['css'] ?? [];
    $additionalJS = $options['js'] ?? [];
    $customStyles = $options['customStyles'] ?? '';
    $customJS = $options['customJS'] ?? '';
    
    // Se content é um arquivo, inclui ele
    if (is_file($content)) {
        $contentFile = $content;
        $content = null;
    }
    
    // Renderiza template
    require_once __DIR__ . '/templates/base.php';
}

/**
 * Helper para páginas que precisam de conexão com banco
 */
function renderPageWithDB($title, $content, $options = []) {
    require_once dirname(__DIR__) . "/vendor/autoload.php";
    require_once("./login/_sessao.php");
    
    // Disponibiliza conexão globalmente
    $connectionClass = 'App\Database\Connection';
    $GLOBALS['conexao'] = $connectionClass::get();
    
    renderPage($title, $content, $options);
}
?>