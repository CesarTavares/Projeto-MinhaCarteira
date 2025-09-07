<?php
require_once("./_sessao.php");
require_once("./_conexao/conexao.php");
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minha Carteira - Cadastro de Categoria</title>
    <link rel="stylesheet" href="./css/estilo.css">
    <link rel="shortcut icon" href="../../logo_minha_carteira_ICO.ico" type="image/x-icon">
    <script src="./js/verifica_cadastros.js"></script>

    
</head>

<body>
    <div class="container">
        <h1>Minha Carteira - Controle Financeiro Pessoal</h1>
        <h1>Cadastro de Categoria</h1>
        <?php include("./_menu-telas-consultas.php"); ?>
    </div><br>

    <div class="centralizar-v">
        <form id="formulario" action="./cad-categoriasbd.php" method="POST" enctype="multipart/form-data">
            <div class="row-flex">
                <div class="col">
                    <div class="centralizar-h">
                        <div class="col-8" style="margin-top: 15px;">
                            <label for="descricao">Descrição da Categoria</label>
                            <input type="text" name="descricao" placeholder="Ex: Moradia/Compras Supermercado." value="<?php echo isset($_GET['descricao']) ? htmlspecialchars(urldecode($_GET['descricao'])) : ''; ?>">
                            <div class="mensagem-erro" style="color: red;"></div>
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
                                            margin-left: 150px;
                                            margin-top: -80px;
                                            align-items: center;
                                        ">Esta Categoria já existe! Você pode alterá-la ou inserir uma nova.</div>';
                            }
                            ?>
                        </div>
                    </div><br><br>

                    <div class="centralizar-h">
                        <div class="col-8">
                            <label for="tipo">Tipo da Categoria</label>
                            <select name="tipo" id="tipo">
                                <option value=""selected>Selecione</option>
                                <option value="Despesa">Despesa</option>
                                <option value="Receita">Receita</option>
                            </select>
                            <div class="mensagem-erro" style="color: red;"></div>
                        </div>
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


    <script>
        //Controle de mensagem para cadastro igual já realizado
        setTimeout(function() {
            var mensagemErro = document.getElementById("mensagem-erro");
            if (mensagemErro) {
                mensagemErro.style.opacity = "0";
            }
        }, 3500);

        // Função para enviar o formulário de cadastro da categoria usando AJAX
        document.getElementById("formCadastroCategoria").addEventListener("submit", function(event) {
            event.preventDefault(); // Evita o envio do formulário (pode ser personalizado para enviar para o servidor)

            // Obtenha o valor do campo de descrição
            var descricao = document.getElementById("descricao").value;

            // Crie um objeto FormData para enviar os dados via AJAX
            var formData = new FormData();
            formData.append("categoria", descricao);

            // Crie um objeto XMLHttpRequest para enviar a requisição AJAX
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "cad-categoriasbd.php", true);

            // Defina a função de callback para lidar com a resposta do servidor
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // A requisição foi concluída com sucesso
                    // Aqui você pode lidar com a resposta do servidor, se necessário
                    console.log(xhr.responseText);

                    // Atualize o campo "categoria" no formulário principal com o nome da categoria cadastrada
                    document.getElementById("categoria").value = descricao;

                    // Feche o pop-up após o envio do formulário
                    fecharPopup();

                    // Exiba uma mensagem com o valor da nova categoria cadastrada
                    alert("Categoria cadastrada com sucesso: " + descricao);
                }
            };

            // Envie a requisição com os dados do formulário
            xhr.send(formData);
        });
    </script>
</body>

</html>