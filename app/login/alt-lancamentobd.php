
<?php
require_once("./_sessao.php");
require_once("./_conexao/conexao.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $data_pagamento = filter_input(INPUT_POST, "data_pagamento", FILTER_SANITIZE_SPECIAL_CHARS);
        $descricao = filter_input(INPUT_POST, "descricao", FILTER_SANITIZE_SPECIAL_CHARS);
        $valor = filter_input(INPUT_POST, "valor", FILTER_SANITIZE_SPECIAL_CHARS);
        $data_vencimento = filter_input(INPUT_POST, "data_vencimento", FILTER_SANITIZE_SPECIAL_CHARS);
        $categoria = filter_input(INPUT_POST, "categoria", FILTER_SANITIZE_NUMBER_INT);
        $carteira = filter_input(INPUT_POST, "carteira", FILTER_SANITIZE_NUMBER_INT);
        $codigo_usuario = $_SESSION['codigo'];
        $situacao = filter_input(INPUT_POST, "situacao", FILTER_SANITIZE_SPECIAL_CHARS);
        $codigo = filter_input(INPUT_POST, "codigo", FILTER_SANITIZE_SPECIAL_CHARS);
        //Inicia como null
        //$comprovante = null;

        //verifica se o arquivo foi enviado com sucesso
        if (isset($_FILES['comprovante']) && $_FILES['comprovante']['error'] === UPLOAD_ERR_OK) {
            //pasta onde armazena o arquivo de comprovante
            $uploadDirectory = './imagens/';
            $filename = $_FILES['comprovante']['name'];

            //caminho completo para o arquivo no servidor
            $comprovantePath = $uploadDirectory . $filename;

            //move o arquivo para o diretório de uploads
            if (move_uploaded_file($_FILES['comprovante']['tmp_name'], $comprovantePath)) {
                $comprovante = $comprovantePath;
            } else {
                echo "Falha ao mover o arquivo para o diretório de upload.";
            }
        } else {
            //se nenhum novo comprovante foi enviado pelo usuário, mantem o comprovante existente no banco de dados
            $consultaComprovante = "SELECT comprovante FROM lancamentos_despesas WHERE codigo = :codigo";
            $stmt = $conexao->prepare($consultaComprovante);
            $stmt->bindParam(':codigo', $codigo, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result && !empty($result['comprovante'])) {
                $comprovante = $result['comprovante'];
            }
        }

        require_once("./_conexao/conexao.php");

        $sql = "UPDATE `lancamentos_despesas` SET 
                        `data_pagamento` = :data_pagamento,
                        `descricao` = :descricao,
                        `valor` = :valor,
                        `data_vencimento` = :data_vencimento,
                        `categoria` = :categoria,
                        `carteira` = :carteira,
                        `codigo_usuario` = :codigo_usuario,
                        `situacao` = :situacao,
                        `comprovante` = :comprovante 

                    WHERE `codigo` = :codigo";

        $comandoSQL = $conexao->prepare($sql);

        $comandoSQL->bindParam(':data_pagamento', $data_pagamento, PDO::PARAM_STR);
        $comandoSQL->bindParam(':descricao', $descricao, PDO::PARAM_STR);
        $comandoSQL->bindParam(':valor', $valor, PDO::PARAM_STR);
        $comandoSQL->bindParam(':data_vencimento', $data_vencimento, PDO::PARAM_STR);
        $comandoSQL->bindParam(':categoria', $categoria, PDO::PARAM_STR);
        $comandoSQL->bindParam(':carteira', $carteira, PDO::PARAM_STR);
        $comandoSQL->bindParam(':codigo_usuario', $codigo_usuario, PDO::PARAM_STR);
        $comandoSQL->bindParam(':situacao', $situacao, PDO::PARAM_STR);
        $comandoSQL->bindParam(':codigo', $codigo, PDO::PARAM_STR);
        $comandoSQL->bindParam(':comprovante', $comprovante, PDO::PARAM_STR);

        if ($comandoSQL->execute()) {
            if ($comandoSQL->rowCount() == 1) {
                header("Location: ./view-lancamento.php?status=altsucesso");
            } else {
                header("Location: ./view-lancamento.php?status=altNever");
            }
        } else {
            // Exibir informações de erro
            header("Location: ./view-lancamento.php?status=altinsucesso");
        }
    } catch (Exception $e) {
        echo "Erro: " . $e->getMessage();
    } finally {
        $conexao = null;
    }
} else {
    echo "Entre em contato com o Administrador!";
}
?>

