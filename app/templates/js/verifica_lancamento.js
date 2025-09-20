        import sendData from './sendData.js';

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