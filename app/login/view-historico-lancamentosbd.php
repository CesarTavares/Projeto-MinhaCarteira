<?php
    require_once("./_sessao.php");

    require_once("./_conexao/conexao.php");

    try{
        // comando SQL para buscar todos os registros da tabela
        $sql = "SELECT * FROM lancamentos_despesas";

        // executa o comando SQL no banco de dados
        $dadosSelecionados = $conexao->query($sql);

        // prepara os dados para serem vizualisados
        $dados = $dadosSelecionados->fetchAll();

        // calcula o total de registros lidos da tabela
        $totalRegistros = $dadosSelecionados->rowCount();

    }catch(PDOException $erro) {
        echo("Código dp erro.: ".$erro->getCode());
        echo("Descrição......: ".$erro->getMessage());
    }
