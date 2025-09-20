<?php
/**
 * Template base para páginas da aplicação
 * 
 * Variáveis esperadas:
 * - $pageTitle: título da página
 * - $basePath: caminho base para assets
 * - $contentFile: arquivo PHP com o conteúdo específico da página
 * - $additionalCSS: array de arquivos CSS adicionais
 * - $additionalJS: array de arquivos JS adicionais
 * - $customStyles: CSS customizado inline
 * - $customJS: JavaScript customizado inline
 */

// Define valores padrão se não foram fornecidos
$pageTitle = $pageTitle ?? 'Minha Carteira';
$basePath = $basePath ?? './login';

// Inclui header
require_once __DIR__ . '/header.php';

// Inclui menu
require_once __DIR__ . '/menu.php';
?>

<!-- Início do conteúdo específico da página -->
<main class="main-content">
    <?php 
    if (isset($contentFile) && file_exists($contentFile)) {
        require_once $contentFile;
    } elseif (isset($content)) {
        echo $content;
    } else {
        echo '<div class="centralizar-r"><p>Conteúdo não encontrado.</p></div>';
    }
    ?>
</main>
<!-- Fim do conteúdo específico da página -->

<?php
// Inclui footer
require_once __DIR__ . '/footer.php';
?>