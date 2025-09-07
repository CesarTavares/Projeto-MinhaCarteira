<?php
    require_once("_sessao.php");

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minha Carteira - Exclusão Lançamento de Despesa</title>
    <link rel="stylesheet" href="./css/estilo.css">
    <link rel="shortcut icon" href="../../logo_minha_carteira_ICO.ico" type="image/x-icon">
</head>
<body>
    <div class="container">
    <h1>Minha Carteira - Controle Financeiro Pessoal</h1>
        <h1>Exclusão de Lançamento de Despesa</h1>
        <?php include("./_menu-telas-consultas.php"); ?> 
    </div>
    <?php
    $codigo= filter_input(INPUT_GET, "codigo", FILTER_SANITIZE_NUMBER_INT);
    require_once("./exc-lancamento-view.php");
    ?>

    <div class="centralizar-v">
    <form action="exc-lancamentobd.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="codigo" value="<?php echo $codigo;?>">

            
             <div class="row-flex">
                <div class="col" style="margin-top: 15px;">                    
                    <label for="data_pagamento" >Data do Pagamento</label>
                    <input type="date"  style="padding:15px; font-size: 20px;" name="data_pagamento" id="data_pagamento"        
                     placeholder="Data do Lançamento" value="<?=$resultado['data_pagamento'];?>"
                     readonly>
                </div>   

                <div class="col" style="margin-top: 15px;">                    
                    <label for="data_vencimento" >Data de Vencimento</label>
                    <input type="date"  style="padding:15px; font-size: 20px;" name="data_vencimento" id="data_vencimento"        
                     placeholder="Data do Lançamento" value="<?=$resultado['data_vencimento'];?>"
                     readonly>
                </div>                
            </div>
            

            <div class="row-flex">
                <div class="col">
                    <label for="text">Descrição</label>
                    <input type="text" name="descricao" id="descricao" 
                    placeholder="Descrição do Lançamento" value="<?=$resultado['descricao'];?>"
                    readonly>
                </div>                
            </div>

            <div class="row-flex">
                <div class="col">
                    <label for="valor">Valor</label>
                    <input type="text" name="valor" id="valor" 
                    placeholder="Valor da Lançamento" value="<?=$resultado['valor'];?>"
                    readonly>
                </div>                
            </div>

            <div class="row-flex">
                <div class="col" style="margin-top: 15px;">                    
                    <label for="categoria">Categoria</label>
                    <input type="text" name="categoria" id="categoria"        
                     placeholder="Descrição da Categoria" value="<?=$resultado['descricao'];?>"
                     readonly>
                </div>                
            </div>

            <div class="row-flex">
                <div class="col" style="margin-top: 15px;">                    
                    <label for="conta">Conta Bancária</label>
                    <input type="text" name="conta" id="conta"        
                     placeholder="Descrição da Carterua" value="<?=$resultado['descricao'];?>"
                     readonly>
                </div>                
            </div>

            <div class="row-flex">
                <div class="col" style="margin-top: 15px;">                    
                    <label for="tipo">Pago</label>
                    <input type="text" name="pago" id="pago"        
                     placeholder="Indica se foi pago ou não." value="<?=$resultado['situacao'];?>"
                     readonly>
                </div>                
            </div>

            <label for="">Comprovante Atual:</label><br>

<?php
    try {
        // Estabelecer a conexão com o banco de dados
        $pdo = new PDO("mysql:host=localhost;dbname=minhacarteira", "root", "");

        // Configurar o PDO para lançar exceções em caso de erros
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Verifique se a variável $codigo está definida e é um número inteiro válido
            if (isset($_GET['codigo']) && is_numeric($_GET['codigo'])) {
            $codigo = $_GET['codigo'];

            // Consulta SQL para recuperar o caminho do comprovante com base no código
            $consultaComprovante = "SELECT comprovante FROM lancamentos_despesas WHERE codigo = :codigo";

        // Preparar a consulta
        $stmt = $pdo->prepare($consultaComprovante);
        $stmt->bindParam(':codigo', $codigo, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result && !empty($result['comprovante'])) {
            $comprovantePath = $result['comprovante'];

            // Verifique o tipo de arquivo e exiba-o
            $fileExtension = pathinfo($comprovantePath, PATHINFO_EXTENSION);

            

        if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'bmp'])) {
                // É uma imagem, use a tag <img> para exibi-la com a classe CSS
                echo '<img src="' . $comprovantePath . '" alt="Comprovante" class="comprovante-img">';
            } elseif ($fileExtension === 'pdf') {
                // É um arquivo PDF, use o elemento <embed> para exibi-lo
                echo '<embed src="' . $comprovantePath . '" type="application/pdf" width="100%" height="500px">';
            } else {
                // Tipo de arquivo não suportado, exiba uma mensagem de erro
                echo 'Tipo de arquivo não suportado.';
            }
        } else {
            echo 'Nenhum comprovante disponível para este registro.';
        }
    } else {
        echo 'Código inválido ou não fornecido.';
    }
} catch (PDOException $e) {
    echo 'Erro ao recuperar o comprovante: ' . $e->getMessage();
}
?>





            <div class="containercad"  style="margin-top: 35px;"> 
                <form>
                    <div class="voltar" style="background-color: dimgray">
                        <input type="submit" 
                            style="background-color:dimgray; border: 1px solid dimgray; font-size: 16px"
                            value="Fechar" formaction="./view-lancamento.php">
                    </div>
                </form>
                    <div class="espaco-m"></div>
                        <a href="./cad-contas.php"><input type="submit" 
                        style="background-color: red; border: 1px solid red; font-size: 16px" 
                        value="E X C L U I R"></a>
            </div>         
        </form>
    </div>
</body>
</html>