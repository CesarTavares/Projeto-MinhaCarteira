<?php
require_once("_sessao.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $descricaoCarteira = filter_input(INPUT_POST, "descricaoCarteira", FILTER_SANITIZE_SPECIAL_CHARS);
    $tipo = filter_input(INPUT_POST, "tipo", FILTER_SANITIZE_SPECIAL_CHARS);
    $codigo = $_SESSION['codigo'];

    require_once("./_conexao/conexao.php");

    try {
        // Consultar o código do tipo de carteira pelo nome
        $consultaTipoCarteira = $conexao->prepare("SELECT codigo FROM tipos_carteiras WHERE descricao = :tipo");
        $consultaTipoCarteira->bindParam(':tipo', $tipo);
        $consultaTipoCarteira->execute();

        // Verificar se o tipo de carteira foi encontrado
        if ($consultaTipoCarteira->rowCount() > 0) {
            $row = $consultaTipoCarteira->fetch(PDO::FETCH_ASSOC);
            $codigoTipoCarteira = $row['codigo'];

            // Verificar se já existe uma carteira igual cadastrada
            $consultaExistente = $conexao->prepare("
                SELECT COUNT(*) AS total
                FROM `carteiras`
                WHERE 
                    `descricao` = :descricao AND
                    `codigo_tipo_carteira` = :tipo AND
                    `codigo_usuario` = :codigo
            ");

            $consultaExistente->bindParam(':descricao', $descricaoCarteira);
            $consultaExistente->bindParam(':tipo', $codigoTipoCarteira);
            $consultaExistente->bindParam(':codigo', $codigo);
            $consultaExistente->execute();

            $resultadoExistente = $consultaExistente->fetch(PDO::FETCH_ASSOC);

            if ($resultadoExistente['total'] > 0) {
                // Carteira igual já existe, redirecionar para a página "cad-contas.php" com os valores dos campos na URL
                $descricaoCarteira = urlencode($descricaoCarteira);
                $tipo = urlencode($tipo);
                header('Location: ./cad-contas.php?erro=lancamento-igual&descricao=' . $descricaoCarteira . '&tipo=' . $tipo);
                exit();
            }

            // Inserir nova carteira
            $comandoSQL = $conexao->prepare("
                INSERT INTO `carteiras` (`descricao`, `codigo_tipo_carteira`, `codigo_usuario`)
                VALUES (:descricao, :tipo, :codigo_usuario)
            ");

            $resultado = $comandoSQL->execute(array(
                ':descricao' => $descricaoCarteira,
                ':tipo' => $codigoTipoCarteira,
                ':codigo_usuario' => $codigo
            ));

            if ($resultado) {
                header("Location: ./view-contas.php?status=sucesso");
                exit();
            } else {
                header("Location: ./view-contas.php?status=insucesso");
                exit();
            }
        } else {
            echo "Tipo de carteira não encontrado.";
        }
    } catch (PDOException $erro) {
        echo "Erro: " . $erro->getMessage();
    }
} else {
    echo "Entre em contato com o Administrador";
}
?>
