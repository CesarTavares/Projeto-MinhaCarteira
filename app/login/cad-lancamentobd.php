<?php
require_once("_sessao.php");
require_once("./_conexao/conexao.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $descricao = filter_input(INPUT_POST, "descricao", FILTER_SANITIZE_SPECIAL_CHARS);
        $valor = filter_input(INPUT_POST, "valor", FILTER_SANITIZE_SPECIAL_CHARS);
        $data_vencimento = filter_input(INPUT_POST, "data_vencimento", FILTER_SANITIZE_SPECIAL_CHARS);
        $data_pagamento = filter_input(INPUT_POST, "data_pagamento", FILTER_SANITIZE_SPECIAL_CHARS);
        $categoria_id = filter_input(INPUT_POST, "categoria", FILTER_SANITIZE_SPECIAL_CHARS);
        $carteira_id = filter_input(INPUT_POST, "carteira", FILTER_SANITIZE_SPECIAL_CHARS);
        $codigo = $_SESSION['codigo'];
        $situacao = filter_input(INPUT_POST, "situacao", FILTER_SANITIZE_SPECIAL_CHARS);        
        $parcelas = intval($_POST['parcelas']); // Obtém o número de parcelas como um inteiro

        //calcula o valor por parcelas
        $valorParcela = $valor / $parcelas;

        for ($i = 1; $i <= $parcelas; $i++) {
            //soma i-1 meses à data de vencimento
            $dataVenc = date('Y-m-d', strtotime("+".($i - 1)." month", strtotime($data_vencimento)));

            $descricaoParcela = $descricao . " ($i/$parcelas)";
            
           $query = "INSERT INTO lancamentos_despesas 
                    (descricao, valor, data_vencimento, data_pagamento, categoria, carteira, situacao, codigo_usuario) 
                  VALUES 
                    (:descricao, :valor, :data_vencimento, :data_pagamento, :categoria, :carteira, :situacao, :usuario)";
        
        $stmt = $conexao->prepare($query);
        $stmt->bindParam(':descricao', $descricaoParcela);
        $stmt->bindParam(':valor', $valorParcela);
        $stmt->bindParam(':data_vencimento', $dataVenc);
        $stmt->bindParam(':data_pagamento', $data_pagamento);
        $stmt->bindParam(':categoria', $categoria);
        $stmt->bindParam(':carteira', $carteira);
        $stmt->bindParam(':situacao', $situacao);
        $stmt->bindParam(':usuario', $_SESSION['codigo']);
        $stmt->execute();
    }

    header("Location: view-lancamento.php?status=sucesso");
    exit;

 

        $comprovante = ""; // Define um valor padrão vazio

        if (isset($_FILES['comprovante']) && $_FILES['comprovante']['error'] === UPLOAD_ERR_OK) {
            $uploadDirectory = './imagens/';
            $filename = $_FILES['comprovante']['name'];

            // Move o arquivo carregado para o diretório de uploads
            move_uploaded_file($_FILES['comprovante']['tmp_name'], $uploadDirectory . $filename);

            // Armazena o caminho do arquivo no banco de dados
            $comprovante = $uploadDirectory . $filename;
        }

        // Verificar se já existe um lançamento igual cadastrado
        $consultaExistente = $conexao->prepare("
            SELECT COUNT(*) AS total
            FROM `lancamentos_despesas`
            WHERE 
                `descricao` = :descricao AND
                `valor` = :valor AND
                `data_vencimento` = :data_vencimento AND
                `data_pagamento` = :data_pagamento AND
                `categoria` = :categoria AND
                `carteira` = :carteira AND
                `codigo_usuario` = :codigo
        ");

        // Bind dos parâmetros
        $consultaExistente->bindParam(':descricao', $descricao);
        $consultaExistente->bindParam(':valor', $valor);
        $consultaExistente->bindParam(':data_vencimento', $data_vencimento);
        $consultaExistente->bindParam(':data_pagamento', $data_pagamento);
        $consultaExistente->bindParam(':categoria', $categoria_id);
        $consultaExistente->bindParam(':carteira', $carteira_id);
        $consultaExistente->bindParam(':codigo', $codigo);

        // Executar a consulta
        $consultaExistente->execute();

     // Obter o resultado da consulta
$resultadoExistente = $consultaExistente->fetch(PDO::FETCH_ASSOC);

// Verificar se já existe um lançamento igual
if ($resultadoExistente['total'] > 0) {
    // Lançamento igual já existe, redirecionar para a página "cad-lancamento.php" com os valores dos campos na URL
    $descricao = urlencode($descricao);
    $valor = urlencode($valor);
    $data_vencimento = urlencode($data_vencimento); 
    $data_pagamento = urlencode($data_pagamento); // Adicione os outros campos aqui, se necessário
    header('Location: ./cad-lancamento.php?erro=lancamento-igual&descricao='.$descricao.'&valor='.$valor.'&data_vencimento='.$data_vencimento .'&data_pagamento='.$data_pagamento .'&categoria='.$categoria.'&carteira='.$carteira.'&situacao='.$situacao);
    exit();
}



        // Preparar e executar a consulta SQL para inserir o lançamento de despesas
        $comandoSQL = $conexao->prepare("
            INSERT INTO `lancamentos_despesas` (
                `descricao`,
                `valor`,
                `data_vencimento`,
                `data_pagamento`,
                `categoria`,
                `carteira`,
                `codigo_usuario`,
                `situacao`,
                `comprovante`
            )
            VALUES (
                :descricao,
                :valor,
                :data_vencimento,
                :data_pagamento,
                :categoria,
                :carteira,
                :codigo,
                :situacao,
                :comprovante
            )
        ");

        // Bind dos parâmetros
        $comandoSQL->bindParam(':descricao', $descricao);
        $comandoSQL->bindParam(':valor', $valor);
        $comandoSQL->bindParam(':data_vencimento', $data_vencimento);
        $comandoSQL->bindParam(':data_pagamento', $data_pagamento);
        $comandoSQL->bindParam(':categoria', $categoria_id);
        $comandoSQL->bindParam(':carteira', $carteira_id);
        $comandoSQL->bindParam(':codigo', $codigo);
        $comandoSQL->bindParam(':situacao', $situacao);
        $comandoSQL->bindParam(':comprovante', $comprovante);

        // Executar a consulta
        $insercaoBemSucedida = $comandoSQL->execute();

        // Verificar se a inserção foi bem-sucedida
        if ($insercaoBemSucedida) {
            // Inserção bem-sucedida
            // Redirecionar para a página de sucesso ou fazer outras ações necessárias
            header("Location: ./view-lancamento.php?status=sucesso");
            exit();
        } else {
            // Inserção falhou
            // Exibir mensagem de erro ou fazer outras ações necessárias
            header("Location: ./view-lancamento.php?status=insucesso");
            exit();
        }

        // Fechar conexão com o banco de dados
        $conexao = null;
    } catch (PDOException $erro) {
        // Tratar exceção, se houver
        echo "Ocorreu um erro: " . $erro->getMessage();
    }
} else {
    // Método de requisição incorreto
    echo "Método de requisição inválido. Entre em contato com o administrador.";
}
