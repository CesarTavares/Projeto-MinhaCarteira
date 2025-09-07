<?php
    require_once("./_sessao.php");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minha Carteira - Atualização de Usuário</title>
    <link rel="stylesheet" href="./css/estilo.css">
    <link rel="shortcut icon" href="../../logo_minha_carteira_ICO.ico" type="image/x-icon">
</head>
<body>
    <div class="container">
    <h1>Minha Carteira - Controle Financeiro Pessoal</h1>
        <h1>Atualização de Usuário</h1>
        <?php include("./_menu-pagina-inicial.php"); ?> 
    </div>

    <?php
    $codigo= filter_input(INPUT_GET, "codigo", FILTER_SANITIZE_NUMBER_INT);
    require_once("./alt-login-view.php");

                    $status = filter_input(INPUT_GET, 'status', FILTER_SANITIZE_SPECIAL_CHARS);
                    if(isset($status) && ($status=="altsucesso")){
                        echo '<div id="altsucesso">Usuário alterado com sucesso!</div>';
                    }
                    if(isset($status) && ($status=="altinsucesso")){
                        echo '<div id="altinsucesso">Alteração de Usuário não realizada!</div>';
                    }
    ?>

    

    <div class="centralizar-v">
    <form action="alt-loginbd.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="codigo" value="<?=$codigo;?>">
            <div class="row-flex">
                <div class="col" style="margin-top: 15px;">                    
                    <label for="nome" >Nome</label>
                    <input type="text" name="nome" id="nome"        
                     placeholder="Nome completo" value="<?=$resultado['nome'];?>">
                </div>                
            </div>

            <div class="row-flex">
                <div class="col">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" 
                    placeholder="Email institucional" value="<?=$resultado['email'];?>">
                </div>                
            </div>

            <div class="row-flex">
                <div class="col">
                    <label for="senha1">Senha</label>
                    <input type="password" name="senha1" id="senha1" 
                    placeholder="Senha com 8 digitos" 
                    value="********">
                    <p class="mens_erro">Senhas diferentes</p> 
                </div>

                <div class="col">
                    <label for="senha2">Confirmação de Senha</label>
                    <input type="password" name="senha2" id="senha2" 
                    placeholder="Mesma senha informada anteriormente"
                    value="********">
                    <p class="mens_erro">Senhas diferentes</p> 
                </div>

            </div>

            <div class="row-flex">
            <div class="col">
                <label for="nivel">Nível</label>
                        <select name="nivel" id="nivel">
                            <option value="Usuário" <?= ($resultado["nivel"] == "Usuário") ? "selected" : ""; ?>>Usuário</option>
                            <option value="Administrador" <?= ($resultado["nivel"] == "Administrador") ? "selected" : ""; ?>>Administrador</option>
                    </select>
            </div>

            <div class="col">
                <label for="status">Status</label>
                <select name="status" id="status">
                    <option value="Desativado" <?= ($resultado["status"] == "Desativado") ? "selected" : ""; ?>>Desativado</option>
                    <option value="Ativado" <?= ($resultado["status"] == "Ativado") ? "selected" : ""; ?>>Ativado</option>
                </select>
            </div>
            </div>

            <div class="containercad"  style="margin-top: 35px;"> 
                
                    <div class="voltar" style="background-color: dimgray">
                        <input type="submit" 
                            style="background-color:dimgray; border: 1px solid dimgray; font-size: 16px"
                            value="Fechar" formaction="../../tela-inicial.php">
                    </div>
                
                    <div class="espaco-m"></div>
                        <a href="./view-login.php"><input type="submit" 
                        style="background-color: green; border: 1px solid green; font-size: 16px" 
                        value="G R A V A R"></a>
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



        //Senha-------------------------------------------------------------
            let senha1 = document.querySelector('#senha1');
            let senha2 = document.querySelector('#senha2');
            let submit = document.querySelector('#submit');
            let mens_erro = document.querySelector('.mens_erro');

            function verifica(){
                if(senha1.value == senha2.value){
                    mens_erro.style.display = 'none';
                    submit.disable = false;
                }else{
                    mens_erro.style.display = 'block';
                    submit.disable = true;
                }
            }

            senha1.addEventListener('input', function(){
                verifica();
            }); 

            senha2.addEventListener('input', function(){
                verifica();
            }); 
            //senha----------------------------------------------------------------------

            //foto----------------------------------------------------------------------
            function preview(){
            let file_foto = document.querySelector('#foto').files[0];
            let img_imagem = document.querySelector('#imagem');

            //faz a leitura da imagem
            let visualizado = new FileReader();

            if(file_foto){
                
                // esse comando dispara o evento load da imagem
                //quando ela for lida completamente
                visualizado.readAsDataURL(file_foto);
            }else{
                img_imagem.src = "";
            }
                // evento de load quando disparada carrega
                // a imagem quando ela for lida completamente
                visualizado.onloadend = function(){
                    img_imagem.src = visualizado.result;
                }
            }
            //foto------------------------------------------------------------------------
    </script>
</body>
</html>