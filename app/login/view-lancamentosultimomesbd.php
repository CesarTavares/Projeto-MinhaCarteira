<?php
    require_once("../minhacarteira/app/login/_sessao.php");

    require_once("../minhacarteira/app/login/_conexao/conexao.php");

    try {
        // Obter o mês e ano atual
        $mesAtual = date('m');
        $anoAtual = date('Y');
    
        // Subtrair 1 mês do mês atual para obter o mês passado
        $mesPassado = date('m', strtotime('-1 month'));
        $anoPassado = date('Y', strtotime('-1 month'));
    
        // Comando SQL para buscar os registros do mês passado
        $sql = "SELECT * FROM lancamentos_despesas
                WHERE MONTH(data_pagamento) = :mes AND YEAR(data_pagamento) = :ano AND codigo_usuario = :codigo
                ORDER BY data_pagamento DESC";
    
        // Preparar a consulta
        $consulta = $conexao->prepare($sql);
        $consulta->bindValue(':mes', $mesPassado);
        $consulta->bindValue(':ano', $anoPassado);
        $consulta->bindValue(':codigo', $_SESSION['codigo']);
    
        // Executar a consulta
        $consulta->execute();
    
        // Obter os dados retornados
        $dados = $consulta->fetchAll();
    
        // Calcular o total de registros lidos da tabela
        $totalRegistros = $consulta->rowCount();
    
    } catch (PDOException $erro) {
        echo "Código do erro: " . $erro->getCode() . "<br>";
        echo "Descrição: " . $erro->getMessage();
    }