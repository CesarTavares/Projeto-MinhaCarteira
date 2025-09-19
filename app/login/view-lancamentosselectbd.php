<?php
require_once("../minhacarteira/app/login/_sessao.php");
require_once("../minhacarteira/app/login/_conexao/conexao.php");

try {
    // Obter o mês e ano atual
    $mesAtual = date('m');
    $anoAtual = date('Y');

    // Calcular o mês e ano anterior
    $mesAnterior = $mesAtual - 1;
    $anoAnterior = $anoAtual;

    // Ajustar o ano se o mês anterior for dezembro do ano passado
    if ($mesAnterior == 0) {
        $mesAnterior = 12;
        $anoAnterior = $anoAtual - 1;
    }

    // Comando SQL para buscar os registros do mês anterior
    $sql = "SELECT ld.*, c.descricao AS categoria_descricao, ca.descricao AS carteira_descricao
            FROM lancamentos_despesas ld
            LEFT JOIN categorias c ON ld.categoria = c.codigo
            LEFT JOIN carteiras ca ON ld.carteira = ca.codigo
            WHERE ld.codigo_usuario = :codigo
            AND MONTH(ld.data_pagamento) = :mes
            AND YEAR(ld.data_pagamento) = :ano
            ORDER BY ld.data_pagamento DESC";

    // Preparar a consulta
    $consulta = $conexao->prepare($sql);

    // Associar os parâmetros
    $consulta->bindValue(':codigo', $_SESSION['codigo'], PDO::PARAM_INT);
    $consulta->bindValue(':mes', $mesAnterior, PDO::PARAM_INT);
    $consulta->bindValue(':ano', $anoAnterior, PDO::PARAM_INT);

    // Executar a consulta
    $consulta->execute();

    // Obter os dados retornados
    $dados = $consulta->fetchAll(PDO::FETCH_ASSOC);

    // Calcular o total de registros lidos da tabela
    $totalRegistros = $consulta->rowCount();


} catch (PDOException $erro) {
    echo "Código do erro: " . $erro->getCode() . "<br>";
    echo "Descrição: " . $erro->getMessage();
}
?>
