<?php
//require_once("_sessao.php");
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minha Carteira - Cadastro de Novo Usuário</title>
    <link rel="stylesheet" href="./css/estilo.css">
    <link rel="shortcut icon" href="../../logo_minha_carteira_ICO.ico" type="image/x-icon">
    <script src="./js/verifica_cadastros.js"></script>
</head>

<body>
    <div class="container">
        <h1>Minha Carteira - Controle Financeiro Pessoal</h1>
        <h1>Cadastro de Novo Usuário</h1>
    </div>

    <?php
    $selectedNivel = isset($_POST['nivel']) ? $_POST['nivel'] : '';
    $selectedStatus = isset($_POST['status']) ? $_POST['status'] : '';

    if (isset($_GET['nova_categoria'])) {
        $novaCategoria = htmlspecialchars($_GET['nova_categoria']);
        $selectedNivel = $novaCategoria;
    }
    // Verificar se há um erro na URL
    if (isset($_GET['erro']) && $_GET['erro'] === 'lancamento-igual') {
        // Abra uma div com uma classe para estilização CSS
        echo '<div id="mensagem-erro" style="background-color: rgb(235, 124, 50);
                                            color: rgb(248, 248, 248);
                                            border: 1px solid rgb(235, 252, 6);
                                            width: 650px;
                                            margin: 0 auto; 
                                            margin-top: 60px;            
                                            height: 150px;
                                            font-size: 29px;
                                            border-radius: 7px;
                                            text-align: center;
                                            opacity: 1;
                                            transition: opacity 4.3s ease;
                                            position: absolute;
                                            margin-left: 750px;
                                            align-items: center;
                                        ">Este Usuário já existe! Você não pode inserir novo usuário com mesmo E-mail e Nome de um já existente. Tente novamente!.</div>';
    }
    ?>
    <br>

    <div class="containercad">
        <form id="formulario" action="cad-novo-loginbd.php" method="POST" enctype="multipart/form-data">
            <div class="row-flex">
                <div class="col" style="margin-top: 15px;">
                    <label for="nome">Nome Completo</label>
                    <input type="text" name="nome" id="nome" placeholder="Digite seu Nome completo" value="<?php echo isset($_GET['nome']) ? htmlspecialchars(urldecode($_GET['nome'])) : ''; ?>">
                    <div class="mensagem-erro" style="color: red;"></div>
                </div>
            </div><br>

            <div class="row-flex">
                <div class="col">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" placeholder="Digite seu Melhor Email" value="<?php echo isset($_GET['email']) ? htmlspecialchars(urldecode($_GET['email'])) : ''; ?>">
                    <div class="mensagem-erro" style="color: red;"></div>
                </div>
            </div><br><br>

            <div class="row-flex">
                <div class="col">
                    <label for="senha1">Senha</label>
                    <input type="password" name="senha1" id="senha1" placeholder="Senha com 8 digitos" value="<?php echo isset($_GET['senha1']) ? htmlspecialchars(urldecode($_GET['senha1'])) : ''; ?>">
                    <p class="mens_erro">Senhas diferentes</p>
                    <div class="mensagem-erro" style="color: red;"></div>
                </div><br>
                <div class="espaco-m"></div>
                <div class="col">
                    <label for="senha2">Confirmação de Senha</label>
                    <input type="password" name="senha2" id="senha2" placeholder="Mesma senha informada anteriormente" value="<?php echo isset($_GET['senha2']) ? htmlspecialchars(urldecode($_GET['senha2'])) : ''; ?>">
                    <p class="mens_erro">Senhas diferentes</p>
                    <div class="mensagem-erro" style="color: red;"></div>
                </div>
            </div><br><br>

            <div class="row-flex">
                <div class="col">
                    <label for="nivel">Nível</label>
                    <select name="nivel" id="nivel">
                        <option value="Usuário" selected>Usuário</option>
                    </select>
                    <div class="mensagem-erro" style="color: red;"></div>
                </div>
                <div class="espaco-m"></div>
                <div class="col">
                    <label for="status">Status</label>
                    <select name="status" id="status">
                        <option value="Desativado" selected>Ativo</option>
                    </select>
                    </select>
                    <div class="mensagem-erro" style="color: red;"></div>
                </div>
            </div>

            <div class="containercad" style="margin-top: 35px;">
                <form>
                    <div class="voltar" style="background-color: dimgray">
                        <input type="submit" style="background-color:dimgray; border: 1px solid dimgray; font-size: 16px" value="Fechar" formaction="../../index.php">
                    </div>
                </form>
                <div class="espaco-m"></div>
                <input type="submit" id="submit" value="G R A V A R" onclick="return verificarCampos()">
            </div>
    </div>
    </form>
    </div>

    <script>
        //Controle de mensagem para cadastro igual já realizado
        setTimeout(function() {
            var mensagemErro = document.getElementById("mensagem-erro");
            if (mensagemErro) {
                mensagemErro.style.opacity = "0";
            }
        }, 3500);

        //Controle de Senha
        let senha1 = document.querySelector('#senha1');
        let senha2 = document.querySelector('#senha2');
        let submit = document.querySelector('#submit');
        let mens_erro = document.querySelector('.mens_erro');

        function verifica() {
            if (senha1.value == senha2.value) {
                mens_erro.style.display = 'none';
                submit.disable = false;
            } else {
                mens_erro.style.display = 'block';
                submit.disable = true;
            }
        }

        senha1.addEventListener('input', function() {
            verifica();
        });

        senha2.addEventListener('input', function() {
            verifica();
        });
    </script>
</body>

</html>