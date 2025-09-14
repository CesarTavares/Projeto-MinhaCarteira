<?php
    require_once("./_sessao.php");

    require_once("./_conexao/conexao.php");

    try{
        // comando SQL para buscar todos os registros da tabela
    $sql = "SELECT * FROM lancamentos_despesas";
    $stmt = $conexao->prepare($sql);
    $stmt->execute();
    $dados = $stmt->fetchAll();
    $totalRegistros = $stmt->rowCount();

    }catch(PDOException $erro) {
        echo("Código dp erro.: ".$erro->getCode());
        echo("Descrição......: ".$erro->getMessage());
    }
