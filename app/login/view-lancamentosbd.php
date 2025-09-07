<?php
require_once("./_sessao.php");

require_once("./_conexao/conexao.php");

try {
    // comando SQL para buscar todos os registros da tabela
    $sql = "SELECT ld.*, c.descricao AS categoria_descricao, ca.descricao AS carteira_descricao
            FROM lancamentos_despesas ld
            LEFT JOIN categorias c ON ld.categoria = c.codigo
            LEFT JOIN carteiras ca ON ld.carteira = ca.codigo
            WHERE ld.codigo_usuario = :codigo
            ORDER BY ld.data_vencimento DESC";

    // prepara a consulta
    $consulta = $conexao->prepare($sql);
    // vincula o valor do id do usuário (sessão) à consulta ao banco
    $consulta->bindValue(':codigo', $_SESSION['codigo']);
    // executa a consulta
    $consulta->execute();
    // obtém os dados retornados
    $dados = $consulta->fetchAll();
    // calcula o total de registros lidos da tabela
    $totalRegistros = $consulta->rowCount();
} catch (PDOException $erro) {
    echo ("Código do erro.: " . $erro->getCode());
    echo ("Descrição......: " . $erro->getMessage());
}
?>
 
