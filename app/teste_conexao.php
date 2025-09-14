<?php
require_once 'login/_conexao/conexao.php';

try {
    print("Teste de conexão: <br/>");
    $stmt = $conexao->query('SELECT 1 AS teste'); // query já executa
    print_r($stmt);
    print("<br/>");
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    echo '<br/>Conexão bem-sucedida! Valor do teste: ' . ($resultado['teste'] ?? '');
} catch (PDOException $e) {
    echo 'Erro na conexão: ' . $e->getMessage();
}
