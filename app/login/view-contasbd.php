<?php
require_once("./_sessao.php");

require_once("./_conexao/conexao.php");

try {
    //esse comadno SQL busca todos os registros da tabela, incluindo a descrição do tipo de carteira
    $sql = "
        SELECT c.*, t.descricao AS codigo_tipo_carteira
        FROM carteiras c
        LEFT JOIN tipos_carteiras t ON c.codigo_tipo_carteira = t.codigo
        WHERE c.codigo_usuario = :codigo
    ";

    // Prepara a consulta
    $consulta = $conexao->prepare($sql);
    // Vincula o valor do ID do usuário (sessão) à consulta ao banco
    $consulta->bindValue(':codigo', $_SESSION['codigo']);
    // Executa a consulta
    $consulta->execute();
    // Obtém os dados retornados
    $dados = $consulta->fetchAll();
    // Calcula o total de registros lidos da tabela
    $totalRegistros = $consulta->rowCount();
} catch (PDOException $erro) {
    echo("Código do erro: ".$erro->getCode());
    echo("Descrição: ".$erro->getMessage());
}
?>
