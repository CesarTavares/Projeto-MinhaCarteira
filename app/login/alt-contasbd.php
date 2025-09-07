<?php
    require_once("./_sessao.php");

    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        $descricao = filter_input(INPUT_POST, "descricao",FILTER_SANITIZE_SPECIAL_CHARS);
        $tipo_carteira = filter_input(INPUT_POST, "tipo_carteira", FILTER_SANITIZE_NUMBER_INT);
        $codigo = filter_input(INPUT_POST, "codigo", FILTER_SANITIZE_NUMBER_INT);


        require_once("./_conexao/conexao.php");

        $sql = "UPDATE carteiras 
        SET descricao = :descricao,
            codigo_tipo_carteira = :codigo_tipo_carteira 
        WHERE codigo = :codigo";

$comandoSQL = $conexao->prepare($sql);

$comandoSQL->bindParam(':codigo', $codigo, PDO::PARAM_INT);
$comandoSQL->bindParam(':descricao', $descricao, PDO::PARAM_STR);
$comandoSQL->bindParam(':codigo_tipo_carteira', $tipo_carteira, PDO::PARAM_INT);

$comandoSQL->execute();

if ($comandoSQL->rowCount() == 1) {
    header("Location: ./view-contas.php?status=altsucesso");
    exit();
} else {
    // Redirecionamento para altinsucesso se não houver alterações
    header("Location: ./view-contas.php?status=altNever");
    exit();
}
}else{
header("Location: ./view-contas.php?status=altinsucesso");
}
$conexao=null;