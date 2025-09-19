<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Minha Carteira' ?></title>
    <link rel="stylesheet" href="<?= $basePath ?? './login' ?>/css/estilo.css">
    <link rel="shortcut icon" href="<?= $basePath ?? './login' ?>/imagens/logo_minha_carteira_ICO.ico" type="image/x-icon">
    
    <?php if (isset($additionalCSS)): ?>
        <?php foreach ($additionalCSS as $css): ?>
            <link rel="stylesheet" href="<?= $css ?>">
        <?php endforeach; ?>
    <?php endif; ?>
    
    <?php if (isset($customStyles)): ?>
        <style><?= $customStyles ?></style>
    <?php endif; ?>
</head>
<body>