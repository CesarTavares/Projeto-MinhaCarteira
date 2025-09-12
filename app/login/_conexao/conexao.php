<?php
// Conexão centralizada via PDO, com configurações seguras e padrão utf8mb4

// Evita recriar a conexão se já existir
if (!isset($conexao) || !($conexao instanceof PDO)) {
    $host = 'localhost';
    $db   = 'minhacarteira';
    $user = 'root';
    $pass = '1234';
    $charset = 'utf8mb4';

    $dsn = "mysql:host={$host};dbname={$db};charset={$charset}";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    try {
        $conexao = new PDO($dsn, $user, $pass, $options);
        // $conexao->exec('SET NAMES utf8mb4'); // normalmente desnecessário com charset no DSN
    } catch (PDOException $erro) {
        // Loga erro no servidor e não expõe detalhes sensíveis ao usuário final
        if (function_exists('error_log')) {
            error_log('[DB ERROR] ' . $erro->getMessage());
        }
        echo 'Entre em contato com o Administrador.';
        exit;
    }
}
















