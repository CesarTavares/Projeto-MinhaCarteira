<?php
    require_once("_sessao.php");
    //$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);

    require_once("./_conexao/conexao.php");

    try {
        $sql = "SELECT carteiras.*, tipos_carteiras.descricao as tipo_descricao 
                FROM carteiras 
                LEFT JOIN tipos_carteiras ON carteiras.codigo_tipo_carteira = tipos_carteiras.codigo 
                WHERE carteiras.codigo = :codigo";

        $comandoSQL = $conexao->prepare($sql);
        //pega o ':id' e passa para a variável $id
        $comandoSQL->bindParam(':codigo', $codigo);
        $comandoSQL->execute();
        //fetchAll forma uma matriz e organiza os dados
        $resultado = $comandoSQL->fetch(PDO::FETCH_ASSOC);

    } catch(PDOException $erro){
        echo("Erro: " .$erro->getMessage());
    }

    $conexao = null; //fechando a conexão como fazer para buscar a descrição do tipo_carteira da tabela carteira, esta mostrando só o codigo dela e quero a descição
?>

