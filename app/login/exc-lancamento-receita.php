<?php
    require_once("_sessao.php");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minha Carteira - Exclusão Lançamento de Receita</title>
    <link rel="stylesheet" href="./css/estilo.css">
    <link rel="shortcut icon" href="../../logo_minha_carteira_ICO.ico" type="image/x-icon">
</head>
<body>
    <div class="container">
    <h1>Minha Carteira - Controle Financeiro Pessoal</h1>
        <h1>Exclusão de Lançamento de Receita</h1>
        <?php include("./_menu-telas-consultas.php"); ?> 
    </div>
    <?php
    $codigo= filter_input(INPUT_GET, "codigo", FILTER_SANITIZE_NUMBER_INT);
    require_once("./exc-lancamento-view-receita.php");
    ?>

    <div class="centralizar-v">
        <form action="exc-lancamentobd-receita.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="codigo" value="<?php echo $codigo;?>">
        
            <div class="row-flex">
                <div class="col" style="margin-top: 15px;">                    
                    <label for="data_credito" >Data do Crédito</label>
                    <input type="date"  style="padding:15px; font-size: 20px;" name="data_credito" id="data_credito"        
                     placeholder="Data do Lançamento" value="<?=$resultado['data_credito'];?>"
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
                     placeholder="Descrição da Conta" value="<?=$resultado['descricao'];?>"
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

            <div class="containercad"  style="margin-top: 35px;"> 
                <div class="voltar" style="background-color: dimgray">
                    <input type="submit" 
                        style="background-color:dimgray; border: 1px solid dimgray; font-size: 16px"
                        value="Fechar" formaction="./view-lancamento.php">
                </div>
                <div class="espaco-m"></div>
                <a href="./cad-contas.php"><input type="submit" 
                    style="background-color: red; border: 1px solid red; font-size: 16px" 
                    value="E X C L U I R"></a>
            </div>         
        </form>
    </div>
</body>
</html>
