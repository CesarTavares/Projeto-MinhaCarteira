<?php
    require_once("./_sessao.php");

    require_once("./_conexao/conexao.php");

    try{
        // comando SQL para buscar todos os registros da tabela
        $sql = "SELECT * FROM categorias where codigo_usuario = :codigo";

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

    }catch(PDOException $erro) {
        echo("Código dp erro.: ".$erro->getCode());
        echo("Descrição......: ".$erro->getMessage());
    }
