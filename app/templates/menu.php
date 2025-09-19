<?php
// Inclui cabeçalho e menu da aplicação se existir
$cabecalhoPath = $basePath ?? './login';
if (file_exists($cabecalhoPath . '/layout/cabecalho.php')) {
    require_once $cabecalhoPath . '/layout/cabecalho.php';
}

$menuPath = $basePath ?? '.';
if (file_exists($menuPath . '/_menu-pagina-inicial.php')) {
    include $menuPath . '/_menu-pagina-inicial.php';
}
?>