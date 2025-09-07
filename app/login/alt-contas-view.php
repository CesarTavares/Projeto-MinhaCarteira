<?php
require_once("./_sessao.php");
require_once("../login/_conexao/conexao.php");

try {
    $codigo = filter_input(INPUT_GET, "codigo", FILTER_SANITIZE_NUMBER_INT);

    $sql = "SELECT c.*, tc.descricao as tipo_carteira
            FROM carteiras c
            LEFT JOIN tipos_carteiras tc ON c.codigo_tipo_carteira = tc.codigo
            WHERE c.codigo = :codigo";

    $comandoSQL = $conexao->prepare($sql);
    $comandoSQL->bindParam(':codigo', $codigo);
    $comandoSQL->execute();
    $resultado = $comandoSQL->fetch(PDO::FETCH_ASSOC);

    // Agora, $resultado['tipo_carteira_descricao'] conterá a descrição do tipo de carteira associado.

} catch (PDOException $erro) {
    echo("Erro: " . $erro->getMessage());
    echo('deu erradoooooooooooooooo');
}

$conexao = null; // fechando a conexão
?>
