<?php


// Obtém o código da sessão do usuário
$codigo = $_SESSION['codigo'];

try {
    
    $stmt = $conexao->prepare("SELECT descricao FROM tipos_carteiras WHERE codigo_usuario = :usuario ORDER BY descricao ASC");
    $stmt->bindValue(':usuario', $codigo, PDO::PARAM_INT);
    $stmt->execute();

    $options = [];
    while ($row = $stmt->fetch()) {
        $nomeCarteira = htmlspecialchars($row['descricao']);
        $selected = ($nomeCarteira == $tipo) ? 'selected' : '';
        $options[] = "<option value=\"{$nomeCarteira}\" {$selected}>{$nomeCarteira}</option>";
    }
} catch (PDOException $e) {
    echo '<option value="">Erro ao carregar tipos de carteiras</option>';
}
?>


<div class="container">

    <?php

    // Verificar se há valores passados via GET para preencher os campos
    $descricaoCarteira = isset($_GET['descricaoCarteira']) ? htmlspecialchars(urldecode($_GET['descricaoCarteira'])) : '';
    $tipo = isset($_GET['tipo']) ? htmlspecialchars(urldecode($_GET['tipo'])) : '';

    // Verifique se há uma mensagem de sucesso na URL
    if (isset($_GET['status']) && $_GET['status'] == 'sucesso_carteira') {
        $mensagem = 'Tipo de Carteira cadastrado com sucesso ' . urldecode($_GET['novo_tipo_carteira']);
        //Exibe mensgame de sucesso pel URL
        echo '<div class="mensagem-sucesso personalizado">' . $mensagem . '</div>';
    }

    // Verifica se há uma nova categoria na URL e exibe no campo específico
    if (isset($_GET['novo_tipo_carteira'])) {
        $tipo = htmlspecialchars($_GET['novo_tipo_carteira']);
        $mensagem = 'Tipo de Carteira cadastrado com sucesso. ' . urldecode($_GET['novo_tipo_carteira']);
        // Exibe mensagem de sucesso
        echo '<div class="mensagem-sucesso personalizado">' . $mensagem . '</div>';
    }


    // Verificar se há um erro na URL
    if (isset($_GET['erro']) && $_GET['erro'] === 'lancamento-igual') {
        //$descricao = htmlspecialchars($_GET['descricao']);
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
        ">Essa Carteira já existe! Você pode alterá-la ou inserir uma nova.</div>';
    }

    // Verificar se há um erro na URL
    if (isset($_GET['erro']) && $_GET['erro'] === 'lancamento-igual-tipo') {
        //$descricao = htmlspecialchars($_GET['descricao']);
        //$tipo = htmlspecialchars($_GET['tipo']);
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
        ">Esse Tipo de Carteira já existe! Você pode alterá-lo ou inserir uma novo.</div>';
    }
    ?>

</div>

<div class="centralizar-v"><br><br>
    <form id="formulario" action="./cad-contasbd.php" method="POST" enctype="multipart/form-data">
        <div class="row-flex">
            <div class="col">
                <div class="row-flex centralizar-h">
                    <div class="col-8" style="margin-top: 15px;">
                        <label for="descricao">Descrição do Tipo de Carteira</label>
                        <input type="text" id="descricaoCarteira" name="descricaoCarteira"
                            placeholder="Descrição do Tipo de Carteira"
                            value="<?php echo isset($_GET['descricaoCarteira']) ? htmlspecialchars(urldecode($_GET['descricaoCarteira'])) : ''; ?>">
                        <div class="mensagem-erro" style="color: red;"></div>
                    </div>

                </div>
                <div class="row-flex centralizar-h">
                    <div class="col-8" style="margin-top: 15px;">
                        <label for="tipo">Tipo de Carteira</label>
                        <select name="tipo" id="tipo">
                            <option value="">Selecione</option>
                            <?php echo $options ?>
                        </select>
                        <div class="mensagem-erro" style="color: red;"></div>
                        <br><br>
                        <a href="#" id="linkCadastroTipo" onclick="exibirPopup()" class="link-as-button"
                            style="padding:2px;">+ Cadastrar Tipo de Carteira</a>
                    </div>
                </div>
            </div>
        </div>


        <div class="containercad" style="margin-top: 35px;">
            <form onsubmit="return false;">
                <div class="voltar" style="background-color: dimgray">
                    <input type="submit" id="voltar"
                        style="background-color: dimgray; border: 1px solid dimgray; font-size: 18px" value="Fechar"
                        formaction="../../tela-inicial.php" onclick="fecharFormulario()">
                </div>
            </form>
            <div class="espaco-m"></div>
            <input type="submit" id="submit" value="G R A V A R" onclick="return verificarCampos()">
        </div>
    </form>

    <!-- Pop-up de cadastro Tipo de Carteira -->
    <div id="popup" class="popup" style="display: none;">
        <div class="popup-content">
            <h2>Novo Tipo de Carteira</h2>
            <div class="centralizar-h">
                <form id="form-carteira" action="./cad-tipo-contasbd-popup-cad-principal.php" method="POST"
                    enctype="multipart/form-data">
                    <label for="descricao">Descrição</label>
                    <input type="text" name="descricao" id="descricao"
                        placeholder="Insira a Descrição do Tipo da Carteira">
                    <div class="mensagem-erro" style="color: red;"></div>
                    <div class="containercad" style="margin-top: 35px;">
                        <div class="voltar" style="background-color: dimgray">
                            <input type="button"
                                style="background-color: dimgray; border: 1px solid dimgray; font-size: 16px; padding: 25px 50px; padding-right:95px; margin-top: 9px; border-radius:15px; color:white"
                                value="Fechar" onclick="fecharPopup()">
                        </div>
                        <div class="espaco-m"></div>
                        <button type="button" id="submitTipoCarteira" onclick="gravar()">G R A V A R</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const params = new URLSearchParams(window.location.search);
        const descricao = params.get('tipo') || params.get('novo_tipo_carteira');
        if (descricao) {
            document.getElementById('descricao').value = descricao;
        }
    });

    window.addEventListener('DOMContentLoaded', function () {
        var mensagem = document.querySelector('.mensagem-sucesso.personalizado');
        if (mensagem) {
            mensagem.style.display = 'block';
            setTimeout(function () {
                mensagem.style.opacity = 0;
                setTimeout(function () {
                    mensagem.style.display = 'none';
                }, 900);
            }, 5000);
        }
    });

    function salvarValoresFormulario() {
        localStorage.setItem('descricaoCarteira', document.getElementById('descricaoCarteira').value);
    }

    function preencherCamposFormulario() {
        document.getElementById('descricaoCarteira').value = localStorage.getItem('descricaoCarteira') || '';
    }

    function limparValoresFormulario() {
        localStorage.removeItem('descricaoCarteira');
    }

    window.addEventListener('DOMContentLoaded', function () {
        preencherCamposFormulario();
    });

    function exibirPopup() {
        salvarValoresFormulario();
        var popup = document.getElementById("popup");
        popup.style.display = 'flex';
    }

    function fecharFormulario() {
        limparValoresFormulario();
    }

    function fecharPopup() {
        var popup = document.getElementById("popup");
        popup.style.display = 'none';
        preencherCamposFormulario();
    }

    document.getElementById('submit').addEventListener('click', function (event) {
        fecharFormulario();
    });

    setTimeout(function () {
        var mensagemErro = document.getElementById("mensagem-erro");
        if (mensagemErro) {
            mensagemErro.style.opacity = "0";
        }
    }, 3500);
</script>
</body>

</html>