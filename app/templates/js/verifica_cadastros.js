// Função para validar campo
function validarCampo(campo) {
    // Verifica se o campo está vazio
    if (campo.value.trim() === "") {
        // Define a borda do campo como vermelha
        campo.style.borderColor = "red";
        // Exibe a mensagem de erro acima do campo
        campo.parentNode.querySelector(".mensagem-erro").innerHTML = "Este campo é obrigatório.";
        return false; // Indica que o campo está vazio
    } else {
        // Remove a borda vermelha e a mensagem de erro
        campo.style.borderColor = "";
        campo.parentNode.querySelector(".mensagem-erro").innerHTML = "";
        return true; // Indica que o campo está preenchido
    }
}

// Adiciona um ouvinte de evento para o botão de envio de cada modal
document.getElementById("submitTipoCarteira").addEventListener("click", function () {
    var camposPreenchidos = verificarCamposModais("popup");
    if (camposPreenchidos) { }
});

// Adiciona o evento de blur a todos os campos do formulário
var campos = document.querySelectorAll("input, select, textarea");
campos.forEach(function (campo) {
    campo.addEventListener("blur", function () {
        validarCampo(campo);
    });
});

function verificarCampos() {

    verificarLancamentoIgual();
    var formulario = document.getElementById("formulario");
    var campos = formulario.querySelectorAll("input, select, textarea");

    // Variável para verificar se há campos vazios
    var camposVazios = false;

    campos.forEach(function (campo) {
        // Verifica se o campo não é o campo de comprovante

        if (campo.value.trim() === "") {
            campo.style.borderColor = "red";
            var mensagemErro = campo.parentNode.querySelector(".mensagem-erro");
            if (mensagemErro) {
                mensagemErro.innerHTML = "Este campo é obrigatório.";
                setTimeout(function () {
                    campo.style.borderColor = "";
                    mensagemErro.innerHTML = "";
                }, 3000);

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
    xhr.open("POST", "cad-contas.php", true);
    xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
    xhr.onreadystatechange = function () {
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

}

// Função para lidar com a resposta JSON do servidor
function handleResponse(response) {
    if (response.erro === "lancamento-igual") {
        alert("Essa Carteira já existe! Você pode altera a existente.");
    }
}

// Função para fazer uma requisição AJAX para o servidor
function fazerRequisicao() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', './cad-lancamento-receitabd.php', true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.onload = function () {
        if (xhr.status >= 200 && xhr.status < 400) {
            var response = JSON.parse(xhr.responseText);
            handleResponse(response);
        }
    };
    xhr.send();
}

//função pra mensagem de item existente
setTimeout(function () {
    var mensagemErro = document.getElementById("mensagem-erro");
    mensagemErro.style.display = "none";
}, 3000);


// Função para validar os campos do formulário
function validarCampo(campo) {
    const mensagemErro = campo.parentNode.querySelector(".mensagem-erro");
    if (campo.value.trim() === "") {
        campo.style.borderColor = "red";
        mensagemErro.textContent = "Este campo é obrigatório.";
        return false;
    } else {
        campo.style.borderColor = "";
        mensagemErro.textContent = "";
        return true;
    }
}

// Função para verificar os campos de um modal específico e exibir mensagens de erro se estiverem vazios
function verificarCamposModais(campo) {
    var modal = document.getElementById(campo);
    const campos = document.querySelectorAll("#form-carteira input[type=text]");

    var camposPreenchidos = true;

    campos.forEach(function (campo) {
        if (campo.value.trim() === "") {
            campo.style.borderColor = "red";
            var mensagemErro = campo.parentNode.querySelector(".mensagem-erro");
            if (mensagemErro) {
                mensagemErro.innerHTML = "Este campo é obrigatório.";

                setTimeout(function () {
                    campo.style.borderColor = "";
                    mensagemErro.innerHTML = "";
                }, 3000);
            }

            camposPreenchidos = false;
        }
    });

    return camposPreenchidos;
}


// Função para lidar com a gravação do formulário
function gravar() {
    if (verificarCamposModais()) {
        document.getElementById("form-carteira").submit();
    }
}

// Adiciona um evento de blur a todos os campos do formulário para validação
document.querySelectorAll("#form-carteira input, select, textarea").forEach(campo => {
    campo.addEventListener("blur", () => validarCampo(campo));
});

