<?php
require_once("_sessao.php");
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minha Carteira - Exclusão de Carteira</title>
    <link rel="stylesheet" href="./css/estilo.css">
    <link rel="shortcut icon" href="../../logo_minha_carteira_ICO.ico" type="image/x-icon">
</head>

<body>
    <div class="container">
    <h1>Minha Carteira - Controle Financeiro Pessoal</h1>
        <h1>Exclusão de Carteira</h1>
        <?php include("./_menu-telas-consultas.php"); ?>
    </div>

    <?php
    $codigo = filter_input(INPUT_GET, "codigo", FILTER_SANITIZE_NUMBER_INT);
    require_once("./exc-contas-view.php");
    ?>

    <div class="centralizar-v">
        <form action="exc-contasbd.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="codigo" value="<?php echo $codigo; ?>">
            <br><br>
            <div class="row-flex">
                <div class="col" style="margin-top: 15px;">
                    <label for="descricao">Descrição da Conta</label>
                    <input type="text" name="descricao" id="descricao" placeholder="Descrição da Carteira" value="<?= $resultado['descricao']; ?>" readonly>
                </div>
            </div>
            <br>
            <div class="row-flex">
                <div class="col">
                    <label for="text">Tipo</label>

                    <?php
                    // Verifica se $resultado está definido
                    if (isset($resultado)) {
                        // Exibe a descrição do tipo de carteira
                        echo '<input type="text" name="tipo" id="tipo" placeholder="Tipo da Conta" value="' . $resultado['tipo_descricao'] . '" readonly>';
                    } else {
                        // Trate o caso em que $resultado não está definido
                        echo "Erro: Não foi possível obter os dados da carteira.";
                    }
                    ?>
                </div>
            </div><br>

            <div class="containercad" style="margin-top: 35px;">
                <form>
                    <div class="voltar" style="background-color: dimgray">
                        <input type="submit" style="background-color:dimgray; border: 1px solid dimgray; font-size: 16px" value="Fechar" formaction="../../tela-inicial.php">
                    </div>

                </form>
                <div class="espaco-m"></div>
                <a href="./cad-contas.php"><input type="submit" style="background-color: red; border: 1px solid red; font-size: 16px" value="E X C L U I R"></a>
            </div>

        </form>
    </div>
</body>

</html>