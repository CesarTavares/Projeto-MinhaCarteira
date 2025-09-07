<?php
    require_once("_sessao.php");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minha Carteira - Exclusão de Usuário</title>
    <link rel="stylesheet" href="./css/estilo.css">
    <link rel="shortcut icon" href="../../logo_minha_carteira_ICO.ico" type="image/x-icon">
</head>
<body>
    <div class="container">
    <h1>Minha Carteira - Controle Financeiro Pessoal</h1>
        <h1>Exclusão de Usuário</h1>
        <?php include("./_menu-telas-consultas.php"); ?> 
    </div>

    <?php
    $codigo= filter_input(INPUT_GET, "codigo", FILTER_SANITIZE_NUMBER_INT);
    require_once("./exc-login-view.php");
    ?>

    <div class="centralizar-v">
        <form action="exc-loginbd.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="codigo" value="<?php echo $codigo;?>">

             <div class="row-flex">
                <div class="col" style="margin-top: 15px;">                    
                    <label for="nome" >Nome Completo</label>
                    <input type="text" name="nome" id="nome"        
                     placeholder="Nome completo" value="<?=$resultado['nome'];?>"
                     readonly>
                </div>                
            </div>

            <div class="row-flex">
                <div class="col">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" 
                    placeholder="Melhor E-mail" value="<?=$resultado['email'];?>"
                    readonly>
                </div>                
            </div>

            


            <div class="containercad"  style="margin-top: 35px;"> 
                <form>
                    <div class="voltar" style="background-color: dimgray">
                        <input type="submit" 
                            style="background-color:dimgray; border: 1px solid dimgray; font-size: 16px"
                            value="Fechar" formaction="../../tela-inicial.php">
                    </div>
                </form>
                    <div class="espaco-m"></div>
                    <input type="submit" style="background-color: red; border: 1px solid red" value=" E X C L U I R ">
            </div>         

        </form>
    </div>
</body>
</html>