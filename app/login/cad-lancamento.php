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
    <title>Minha Carteira - Cadastro de Lançamento de Despesa</title>
    <link rel="stylesheet" href="./css/estilo.css">
    <link rel="shortcut icon" href="../../logo_minha_carteira_ICO.ico" type="image/x-icon">
    <script type="module" defer src="./js/controle_de_popup.js"></script>
    <script src="./js/verifica_cadastros.js"></script>

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
        /* Inicialmente escondido */
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
        
        <h1 class="titulo-2">Cadastro de Lançamento de Despesa</h1>

        <?php include("./_menu-telas-consultas.php");?>
   
    <div id="mensagem-formulario" class="mensagem"></div>
    <div id="mensagem-sucesso" class="mensagem"></div>
    <div id="mensagem-carteira'"></div>
    <div id="mensagem-formulario-categoria"></div>
    <div id="mensagem-formulario-tipo"></div>
    <div id="excinsucesso">Não foi possível excluir o Registro!</div>
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
        <form id="formulario" action="cad-lancamentobd.php" method="POST" enctype="multipart/form-data">
            <div class="row-flex">
                <div class="col">
                    <label for="data_pagamento">Data do Pagamento</label>
                    <input type="date" style="padding: 15px; font-size: 20px;" name="data_pagamento" id="data_pagamento" value="<?php echo isset($_GET['data_pagamento']) ? htmlspecialchars(urldecode($_GET['data_pagamento'])) : ''; ?>">
                </div>

                <div class="col">
                    <label for="data_vencimento">Data de Vencimento</label>
                    <input type="date" style="padding:15px; font-size: 20px;" id="data_vencimento" name="data_vencimento" value="<?php echo isset($_GET['data_vencimento']) ? htmlspecialchars(urldecode($_GET['data_vencimento'])) : ''; ?>">
                    <div class="mensagem-erro" style="color: red;"></div>
                </div>
            </div>

            <div class="row-flex">
                <div class="col" style="margin-top: 15px;">
                    <label for="descricao">Descrição</label>
                    <input type="text" name="descricao" placeholder="Descrição do Lançamento" value="<?php echo isset($_GET['descricao']) ? htmlspecialchars(urldecode($_GET['descricao'])) : ''; ?>">
                   
                    <div class="mensagem-erro" style="color: red;"></div>
                </div>
            </div>

            <div class="row-flex">
                <div class="col">
                    <label for="valor">Valor</label>
                    <input type="text" name="valor" id="valor" placeholder="Valor do Lançamento" value="<?= isset($_GET['valor']) ? htmlspecialchars($_GET['valor']) : '' ?>">
                    <div class="mensagem-erro" style="color: red;"></div>
                </div>
            </div><br>

                <!--Parcelamento de despesas-->
            <div class="row-flex">
                <div class="col">
                    <label for="parcelas">Parcelar em</label>
                    <input type="number" name="parcelas" id="parcelas" min="1" value="1" style="padding:10px; font-size:18px;">
                    <span>vezes.</span>
                </div>
            </div>


            <div class="row-flex">
                <div class="col">
                    <div class="row-flex centralizar-h">
                        <div class="col" style="margin-top: 15px;">
                            <label for="categoria">Categoria</label>
                            <div class="mensagem-erro" style="color: red;"></div>
                            <select name="categoria" id="select-categoria">
                                <?php
                                // Obtém o código da sessão do usuário
                                if(isset($_SESSION['codigo'])) {
                                    $codigo = $_SESSION['codigo'];
                            try {
                                $query = "SELECT codigo, descricao 
                                          FROM categorias 
                                          WHERE codigo_usuario = :usuario AND tipo = 'despesa'
                                          ORDER BY descricao ASC";
                                    $stmt = $conexao->prepare($query);
                                    $stmt->bindParam(':usuario', $codigo, PDO::PARAM_INT);

                                    // Executar a consulta
                                    $stmt->execute();

                                    // Verifica se há categorias retornadas
                                    if ($stmt->rowCount() > 0) {
                                        echo '<option value="">Selecione</option>'; // Opção padrão "Selecione"

                                        // Captura categoria passada via GET (se houver)
                                        $novaCategoria = $_GET['nova_categoria'] ?? null;

                                        // Itera sobre as categorias e cria as opções do campo de seleção
                                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                            $codigoCategoria = $row["codigo"];
                                            $nomeCategoria   = htmlspecialchars($row["descricao"]);

                                            // Se a categoria do GET for igual à do loop, marca como selecionada
                                            $selected = ($novaCategoria == $codigoCategoria) ? 'selected' : '';
                                            echo "<option value=\"$codigoCategoria\" $selected>$nomeCategoria</option>";
                                        }
                                    } else {
                                        echo '<option value="">Nenhuma categoria encontrada</option>';
                                    }
                                } catch (PDOException $e) {
                                    echo '<option value="">Erro ao carregar categorias</option>';
                                }
                                } else {
                                    echo '<option value="">Usuário não identificado</option>';
                                }
                                ?>
                            </select>
                            <button type="button" class="link-as-button" style="padding: 2px; margin-top: 28px" id="linkCadastroCategoria" onclick="exibirPopup()">+ Nova Categoria</button>
                        </div>
                    </div>
                </div>
            </div>
           

            <div class="row-flex">
                <div class="col">
                    <div class="row-flex centralizar-h">
                        <div class="col" style="margin-top: 15px;">
                            <label for="carteira">Carteira de Pagamento</label>
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
                            <button type="button" class="link-as-button" style="padding: 2px; margin-top: 28px" id="linkCadastroCarteira" onclick="exibirPopupCarteira()">+ Nova Carteira</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row-flex">
                <div class="col">
                    <div class="row-flex centralizar-h">
                        <div class="col" style="margin-top: 15px;" id="situacao">
                            <label for="situacao">Situação</label>
                            <select name="situacao">
                                <option value="">Selecione</option>
                                <option value="Em Aberto" <?php if (isset($_GET['situacao']) && $_GET['situacao'] === 'Em Aberto') echo 'selected'; ?>>Em Aberto</option>
                                <option value="Pago" <?php if (isset($_GET['situacao']) && $_GET['situacao'] === 'Pago') echo 'selected'; ?>>Pago</option>
                            </select>
                            
                            <div class="mensagem-erro" style="color: red;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row-flex">
                <div class="col">
                    <label for="comprovante">Comprovante de Pagamento</label>
                    <input type="file" name="comprovante" id="comprovante" style="display: none;">
                    <span id="file-selected"></span>
                    <img id="image-preview" src="" alt="Preview" style="display: none;">
                    <embed id="pdf-preview" class="pdf-preview" src="" type="application/pdf" width="710" height="500" style="display: none;">
                    <label for="file-input-button" style="font-size: 17px; padding: 8px; font-family: Verdana, Geneva, Tahoma, sans-serif;font-size: 18px;" ; class="link-as-button">+ Anexar Arquivo</label>
                    <!-- class="custom-file-input-label">Anexar Arquivo</label> -->
                    <input type="button" id="file-input-button" class="custom-file-input" value="Escolher Arquivo">

                </div>
            </div>


            <div class="containercad" style="margin-top: 35px;">
                <div class="voltar" style="background-color: dimgray">
                    <input type="submit" id="voltar" style="background-color: dimgray; border: 1px solid dimgray; font-size: 16px" value="Fechar" formaction="../../tela-inicial.php" onclick="fecharFormulario()">
                </div>
                <div class="espaco-m"></div>
                <div id="mensagem-erro" style="color: red;"></div>
                <input type="submit" id="submit" value="G R A V A R" onclick="return verificarCampos()">
            </div>
        </form>
    </div>

    <!-- Modal de cadastro de Categoria de Receita -->
    <dialog id="categoria-popup" class="popup-content">
        <h2>Nova Categoria de Despesa</h2>
        <label style="padding: 1px 95px;" for="descricao">Descrição</label>
        <div class="mensagem-erro" style="color: red;"></div>
        <div class="centralizar-pop-up">
            <input type="text" name="descricao" id="descricao-categoria" placeholder="Nome da Categoria">
            <div class="mensagem-erro" style="color: red;"></div>
        </div>
        <label style="padding: 1px 95px;" for="descricao">Tipo</label>
        <div class="centralizar-pop-up">
            <select name="tipo" id="tipo">
                <option value="Despesa" selected>Despesa</option>
            </select>
            <div class="mensagem-erro" style="color: red;"></div>
        </div>
        <div class="containercad" style="margin-top: 25px;">
            <div class="voltar" style="background-color: dimgray; display: flex; align-items: center;">
                <button type="button" style="background-color: dimgray; border: 1px solid dimgray; font-size: 16px; padding: 25px 50px; padding-right:95px; margin-top: 9px" id="fecharCategoria">Fechar</button>
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
                <br>
                <!-- <div class="mensagem-erro" style="color: red;"></div>    -->
                <?php
                // Obtém o código da sessão do usuário
                $codigo = $_SESSION['codigo'];
                try {
                    require_once("./_conexao/conexao.php");
                    $stmt = $conexao->prepare("SELECT descricao FROM tipos_carteiras WHERE codigo_usuario = :usuario ORDER BY descricao ASC");
                    $stmt->bindValue(':usuario', $codigo, PDO::PARAM_INT);
                    $stmt->execute();
                    while ($row = $stmt->fetch()) {
                        $nomeCarteira = htmlspecialchars($row['descricao']);
                        echo "<option value=\"{$nomeCarteira}\">{$nomeCarteira}</option>";
                    }
                } catch (PDOException $e) {
                    echo '<option value="">Erro ao carregar tipos de carteiras</option>';
                }
                ?>
            </select>
            <div class="mensagem-erro" style="color: red;"></div>
        </div>

        <button type="button" class="link-as-button" style="margin-left: 116px; padding: 2px;" id="linkCadastroTipoCarteira" onclick="exibirPopuptIPOCarteira()">+ Novo Tipo de Carteira</button>

        <div class="containercad" style="margin-top: 5px;">
            <div class="voltar" style="background-color: dimgray; display: flex; align-items: center;">
                <button type="button" style="background-color: dimgray; border: 1px solid dimgray; font-size: 16px; padding: 25px 50px; padding-right:75px; margin-top: 9px" id="fecharCarteira">Fechar</button>
            </div>
            <div class="espaco-m"></div>
            <div class="mensagem-erro" style="color: red;"></div>
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

            verificarLancamentoIgual();
            var formulario = document.getElementById("formulario");
            var campos = formulario.querySelectorAll("input, select, textarea");

            // Variável para verificar se há campos vazios
            var camposVazios = false;

            campos.forEach(function(campo) {
                // Verifica se o campo não é o campo de comprovante
                if (campo.id !== "comprovante") {
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
            xhr.open("POST", "cad-lancamento.php", true);
            xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var resposta = JSON.parse(xhr.responseText);
                        if (resposta.erro === "lancamento-igual") {
                            // Se o lançamento já existir, exibe a mensagem de erro
                            document.getElementById("mensagem-erro").innerHTML = "Já existe wwwwwum lançamento idêntico cadastrado.";
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

        //Javascript para mensagem de cadastro aparecer por determinado tempo
        window.addEventListener('DOMContentLoaded', function() {
            var mensagem = document.querySelector('.mensagem-sucesso.personalizado');
            mensagem.style.display = 'block';

            setTimeout(function() {
                mensagem.style.opacity = 0;
                setTimeout(function() {
                    mensagem.style.display = 'none';
                }, 900);
            }, 5000);
        });

        //Javascript para mensagem de cadastro aparecer por determinado tempo
        window.addEventListener('DOMContentLoaded', function() {
            var mensagem = document.querySelector('.mensagem-insucesso.personalizado');
            mensagem.style.display = 'block';

            setTimeout(function() {
                mensagem.style.opacity = 0;
                setTimeout(function() {
                    mensagem.style.display = 'none';
                }, 900);
            }, 5000);
        });

        // Função para salvar os valores do formulário no localStorage
        function salvarValoresFormulario() {
            localStorage.setItem('descricao', document.getElementById('descricao').value);
            localStorage.setItem('data_pagamento', document.getElementById('data_pagamento').value);
            localStorage.setItem('data_vencimento', document.getElementById('data_vencimento').value);
            localStorage.setItem('valor', document.getElementById('valor').value);
        }

        // Função para salvar os valores do formulário no localStorage quando abre o popupCarteira
        function salvarValoresFormularioCarteira() {
            localStorage.setItem('descricao', document.getElementById('descricao').value);
            localStorage.setItem('data_pagamento', document.getElementById('data_pagamento').value);
            localStorage.setItem('data_vencimento', document.getElementById('data_vencimento').value);
            localStorage.setItem('valor', document.getElementById('valor').value);
            localStorage.setItem('categoria', document.getElementById('categoria').value);
        }

        // Função para preencher os campos do formulário com os valores salvos no localStorage
        function preencherCamposFormulario() {
            document.getElementById('descricao').value = localStorage.getItem('descricao') || '';
            document.getElementById('data_pagamento').value = localStorage.getItem('data_pagamento') || '';
            document.getElementById('data_vencimento').value = localStorage.getItem('data_vencimento') || '';
            document.getElementById('valor').value = localStorage.getItem('valor') || '';
            // if (localStorage.getItem('categoria'))
            // document.getElementById('categoria').value = localStorage.getItem('categoria') || '';
        }

        // Função para preencher os campos do formulário com os valores salvos no localStorage quando fechar popupCarteria
        function preencherCamposFormularioCarteira() {
            document.getElementById('descricao').value = localStorage.getItem('descricao') || '';
            document.getElementById('data_pagamento').value = localStorage.getItem('data_pagamento') || '';
            document.getElementById('data_vencimento').value = localStorage.getItem('data_vencimento') || '';
            document.getElementById('valor').value = localStorage.getItem('valor') || '';
            if (localStorage.getItem('categoria'))
                document.getElementById('categoria').value = localStorage.getItem('categoria') || '';
        }

        // Função para limpar os valores salvos no localStorage
        function limparValoresFormulario() {
            localStorage.removeItem('descricao');
            localStorage.removeItem('data_pagamento');
            localStorage.removeItem('data_vencimento');
            localStorage.removeItem('valor');
        }

        // Chame a função para preencher os campos quando a página for carregada
        window.addEventListener('DOMContentLoaded', function() {
            preencherCamposFormulario();
        });

        // Função para abrir a pop-up
        function exibirPopup() {
            // Chame a função para salvar os valores antes de abrir a pop-up
            salvarValoresFormulario();
            var popup = document.getElementById("popup");
            popup.style.display = 'flex';
        }

        // Função para fechar o formulário e limpar os valores
        function fecharFormulario() {
            // Chame a função para limpar os valores antes de fechar o formulário
            limparValoresFormulario();
            // Limpar os valores do localStorage ao carregar a página
            localStorage.removeItem('descricao');
            localStorage.removeItem('data_pagamento');
            localStorage.removeItem('data_vencimento');
            localStorage.removeItem('valor');
            localStorage.removeItem('categoria');
            localStorage.removeItem('carteira');
        }

        function fecharPopup() {
            var popup = document.getElementById("popup");
            popup.style.display = 'none';
            preencherCamposFormulario();
        }

        // Função para abrir a pop-up
        function exibirPopupCarteira() {
            // Chame a função para salvar os valores antes de abrir a pop-up
            salvarValoresFormularioCarteira()
            var popupCarteira = document.getElementById("popupCarteira");
            popupCarteira.style.display = 'flex';
        }

        function fecharPopupCarteira() {
            var popupCarteira = document.getElementById("popupCarteira");
            popupCarteira.style.display = 'none';
            preencherCamposFormulario();
        }

        document.getElementById('submit').addEventListener('click', function(event) {
            //função para fechar o formulário
            fecharFormulario();
        });

        // Função para atualizar o elemento com o nome do arquivo selecionado
        function updateFileName() {
            const comprovanteInput = document.getElementById("comprovante");
            const fileSelected = document.getElementById("file-selected");

            if (comprovanteInput.files.length > 0) {
                fileSelected.textContent = "Arquivo selecionado: " + comprovanteInput.files[0].name;
            } else {
                fileSelected.textContent = "";
            }
        }

        //Função para atualizar a prévia do arquivo (imagem ou PDF)
        function updateFilePreview() {
            const comprovanteInput = document.getElementById("comprovante");
            const fileSelected = document.getElementById("file-selected");
            const imagePreview = document.getElementById("image-preview");
            const pdfPreview = document.getElementById("pdf-preview");
            const fileInputButton = document.getElementById("file-input-button");

            if (comprovanteInput.files.length > 0) {
                const selectedFile = comprovanteInput.files[0];

                // Verifica se o arquivo é uma imagem ou PDF
                if (/\.(jpe?g|png|gif|bmp)$/i.test(selectedFile.name)) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                        imagePreview.style.display = "block";
                        pdfPreview.style.display = "none";
                        fileInputButton.style.display = "none";
                    };
                    reader.readAsDataURL(selectedFile);
                } else if (/\.(pdf)$/i.test(selectedFile.name)) {
                    pdfPreview.src = URL.createObjectURL(selectedFile);
                    imagePreview.style.display = "none";
                    pdfPreview.style.display = "block";
                    fileInputButton.style.display = "none"; // Oculta o botão "Anexar Arquivo"
                } else {
                    // Arquivo não suportado, oculta ambos (imagem e PDF)
                    imagePreview.style.display = "none";
                    pdfPreview.style.display = "none";
                }

                fileSelected.textContent = "Arquivo selecionado: " + selectedFile.name;
            } else {
                fileSelected.textContent = ""; // Limpa o texto se nenhum arquivo estiver selecionado
                imagePreview.style.display = "none";
                pdfPreview.style.display = "none";
                fileInputButton.style.display = "block"; // Torna o botão "Anexar Arquivo" visível novamente
            }
        }

        // Adicione um ouvinte de evento ao campo de upload de arquivo
        const comprovanteInput = document.getElementById("comprovante");
        comprovanteInput.addEventListener("change", updateFilePreview);

        // Adicione um ouvinte de evento ao botão para acionar o seletor de arquivo
        const fileInputButton = document.getElementById("file-input-button");
        fileInputButton.addEventListener("click", function() {
            comprovanteInput.click();
        });

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

        function validarFormulario() {
            var formularioValido = true; // Inicialmente, assume que o formulário é válido

            var data_pagamento = document.getElementById("data_pagamento").value;
            var data_vencimento = document.getElementById("data_vencimento").value;
            var descricao = document.getElementById("descricao").value;
            var mensagemErro = document.getElementById("excinsucesso");

            if (data_pagamento === '' || data_vencimento === '' || descricao === '') {
                mensagemErro.innerText = "Por favor, preencha todos os campos!";
                formularioValido = false; // Define a variável para falso se a validação falhar
            } else {
                mensagemErro.innerText = ""; // Limpa a mensagem de erro se os campos estiverem preenchidos
            }

            return formularioValido; // Retorna true se o formulário for válido, caso contrário, retorna false
        }
    </script>
    <!-- <script src="verifica_lancamento"></script> -->
</body>

</html>