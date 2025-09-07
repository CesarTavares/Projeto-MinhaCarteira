<?php
    require_once("./_sessao.php");

    require_once("./_conexao/conexao.php");

    try {
        
        $sql = "SELECT * FROM lancamentos_receitas WHERE codigo = :codigo";

        $comandoSQL = $conexao->prepare($sql);
        //pega o ':id' e passa para a variável $id
        $comandoSQL->bindParam(':codigo', $codigo);
        $comandoSQL->execute();
        //fetchAll forma uma matriz e organiza os dados
        $resultado = $comandoSQL->fetch(PDO::FETCH_ASSOC);

    }catch(PDOException $erro){
        echo("Erro: " .$erro->getMessage());
        echo('deu erradoooooooooooooooo');


    }

    $conexao = null; //fechando a conexão