<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Database\Connection;

try {
    echo 'Teste de conexão: <br/>';
    $pdo = Connection::get();
    $stmt = $pdo->query('SELECT 1 AS teste');
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    echo 'Conexão bem-sucedida! Valor do teste: ' . htmlspecialchars((string)($resultado['teste'] ?? ''), ENT_QUOTES, 'UTF-8');
} catch (PDOException $e) {
    echo 'Erro na conexão: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
}
