<?php
    require_once("_sessao.php");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minha Carteira - Atualização do Tipo de Carteira de Pagamento</title>
    <link rel="stylesheet" href="./css/estilo.css">
    <link rel="shortcut icon" href="../../logo_minha_carteira_ICO.ico" type="image/x-icon">
</head>
<body>
    <div class="container">
    <h1>Minha Carteira - Controle Financeiro Pessoal</h1>
        <h1>Atualização do Tipo de Carteira de Pagamento</h1>
        <?php include("./_menu-telas-consultas.php"); ?> 
    </div>

    <?php
    $codigo= filter_input(INPUT_GET, "codigo", FILTER_SANITIZE_NUMBER_INT);
    require_once("./alt-tipo-contas-view.php");
    ?>

    <div class="centralizar-v">
        <form action="alt-tipo-contasbd.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="codigo" value="<?php echo $codigo;?>">

                    <div class="row-flex">
                        <div class="col" style="margin-top: 15px;">                    
                            <label for="descricao">Descrição do Tipo de Carteira</label>
                            <input type="text" name="descricao" id="descricao"        
                            placeholder="Descriçao do Tipo da Carteira" value="<?=$resultado['descricao'];?>">
                        </div>                
                    </div>            

            <div class="containercad"  style="margin-top: 35px;"> 
                <form>
                    <div class="voltar" style="background-color: dimgray">
                        <input type="submit" 
                            style="background-color:dimgray; border: 1px solid dimgray; font-size: 16px"
                            value="Fechar" formaction="../login/view-tipo-contas.php">
                    </div>
                </form>
                    <div class="espaco-m"></div>
                        <a href="./view-contas.php"><input type="submit" 
                        style="background-color: green; border: 1px solid green; font-size: 16px" 
                        value="G R A V A R"></a>
            </div>    
        </form>
    </div>
    <script>
        
    </script>
</body>
</html>