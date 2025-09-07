<?php
require_once("./_sessao.php");
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minha Carteira - Atualização de Categoria</title>
    <link rel="stylesheet" href="./css/estilo.css">
    <link rel="shortcut icon" href="../../logo_minha_carteira_ICO.ico" type="image/x-icon">
</head>

<body>
    <div class="container">
    <h1>Minha Carteira - Controle Financeiro Pessoal</h1>
        <h1>Atualização de Categorias</h1>
        <?php include("./_menu-pagina-inicial.php"); ?>
    </div>

    <?php
    $codigo = filter_input(INPUT_GET, "codigo", FILTER_SANITIZE_NUMBER_INT);
    require_once("./alt-categorias-view.php");

    $status = filter_input(INPUT_GET, 'status', FILTER_SANITIZE_SPECIAL_CHARS);
    if (isset($status) && ($status == "altsucesso")) {
        echo '<div id="altsucesso">Categoria alterada com sucesso!</div>';
    }
    if (isset($status) && ($status == "altinsucesso")) {
        echo '<div id="altinsucesso">Alteração de Categoria não realizada!</div>';
    }
    ?>


    <br><br>
    <div class="centralizar-v">
        <form action="alt-categoriasbd.php" method="POST" enctype="multipart/form-data">

            <input type="hidden" name="codigo" value="<?= $codigo; ?>">
            <div class="row-flex">
                <div class="col" style="margin-top: 15px;">
                    <div class="centralizar-h">
                        <div class="col-8" style="margin-top: 15px;">
                            <label for="descricao">Descrição da Categoria</label>
                            <input type="text" name="descricao" id="descricao" placeholder="Descrição da Categoria" value="<?= $resultado['descricao']; ?>">
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
                    </div><br>


                    <div class="containercad" style="margin-top: 35px;">
                        <form>
                            <div class="voltar" style="background-color: dimgray">
                                <input type="submit" style="background-color:dimgray; border: 1px solid dimgray; font-size: 16px" value="Fechar" formaction="../../tela-inicial.php">
                            </div>
                        </form>
                        <div class="espaco-m"></div>
                        <a href="./cad-contas.php"><input type="submit" style="background-color: green; border: 1px solid green; font-size: 16px" value="G R A V A R"></a>
                    </div>

        </form>
    </div>
    <script>
        //função para controlar o tempo e forma que aparece a mensagem de inclusão com sucesso(verde)
        window.addEventListener('DOMContentLoaded', function() {
            var mensagem = document.getElementById('altsucesso');
            sucesso.style.display = 'block';

            setTimeout(function() {
                mensagem.style.opacity = 0;
                setTimeout(function() {
                    mensagem.style.display = 'none';
                }, 900);
            }, 5000);
        });

        //função para controlar o tempo e forma que aparece a mensagens de insucesso
        window.addEventListener('DOMContentLoaded', function() {
            var mensagem = document.getElementById('altinsucesso');
            excsucesso.style.display = 'block';

            setTimeout(function() {
                mensagem.style.opacity = 0;
                setTimeout(function() {
                    mensagem.style.display = 'none';
                }, 900);
            }, 4000);
        });
    </script>
</body>

</html>