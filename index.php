<?php
// iniciando uma sessão do usuário
session_start();
// excluindo as variáveis de session
session_unset();
// destruir a session (deslogando usuário)
session_destroy();
// Criando uma session indicando que a mensagem já foi exibida
$_SESSION['mensagem_exibida'] = true;
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minha Carteria - Login de Usuário</title>
    <link rel="stylesheet" href="./app/login/css/estilo.css">
    <link rel="shortcut icon" href="./logo_minha_carteira_ICO.ico" type="image/x-icon">
    <script src="./app/login/js/verifica_cadastros.js"></script>
    <style>
        input {
            margin: 25px 0px;
        }

        .login {
            width: 500px;
            margin: 0 auto;
            margin-top: 60px;
        }

        .esconder {
            display: none;
        }

        .alert {
            width: 500px;
            margin: 0 auto;
            margin-top: 60px;
            height: 60px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 26px;
            border-radius: 5px;
            border: 1px solid red;
        }

        .sucesso {
            background-color: lightgreen;
            color: green;
            border: 1px solid green;
        }

        .erro {
            background-color: lightpink;
            color: red;
            border: 1px solid red;
        }

        .link-as-button {
            appearance: button;
            font-size: 18px;
            padding: 20px 15px;
            background-color: #007bff;
            border: none;
            color: white;
            text-decoration: none;
            border-radius: 7px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="logo-minha-carteira"><img src="./logo_minha_carteira1.png" width="" alt=""></h1>
        <h1 class="titulo-minha-carteira">Minha Carteira - Controle Financeiro Pessoal<br><br> Login de Usuário</h1>
    </div>

    <?php
    $status = filter_input(INPUT_GET, 'status', FILTER_SANITIZE_SPECIAL_CHARS);
    if (isset($status) && ($status == "sucesso")) {
        echo '<div id="sucesso">O seu Usuário foi cadastrado com sucesso!</div>';
    }
    $status = filter_input(INPUT_GET, 'status', FILTER_SANITIZE_SPECIAL_CHARS);
    if (isset($status) && ($status == "erroUsuario")) {
        echo '<div id="erroSenha">Usuário ou Senha Incorretos!</div>';
    }

    $status = filter_input(INPUT_GET, 'status', FILTER_SANITIZE_SPECIAL_CHARS);
    if (isset($status) && ($status == "contaDesativada")) {
        echo '<div id="altNever">Seu Usuário está Desativado! Entre em contato com o Administrador.</div>';
    }

    $status = filter_input(INPUT_GET, 'status', FILTER_SANITIZE_SPECIAL_CHARS);
    if (isset($status) && ($status == "erroSenha")) {
        echo '<div id="erroSenha">Senha incorreta</div>';
    }
    ?>


    <div class="container">
        <form id="formulario" action="valida-senha.php" method="POST" enctype="multipart/form-data">
            <div class="row-flex centralizar-h">
                <div class="col-3" style="margin-top: 28px; text-align: center;">
                    <label for="usuario" style="font-size: 23px;">E-mail</label>
                    <input type="text" name="usuario" id="usuario" placeholder="Digite seu E-mail" autofocus style="font-size: 19px; text-align: center; width: 100%; margin-top: 20px; margin-bottom:  1px; height: 60px;">
                    <div class="mensagem-erro" style="color: red; align-items: center;"></div>
                </div>
            </div>
            <div class="row-flex centralizar-h">
                <div class="col-3" style="margin-top: 25px; text-align: center;">
                    <label for="senha" style="font-size: 23px;">Senha</label>
                    <input type="password" name="senha" id="senha" placeholder="Digite sua Senha" style="font-size: 19px; text-align: center; width: 100%; margin-top: 20px; height: 60px;margin-bottom:  1px;">
                    <div class="mensagem-erro" style="color: red;"></div>
                </div>
            </div>
            <a href="./app/login/telainicial.php"></a>
            <div class="row-flex centralizar-h">
                <div class="espaco-h" style="margin-top: 35px;"></div>
                <!-- <input type="submit" value=" E N T R A R " id="botaoEntrar" style="font-size: 18px"> -->
                <input type="submit" id="botaoEntrar" style="margin-top: 50px" ; value=" E N T R A R " onclick="return verificarCampos()">
            </div><br>
            <div class="container centralizar-h" style="font-size: 25px; text-align: center; width: 100%; margin-top: 5px; height: 60px;">
                <h4 style="font-size: 25px;"><b>Ainda não tem usuário?</b></h4>
            </div>
            <div class="container centralizar-h">
                <a class="link-as-button" href="./app/login/cad-novo-login.php">CADASTRE-SE AQUI</a>
            </div>
        </form>
    </div>

    <footer>
        <h5>&copy; 2024 Minha Carteira. Todos os direitos reservados.</h5>
        <h5>
            <a href="#">César Ricardo Tavares</a> |
            <a href="#">Diego Ap. Violpa Pascoal</a> |
            <a href="#">Contato: sac@minhacarteira.com</a>
        </h5>
    </footer>

    <script>
        const mensagem = setInterval(myMens, 3000);

        function myMens() {
            document.querySelector('.alert').classList.add("esconder");
            clearInterval(mensagem);
        }

        // Função para controlar o tempo e forma que aparece a mensagem de erro de senha
        window.addEventListener('DOMContentLoaded', function() {
            var mensagemSenha = document.getElementById('sucesso');
            mensagemSenha.style.display = 'block';

            setTimeout(function() {
                mensagemSenha.style.opacity = 0;
                setTimeout(function() {
                    mensagemSenha.style.display = 'none';
                }, 800);
            }, 4000);
        });

        // Função para controlar o tempo e forma que aparece a mensagem de erro de senha
        window.addEventListener('DOMContentLoaded', function() {
            var mensagemSenha = document.getElementById('erroSenha');
            mensagemSenha.style.display = 'block';

            setTimeout(function() {
                mensagemSenha.style.opacity = 0;
                setTimeout(function() {
                    mensagemSenha.style.display = 'none';
                }, 800);
            }, 4000);
        });

        // Função para controlar o tempo e forma que aparece a mensagem de erro de usuário
        window.addEventListener('DOMContentLoaded', function() {
            var mensagemUsuario = document.getElementById('erroUsuario');
            mensagemUsuario.style.display = 'block';

            setTimeout(function() {
                mensagemUsuario.style.opacity = 0;
                setTimeout(function() {
                    mensagemUsuario.style.display = 'none';
                }, 800);
            }, 4000);
        });

        // Função para controlar o tempo e forma que aparece a mensagem de erro de senha
        window.addEventListener('DOMContentLoaded', function() {
            var mensagemSenha = document.getElementById('altNever');
            mensagemSenha.style.display = 'block';

            setTimeout(function() {
                mensagemSenha.style.opacity = 0;
                setTimeout(function() {
                    mensagemSenha.style.display = 'none';
                }, 800);
            }, 4000);
        });
    </script>
</body>
</html>