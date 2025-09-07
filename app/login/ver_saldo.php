<?php
function getSaldo($userId, $conexao)
{
    try
    {
        $conexao -> beginTransaction();

        $query = $conexao -> prepare("
        SELECT SUM(valor) as valor 
        FROM minhacarteira.lancamentos_receitas AS l
        WHERE codigo_usuario = :id AND situacao = 'Recebido'
        UNION ALL
        SELECT SUM(valor) * -1
        FROM minhacarteira.lancamentos_despesas AS d
        WHERE codigo_usuario = :id AND situacao = 'Pago';
    ");
    
    $query -> execute(array(
        ":id" => $userId
    ));
    
    if ($query -> rowCount() > 0) {
        $conexao->commit();
        $resultado = $query->fetchAll(PDO::FETCH_ASSOC);
    
        if ($resultado != null) {
            return $resultado[0]['valor'] + $resultado[1]['valor'];
        }
    }
        
        return 0;
    }
    catch(PDOException $e)
    {
        $conexao->rollBack();
        return null;
    }
}