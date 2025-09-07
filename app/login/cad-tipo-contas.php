<?php
require_once("./_sessao.php");
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minha Carteira - Cadastro de Tipo de Carteira</title>
    <link rel="stylesheet" href="./css/estilo.css">
    <link rel="shortcut icon" href="../../logo_minha_carteira_ICO.ico" type="image/x-icon">
    <script src="./js/verifica_cadastros.js"></script>
</head>

<body>
    <div class="container">
        <h1>Minha Carteira - Controle Financeiro Pessoal</h1>
        <h1> Cadastro de Tipo de Carteira</h1>
        <?php include("./_menu-telas-consultas.php") ?>
    </div><br>

    <div class="centralizar-v">
    <form id="formulario" action="./cad-tipo-contasbd.php" method="POST" enctype="multipart/form-data">
            <div class="row-flex">
                <div class="col-10">
                    <div class="centralizar-h">

                        <div class="col-8" style="margin-top: 15px;">
                            <label for="descricao">Descrição do Tipo de Carteira</label>
                            <input type="text" name="descricao" placeholder="Descrição do Tipo de Carteira" value="<?php echo isset($_GET['descricao']) ? htmlspecialchars(urldecode($_GET['descricao'])) : ''; ?>">
                            <div class="mensagem-erro" style="color: red;"></div>
                        </div>
                <?php
                        // Verificar se há um erro na URL
                    if (isset($_GET['erro']) && $_GET['erro'] === 'lancamento-igual') {
                        // Abra uma div com uma classe para estilização CSS
                        echo '<div id="mensagem-erro" style="background-color: rgb(235, 124, 50);
                        color: rgb(248, 248, 248);
                        border: 1px solid rgb(235, 252, 6);
                        width: 500px;
                        margin: 0 auto; 
                        margin-top: 60px;            
                        height: 85px;
                        font-size: 29px;
                        border-radius: 7px;
                        text-align: center;
                        opacity: 1;
                        transition: opacity 4.3s ease;
                        position: absolute;
                        align-items: center;
                    ">Esse Tipo de Carteira já existe! Você pode alterá-lo ou inserir um novo.</div>';
                        }
                    ?>
                    </div>
                </div>
            </div>
            <br><br>
            <div class="containercad" style="margin-top: 35px;">
                <form>
                    <div class="voltar" style="background-color: dimgray">
                        <input type="submit" style="background-color:dimgray; border: 1px solid dimgray; font-size: 16px" value="Fechar" formaction="../../tela-inicial.php">
                    </div>
                </form>
                <div class="espaco-m"></div>
                <input type="submit" id="submit" value="G R A V A R" onclick="return verificarCampos()">
            </div>
    </div>
    </form>
</body>
        <Script>
            //Controle de mensagem para cadastro igual já realizado
                    setTimeout(function() {
                        var mensagemErro = document.getElementById("mensagem-erro");
                        if (mensagemErro) {
                            mensagemErro.style.opacity = "0";
                        }
                    }, 3500);
        </Script>
</html>