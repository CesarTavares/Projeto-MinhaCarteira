<?php
require_once("_sessao.php");
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minha Carteira - Exclusão de Categoria</title>
    <link rel="stylesheet" href="./css/estilo.css">
    <link rel="shortcut icon" href="../../logo_minha_carteira_ICO.ico" type="image/x-icon">
</head>

<body>
    <div class="container">
    <h1>Minha Carteira - Controle Financeiro Pessoal</h1>
        <h1>Exclusão de Categoria</h1>
        <?php include("./_menu-telas-consultas.php"); ?>
    </div>

    <?php
    $codigo = filter_input(INPUT_GET, "codigo", FILTER_SANITIZE_NUMBER_INT);
    require_once("./exc-categorias-view.php");
    ?>

    <div class="centralizar-v"><br>
        <form action="exc-categoriasbd.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="codigo" value="<?php echo $codigo; ?>">
            <div class="row-flex">
                <div class="col" style="margin-top: 15px;">
                    <div class="centralizar-h">
                        <div class="col-8" style="margin-top: 15px;">
                            <label for="descricao">Descrição da Categoria</label>
                            <input type="text" name="descricao" id="descricao" placeholder="Descrição da Conta" value="<?= $resultado['descricao']; ?>" readonly>
                        </div>
                    </div><br><br>

                    <div class="centralizar-h">
                        <div class="col-8">
                            <label for="tipo">Tipo</label>
                            <select name="tipo" id="tipo">
                                <option value="Despesa" <?= ($resultado["tipo"] == "Despesa") ? "selected" : ""; ?>>Despesa</option>
                                <option value="Receita" <?= ($resultado["tipo"] == "Receita") ? "selected" : ""; ?>>Receita</option>
                            </select>
                        </div>
                    </div><br><br>

                    <div class="containercad" style="margin-top: 35px;">
                        <form>
                            <div class="voltar" style="background-color: dimgray">
                                <input type="submit" style="background-color:dimgray; border: 1px solid dimgray; font-size: 16px" value="Fechar" formaction="../../tela-inicial.php">
                            </div>
                        </form>
                        <div class="espaco-m"></div>
                        <a href="./cad-contas.php"><input type="submit" style="background-color: red; border: 1px solid red; font-size: 16px" value="E X C L U I R"></a>
                    </div>


                    <!-- <div class="row-flex">
                <div class="col centralizar-h"  style="margin-top: 35px;">
                    <input type="reset" value="Voltar"
                    onclick="javascript:history.go(-1)"> 

                    <div class="espaco-h"></div>
                    <input type="submit" style="background-color: red; border: 1px solid red" value=" E X C L U I R ">
                </div>
            </div> -->
        </form>
    </div>
</body>

</html>