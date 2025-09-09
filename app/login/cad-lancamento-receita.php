<?php
require_once("_sessao.php");

require_once("./_conexao/conexao.php");
require_once("./layout/cabecalho.php");
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minha Carteira - Cadastro de Lançamento de Receita</title>
    <link rel="stylesheet" href="./css/estilo.css">
    <link rel="shortcut icon" href="../../logo_minha_carteira_ICO.ico" type="image/x-icon">
    <script type="module" defer src="./js/controle_de_popup.js"></script>
</head>

<style>
    .mensagem-insucesso {
        color: red;
    }

    .mensagem-erroo {
        color: red;
        background-color: rgb(235, 124, 50);
        color: rgb(248, 248, 248);
        border: 1px solid rgb(235, 252, 6);
        width: 500px;
        margin: 0 auto;
        margin-top: -59px;
        margin-bottom: 250PX;
        height: 110px;
        font-size: 29px;
        border-radius: 7px;
        text-align: center;
        opacity: 1;
        transition: opacity 4.3s ease;
        position: fixed;
        top: 10;
        left: 0;
        right: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 999;
        display: none;
    }

    .mensagem-sucesso {
        width: 500px;
        margin: 0 auto;
        color: rgb(248, 248, 248);
        margin-top: -59px;
        margin-bottom: 250PX;
        height: 110px;
        font-size: 29px;
        border-radius: 7px;
        text-align: center;
        opacity: 1;
        transition: opacity 4.3s ease;
        position: fixed;
        top: 10;
        left: 0;
        right: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 999;
        display: none;
        background-color: #4CAF50;
    }
</style>


<body>    
        <h1 class="titulo-2">Cadastro Lançamento de Receita</h1>

        <?php include("./_menu-telas-consultas.php");?>
    
    <div id="mensagem-formulario"></div>
    <div id="mensagem-formulario-categoria"></div>
    <div id="mensagem-formulario-tipo"></div>
    <div id="lancamento-igual"></div>
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
        margin-left: 900px;
        align-items: center;
       ">Já existe um lançamento igual. Por favor, verifique seus dados.</div>';
    }
    ?>

    <br><br>
    <div class="centralizar-cad-lancamentos">
        <form id="formulario" action="cad-lancamento-receitabd.php" method="POST" enctype="multipart/form-data">
            <div class="row-flex">
                <div class="col">
                    <label for="data_credito">Data do Crédito</label>
                    <input type="date" style="padding: 15px; font-size: 20px;" name="data_credito" id="data_credito" value="<?php echo isset($_GET['data_credito']) ? htmlspecialchars(urldecode($_GET['data_credito'])) : ''; ?>">
                    <div class="mensagem-erro" style="color: red;"></div>
                </div>
            </div>

            <div class="row-flex">
                <div class="col" style="margin-top: 15px;">
                    <label for="descricao">Descrição</label>
                    <input type="text" name="descricao" placeholder="Descrição do Lançamento" value="<?php echo isset($_GET['descricao']) ? htmlspecialchars(urldecode($_GET['descricao'])) : ''; ?>">
                    <br>
                    <div class="mensagem-erro" style="color: red;"></div>
                </div>
            </div><br>

            <div class="row-flex">
                <div class="col">
                    <label for="valor">Valor</label>
                    <input type="text" name="valor" id="valor" placeholder="Valor do Lançamento" value="<?= isset($_GET['valor']) ? htmlspecialchars($_GET['valor']) : '' ?>">
                    <div class="mensagem-erro" style="color: red;"></div>
                </div>
            </div>

            <div class="row-flex">
                <div class="col">
                    <div class="row-flex centralizar-h">
                        <div class="col" style="margin-top: 15px;">
                            <label for="categoria">Categoria</label><br>
                            <div class="mensagem-erro" style="color: red;"></div>
                            <select name="categoria" id="select-categoria">
                                <?php

                                // Obtém o código da sessão do usuário
                                $codigo = $_SESSION['codigo'];

                                // Verifique se a conexão foi estabelecida com sucesso
                                if ($conexao) {
                                    // Execute a consulta para buscar as categorias do usuário
                                    $query = "SELECT codigo, descricao FROM categorias WHERE codigo_usuario = :usuario";

                                    $query = "SELECT codigo, descricao FROM categorias WHERE codigo_usuario = :usuario AND tipo = 'receita'";

                                    $result_categorias = $conexao->prepare($query);
                                    $result_categorias->bindParam(':usuario', $codigo);

                                    //Executar a QUERY
                                    $result_categorias->execute();

                                    echo '<option value="">Selecione</option>'; // Opção padrão "Selecione"

                                    // Itera sobre as categorias e cria as opções do campo de seleção
                                    while ($row = $result_categorias->fetch(PDO::FETCH_ASSOC)) {
                                        $codigoCategoria = $row["codigo"];
                                        $nomeCategoria = $row["descricao"];

                                        // Exibe a opção com o código e a descrição
                                        echo "<option value=\"$codigoCategoria\">$nomeCategoria</option>";
                                    }

                                    // Verifica se há uma nova categoria na URL e exibe no campo específico
                                    if (isset($_GET['nova_categoria'])) {
                                        $novaCategoria = htmlspecialchars($_GET['nova_categoria']);

                                        // Exibe o campo de seleção da categoria com a nova categoria selecionada
                                        echo '<option value="' . $novaCategoria . '" selected>' . $novaCategoria . '</option>';
                                        // Limpa a variável da URL após usar
                                        unset($_GET['nova_categoria']);
                                    } else {
                                        //echo '<option value="" selected>Selecione</option>';
                                    }
                                }
                                ?>
                            </select>
                            <!-- Link para abrir o pop-up -->
                            <button type="button" class="link-as-button" style="padding: 2px;" id="linkCadastroCategoria" onclick="exibirPopup()">+ Nova Categoria</button>


                        </div>
                    </div>
                </div>
            </div>

            <div class="row-flex">
                <div class="col">
                    <div class="row-flex centralizar-h">
                        <div class="col" style="margin-top: 15px;">
                            <label for="carteira">Carteira de Pagamento</label><br>
                            <div class="mensagem-erro" style="color: red;"></div>
                            <select name="carteira" id="carteira">
                                <?php
                                // Obtém o código da sessão do usuário
                                $codigo = $_SESSION['codigo'];

                                // Verifique se a conexão foi estabelecida com sucesso
                                if ($conexao) {
                                    // Execute a consulta para buscar as categorias do usuário
                                    $query = "SELECT codigo, descricao FROM carteiras WHERE codigo_usuario = :usuario";
                                    $result_carteiras = $conexao->prepare($query);
                                    $result_carteiras->bindParam(':usuario', $codigo);

                                    //Executar a QUERY
                                    $result_carteiras->execute();

                                    echo '<option value="">Selecione</option>'; // Opção padrão "Selecione"

                                    // Itera sobre as categorias e cria as opções do campo de seleção
                                    while ($row = $result_carteiras->fetch(PDO::FETCH_ASSOC)) {
                                        $codigoCarteira = $row["codigo"];
                                        $nomeCarteira = $row["descricao"];

                                        // Exibe a opção com o código e a descrição
                                        echo "<option value=\"$codigoCarteira\">$nomeCarteira</option>";
                                    }

                                    // Verifica se há uma nova categoria na URL e exibe no campo específico
                                    if (isset($_GET['nova_carteira'])) {
                                        $novaCarteira = htmlspecialchars($_GET['nova_carteira']);

                                        // Exibe o campo de seleção da categoria com a nova categoria selecionada
                                        echo '<option value="' . $novaCarteira . '" selected>' . $novaCarteira . '</option>';
                                        // Limpa a variável da URL após usar
                                        unset($_GET['nova_carteira']);
                                    } else {
                                        //echo '<option value="" selected>Selecione</option>';
                                    }
                                }
                                ?>
                            </select>
                            <br>
                            <button type="button" class="link-as-button" style="padding: 2px;" id="linkCadastroCarteira" onclick="exibirPopupCarteira()">+ Nova Carteira</button>
                            <br>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row-flex">
                <div class="col">
                    <div class="row-flex centralizar-h">
                        <div class="col" style="margin-top: 15px;">
                            <label for="situacao">Situação</label>
                            <select name="situacao">
                                <option value="">Selecione</option>
                                <option value="Em Aberto" <?php if (isset($_GET['situacao']) && $_GET['situacao'] === 'Em Aberto') echo 'selected'; ?>>Em Aberto</option>
                                <option value="Recebido" <?php if (isset($_GET['situacao']) && $_GET['situacao'] === 'Recebido') echo 'selected'; ?>>Recebido</option>
                            </select>
                            
                            <div class="mensagem-erro" style="color: red;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="containercad" style="margin-top: 35px;">
                <form onsubmit="return false;">
                    <div class="voltar" style="background-color: dimgray">
                        <input type="submit" id="voltar" style="background-color: dimgray; border: 1px solid dimgray; font-size: 16px" value="Fechar" formaction="../../tela-inicial.php" onclick="fecharFormulario()">
                    </div>
                </form>
                <div class="espaco-m"></div>
                <div id="mensagem-erro" style="color: red;"></div>
                <input type="submit" id="submit" value="G R A V A R" onclick="return verificarCampos()">
            </div>
        </form>

        <!-- Modal de cadastro de Categoria de Receita -->
        <dialog id="categoria-popup" class="popup-content">
            <h2>Nova Categoria de Receita</h2>
            <label style="padding: 1px 95px;" for="descricao">Descrição</label>
            <div class="centralizar-pop-up">
                <input type="text" name="descricao" id="descricao-categoria" placeholder="Nome da Categoria">
                <div class="mensagem-erro" style="color: red;"></div>
            </div>
            <label style="padding: 1px 95px;" for="descricao">Tipo</label>
            <div class="centralizar-pop-up">
                <select name="tipo" id="tipo">
                    <option value="Receita" selected>Receita</option>
                </select>
                <div class="mensagem-erro" style="color: red;"></div>
            </div>
            <div class="containercad" style="margin-top: 25px;">
                <div class="voltar" style="background-color: dimgray; display: flex;    align-items: center;">
                    <input type="submit" style="background-color: dimgray; border: 1px solid dimgray; font-size: 16px; padding: 25px 50px; padding-right:95px; margin-top: 9px" value="Fechar" id="fecharCategoria">
                </div>
                <div class="espaco-m"></div>
                <button type="button" id="submitCategoria" onclick="return verificarCamposModais()">G R A V A R</button>
            </div>
        </dialog>

        <!-- Modal de cadastro Carteira -->
        <dialog id="carteira_modal" class="popup-content">
            <h2>Nova Carteira de Despesa</h2>
            <label style="padding: 1px 95px;" for="descricao">Descrição</label><br>
            <div class="mensagem-erro" style="color: red;"></div>
            <div class="centralizar-pop-up">
                <input type="text" name="descricao" id="descricaoCarteira" placeholder="Insira a Descrição da Carteira">
                <div class="mensagem-erro" style="color: red;"></div>
            </div><br>
            <label style="padding: 1px 95px" ;>Tipo de Carteira</label><br>
            <div class="mensagem-erro" style="color: red;"></div>
            <div class="centralizar-pop-up">
                <select name="tipo" id="tipoCarteira">
                    <option value="">Selecione</option>
                    <?php
                    // Obtém o código da sessão do usuário
                    $codigo = $_SESSION['codigo'];

                    // Conecte-se ao banco de dados
                    $conn = mysqli_connect("localhost", "root", "", "minhacarteira");

                    // Verifique se a conexão foi estabelecida com sucesso
                    if ($conn) {
                        // Execute a consulta para buscar as categorias do usuário
                        $query = "SELECT descricao FROM tipos_carteiras WHERE codigo_usuario = '$codigo'";
                        $result = mysqli_query($conn, $query);

                        // Verifique se há categorias retornadas
                        if (mysqli_num_rows($result) > 0) {
                            // Itere sobre as categorias e crie as opções do campo de seleção
                            while ($row = mysqli_fetch_assoc($result)) {
                                $nomeCarteira = $row["descricao"];
                                echo "<option value=\"$nomeCarteira\">$nomeCarteira</option>";
                            }
                        }

                        // Feche a conexão com o banco de dados
                        mysqli_close($conn);
                    }
                    ?>
                </select>
                <div class="mensagem-erro" style="color: red;"></div>
            </div>

            <button type="button" class="link-as-button" style="margin-left: 116px; padding: 2px;" id="linkCadastroTipoCarteira" onclick="exibirPopuptIPOCarteira()">+ Novo Tipo de Carteira</button>

            <div class="containercad" style="margin-top: 5px;">
                <div class="voltar" style="background-color: dimgray; display: flex;    align-items: center;">
                    <input type="submit" style="background-color: dimgray; border: 1px solid dimgray; font-size: 16px; padding: 25px 50px; padding-right:75px; margin-top: 9px" value="Fechar" id="fecharCarteira">
                </div>
                <div class="espaco-m"></div>
                <div id="mensagem-erro" style="color: red;"></div>
                <button type="button" id="submitCarteira" onclick="return verificarCamposModais()">G R A V A R</button>
            </div>
    </div>
    </dialog>
    </div>

    <!-- Modal de cadastro de Tipo de Carteira -->
    <dialog id="tipo_carteira_modal" class="popup-content">
        <h2>Novo Tipo de Carteira</h2>
        <label style="padding: 1px 95px;" for="descricao">Descrição</label>
        <div class="centralizar-pop-up">
            <input type="text" name="descricao" id="descricaoTipo" placeholder="Descrição">
            <div class="mensagem-erro" style="color: red;"></div>
        </div>
        <div class="containercad" style="margin-top: 25px;">
            <div class="voltar" style="background-color: dimgray; display: flex;    align-items: center;">
                <input type="submit" style="background-color: dimgray; border: 1px solid dimgray; font-size: 16px; padding: 25px 50px; padding-right:95px; margin-top: 9px" value="Fechar" id="fecharTipoCarteira">
            </div>
            <div class="espaco-m"></div>
            <button type="button" id="submitTipoCarteira" onclick="return verificarCamposModais()">G R A V A R</button>
        </div>
    </dialog>



    <script>
        // Função para validar campo individual
        function validarCampo(campo) {
            var mensagemErro = campo.parentNode.querySelector(".mensagem-erro");

            if (campo.value.trim() === "") {
                campo.style.borderColor = "red";

                if (mensagemErro) {
                    mensagemErro.innerHTML = "Este campo é obrigatório.";
                }

                setTimeout(function() {
                    campo.style.borderColor = "";
                    if (mensagemErro) {
                        mensagemErro.innerHTML = "";
                    }
                }, 3000);

                return false;
            } else {
                campo.style.borderColor = "";

                if (mensagemErro) {
                    mensagemErro.innerHTML = "";
                }

                return true;
            }
        }

        // Função para verificar todos os campos em um modal
        function verificarCamposModais(modalId) {
            var modal = document.getElementById(modalId);
            var campos = modal.querySelectorAll("input[type=text], select");

            var todosCamposPreenchidos = true;

            campos.forEach(function(campo) {
                if (!validarCampo(campo)) {
                    todosCamposPreenchidos = false;
                }
            });

            return todosCamposPreenchidos;
        }

        // Adiciona um ouvinte de evento para o botão de envio de cada modal
        document.getElementById("submitCategoria").addEventListener("click", function() {
            var camposPreenchidos = verificarCamposModais("categoria-popup");
            if (camposPreenchidos) {
                // Aqui você pode adicionar a lógica para enviar o formulário do modal, se necessário
            }
        });

        document.getElementById("submitCarteira").addEventListener("click", function() {
            var camposPreenchidos = verificarCamposModais("carteira_modal");
            if (camposPreenchidos) {
                // Aqui você pode adicionar a lógica para enviar o formulário do modal, se necessário
            }
        });

        document.getElementById("submitTipoCarteira").addEventListener("click", function() {
            var camposPreenchidos = verificarCamposModais("tipo_carteira_modal");
            if (camposPreenchidos) {
                // Aqui você pode adicionar a lógica para enviar o formulário do modal, se necessário
            }
        });

        // Adiciona o evento de blur a todos os campos do formulário
        var campos = document.querySelectorAll("input, select, textarea");
        campos.forEach(function(campo) {
            campo.addEventListener("blur", function() {
                validarCampo(campo);
            });
        });

        function verificarCampos() {
            // verificarLancamentoIgual(); // Verifique se isso é necessário aqui

            var formulario = document.getElementById("formulario");
            var campos = formulario.querySelectorAll("input, select, textarea");

            // Variável para verificar se há campos vazios
            var camposVazios = false;

            campos.forEach(function(campo) {
                // Restante do código para estilizar o campo e exibir a mensagem de erro
                if (campo.value.trim() === "") {
                    campo.style.borderColor = "red";
                    var mensagemErro = campo.parentNode.querySelector(".mensagem-erro");
                    if (mensagemErro) {
                        mensagemErro.innerHTML = "Este campo é obrigatório.";
                        setTimeout(function() {
                            campo.style.borderColor = "";
                            mensagemErro.innerHTML = "";
                        }, 3000);
                    }
                    // Define a variável camposVazios como true
                    camposVazios = true;
                }
            });

            // Se houver campos vazios, impede o envio do formulário
            if (camposVazios) {
                console.log("Campos vazios encontrados. Formulário não será enviado.");
                return false;
            } else {
                console.log("Todos os campos preenchidos. Enviando formulário...");
                // Submete o formulário
                formulario.submit();
                return true;
            }
        }


        function verificarLancamentoIgual() {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "cad-lancamento-receita.php", true);
            xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var resposta = JSON.parse(xhr.responseText);
                        if (resposta.erro === "lancamento-igual") {
                            // Se o lançamento já existir, exibe a mensagem de erro
                            document.getElementById("mensagem-erro").innerHTML = "Já existe lançamento idêntico cadastrado.";
                        } else {
                            // Se o lançamento não existir, envia o formulário normalmente
                            document.getElementById("formulario").submit();
                        }
                    }
                }
            };
            xhr.send();
            // Retorna false para evitar que o formulário seja enviado antes da resposta do servidor
            return false;
        }


        // Função para lidar com a resposta JSON do servidor
        function handleResponse(response) {
            if (response.erro === "lancamento-igual") {
                alert("Já existe um lançamento igual. Por favor, verifique seus dados.");
            }
        }

        // Função para fazer uma requisição AJAX para o servidor
        function fazerRequisicao() {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', './cad-lancamento-receitabd.php', true);
            xhr.setRequestHeader('Content-Type', 'application/json');
            xhr.onload = function() {
                if (xhr.status >= 200 && xhr.status < 400) {
                    var response = JSON.parse(xhr.responseText);
                    handleResponse(response);
                }
            };
            xhr.send();
        }

        //função pra mensagem de item existente
        setTimeout(function() {
            var mensagemErro = document.getElementById("mensagem-erro");
            mensagemErro.style.display = "none";
        }, 3000);
























        // Javascipt para mensagem de cadastro aparecer por determinado tempo
        window.addEventListener('DOMContentLoaded', function() {
            var mensagem = document.querySelector('.mensagem-sucesso.personalizado')
            mensagem.style.display = 'block';

            setTimeout(function() {
                mensagem.style.opacity = 0;
                setTimeout(function() {
                    mensagem.style.display = 'none';
                }, 900);
            }, 5000);
        });

        // //Função para salvar os valores do formulário no localStorage
        // function salvarValoresFormulario() {
        //     localStorage.setItem('descricao', document.getElementById('descricao').value);
        //     localStorage.setItem('data_credito', document.getElementById('data_credito').value);
        //     localStorage.setItem('valor', document.getElementById('valor').value);
        // }

        // // Função para salvar os valores do formulário no localStorage quando abre o popupCarteira
        // function salvarValoresFormularioCarteira() {
        //     localStorage.setItem('descricao', document.getElementById('descricao').value);
        //     localStorage.setItem('data_credito', document.getElementById('data_credito').value);
        //     localStorage.setItem('valor', document.getElementById('valor').value);
        //     localStorage.setItem('categoria', document.getElementById('categoria').value);
        // }


        // // Função para preencher os campos do formulário com os valores salvos no localStorage
        // function preencherCamposFormulario() {
        //     document.getElementById('descricao').value = localStorage.getItem('descricao') || '';
        //     document.getElementById('data_credito').value = localStorage.getItem('data_credito') || '';
        //     document.getElementById('valor').value = localStorage.getItem('valor') || '';
        //     // if (localStorage.getItem('categoria'))
        //     // document.getElementById('categoria').value = localStorage.getItem('categoria') || '';
        // }

        // // Função para preencher os campos do formulário com os valores salvos no localStorage quando fechar popupCarteria
        // function preencherCamposFormularioCarteira() {
        //     document.getElementById('descricao').value = localStorage.getItem('descricao') || '';
        //     document.getElementById('data_credito').value = localStorage.getItem('data_credito') || '';
        //     document.getElementById('valor').value = localStorage.getItem('valor') || '';

        //     document.getElementById('codigo, categoria').value = localStorage.getItem('categoria') || '';
        // }

        // // Função para limpar os valores salvos no localStorage
        // function limparValoresFormulario() {
        //     localStorage.removeItem('descricao');
        //     localStorage.removeItem('data_credito');
        //     localStorage.removeItem('valor');
        //     localStorage.removeItem('categoria');
        //     localStorage.removeItem('carteira');
        // }

        // // Chame a função para preencher os campos quando a página for carregada
        // window.addEventListener('DOMContentLoaded', function() {
        //     //preencherCamposFormulario();
        // });

        // // Função para exibir o pop-up
        // function exibirPopup() {
        //     // Chame a função para salvar os valores antes de abrir a pop-up
        //     salvarValoresFormulario();
        //     var popup = document.getElementById("popup");
        //     popup.style.display = "flex";
        // }

        // // Função para fechar o formulário e limpar os valores
        // function fecharFormulario() {
        //     // Se o usuário confirmar ou se não houver campos preenchidos, limpar valores e redirecionar
        //     limparValoresFormulario();
        //     window.location.href = "../../tela-inicial.php";
        // }


        // // Função para ocultar o pop-up
        // function fecharPopup() {
        //     var popup = document.getElementById("popup");
        //     popup.style.display = "none";
        // }

        // function exibirPopupCarteira() {
        //     // Chame a função para salvar os valores antes de abrir a pop-up
        //     salvarValoresFormularioCarteira()
        //     var popupCarteira = document.getElementById("popupCarteira");
        //     popupCarteira.style.display = 'flex';
        // }

        // function fecharPopupCarteira() {
        //     var popupCarteira = document.getElementById("popupCarteira");
        //     popupCarteira.style.display = 'none';
        // }


        // document.getElementById('submit').addEventListener('click', function(event) {

        //     // Após gravar os dados chama a função para fechar o formulário
        //     fecharFormulario();

        // });



        // Função para enviar os dados para o servidor e lidar com a resposta
        function cadastrarCarteira(descricao, tipo) {
            // Construir o objeto de dados a ser enviado para o servidor
            let dados = {
                descricao: descricao,
                tipo: tipo
            };

            // Enviar uma requisição AJAX para o servidor
            fetch('./cad-carteirasbd-popup.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(dados)
                })
                .then(response => response.json()) // Converter a resposta para JSON
                .then(data => {
                    // Manipular a resposta do servidor
                    if (data.status === 'success') {
                        // Redirecionar o usuário para a página de sucesso
                        window.location.href = './view-lancamento.php?status=sucesso-receita';
                    } else {
                        // Redirecionar o usuário para a página de insucesso
                        window.location.href = './view-lancamento.php?status=insucesso-receita';
                    }
                    // Exibir a mensagem na tela de cadastro
                    alert(data.msg);
                })
                .catch(error => {
                    console.error('Erro ao enviar requisição:', error);
                    alert('Erro ao processar solicitação. Por favor, tente novamente mais tarde.');
                });
        }

        // Função para enviar os dados para o servidor e lidar com a resposta
        function cadastrarTipoCarteira(descricao) {
            // Construir o objeto de dados a ser enviado para o servidor
            let dados = {
                descricao: descricao,
            };

            // Enviar uma requisição AJAX para o servidor
            fetch('./cad-tipo-carteiras.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(dados)
                })
                .then(response => response.json()) // Converter a resposta para JSON
                .then(data => {
                    // Manipular a resposta do servidor
                    if (data.status === 'success') {
                        // Redirecionar o usuário para a página de sucesso
                        window.location.href = './view-lancamento.php?status=sucesso-receita';
                    } else {
                        // Redirecionar o usuário para a página de insucesso
                        window.location.href = './view-lancamento.php?status=insucesso-receita';
                    }
                    // Exibir a mensagem na tela de cadastro
                    alert(data.msg);
                })
                .catch(error => {
                    console.error('Erro ao enviar requisição:', error);
                    alert('Erro ao processar solicitação. Por favor, tente novamente mais tarde.');
                });
        }
    </script>
</body>

</html>