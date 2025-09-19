import sendData from './sendData.js';

const GRAVAR_CARTEIRA = document.getElementById("submitCarteira");
const DESCRICAO_CARTEIRA = document.getElementById("descricaoCarteira");
const SELECT_TIPO = document.getElementById("tipoCarteira");
const SELECT_CARTEIRA = document.getElementById("carteira");
const CARTEIRA_MODAL_ACIONADOR = document.getElementById("linkCadastroCarteira");
const MODAL_CARTEIRA = document.getElementById("carteira_modal");
const GRAVAR_CARTEIRA_COM_TIPO = document.getElementById("submitCarteira");

const CATEGORIA_MODAL_ACIONADOR = document.getElementById("linkCadastroCategoria");
const MODAL_CATEGORIA = document.getElementById("categoria-popup");
const GRAVAR_CATEGORIA = document.getElementById("submitCategoria");
const DESCRICAO_CATEGORIA = document.getElementById("descricao-categoria");
const TIPO_CATEGORIA = document.getElementById("tipo");
const SELECT_CATEGORIA = document.getElementById("select-categoria");

const TIPO_CARTEIRA_MODAL_ACIONADOR = document.getElementById("linkCadastroTipoCarteira");
const MODAL_TIPO_CARTEIRA = document.getElementById("tipo_carteira_modal");
const GRAVAR_TIPO_CARTEIRA = document.getElementById("submitTipoCarteira");
const DESCRICAO_TIPO_CARTEIRA = document.getElementById("descricaoTipo");
const SELECT_TIPO_CARTEIRA = document.getElementById("tipoCarteira");

CATEGORIA_MODAL_ACIONADOR.addEventListener("click", () => {
    MODAL_CATEGORIA.showModal();
    DESCRICAO_CATEGORIA.value = ""; // Limpa o campo de descrição ao abrir o modal
});

const exibirMensagem = (mensagem, tipo) => {
    const mensagemElemento = document.getElementById('mensagem-formulario');
    mensagemElemento.innerText = mensagem;
    mensagemElemento.classList.add(tipo, 'personalizado');
    mensagemElemento.style.display = 'flex';

    setTimeout(() => {
        mensagemElemento.innerText = '';
        mensagemElemento.classList.remove(tipo, 'personalizado');
        mensagemElemento.style.display = 'none';
    }, 3000);
};

const insertCategoriaOption = (texto, valor) => {
    let opcao = document.createElement('option');
    opcao.innerText = texto;
    opcao.value = valor;
    opcao.selected = true;

    SELECT_CATEGORIA.appendChild(opcao);
    SELECT_CATEGORIA.value = valor;
};

const selectCategoria = (dados) => {
    let json = JSON.parse(dados);
    insertCategoriaOption(DESCRICAO_CATEGORIA.value, json.dados['codigo_categoria']);
    MODAL_CATEGORIA.close();
};

GRAVAR_CATEGORIA.addEventListener("click", () => {
    let valor = DESCRICAO_CATEGORIA?.value ?? '';
    let valor2 = TIPO_CATEGORIA?.value ?? '';
    const pattern = /\S+/g;

    if ((valor.match(pattern)) && (valor.length > 0)) {
        let dados = { 'descricao': valor, 'tipo': valor2 };

        sendData('./cad-categorias-receitabd-popup.php', dados)
            .then(json => {
                if (json && json.status === 'success') {
                    selectCategoria(JSON.stringify(json));

                    exibirMensagem('Categoria cadastrada com sucesso', 'mensagem-sucesso');
                } else if (json && json.mensagem) {
                    exibirMensagem(json.mensagem, 'mensagem-erroo');
                } else {
                    exibirMensagem('Não foi possível gravar a Categoria Informada!', 'mensagem-erro');
                }
            })
            .catch(error => {
                console.error('Erro ao gravar a Categoria:', error);
                exibirMensagem('Não foi possível gravar a Categoria Informada!', 'mensagem-erro');
            });
    } else {
        exibirMensagem('Descrição da categoria inválida!', 'mensagem-erro');
    }
});

CARTEIRA_MODAL_ACIONADOR.addEventListener("click", () => {
    MODAL_CARTEIRA.showModal();
    DESCRICAO_CARTEIRA.value = ""; // Limpa o campo de descrição ao abrir o modal
});


const insertCarteiraOption = (texto, valor) => {
    let opcao = document.createElement('option');
    opcao.innerText = texto;
    opcao.value = valor;
    opcao.selected = true;
    SELECT_CARTEIRA.appendChild(opcao);
    SELECT_CARTEIRA.value = valor;
};

const selectCarteira = (dados) => {
    let json = JSON.parse(dados);
    insertCarteiraOption(DESCRICAO_CARTEIRA.value, json.dados['codigo_carteira']);
    MODAL_CARTEIRA.close();
};

GRAVAR_CARTEIRA.addEventListener('click', () => {
    let valor = DESCRICAO_CARTEIRA.value;
    const pattern = /\S+/g;

    if ((valor.match(pattern)) && (valor.length > 0)) {
        let dados = { 'descricao': valor, 'tipo': SELECT_TIPO.value };

        sendData('./cad-carteirasbd-popup.php', dados)
            .then(json => {
                const mensagemElemento = document.getElementById('mensagem-formulario');

                if (json && json.status === 'success') {
                    selectCarteira(JSON.stringify(json));

                    mensagemElemento.innerText = 'Carteira cadastrada com sucesso';
                    mensagemElemento.classList.add('mensagem-sucesso', 'personalizado');
                    mensagemElemento.style.display = 'flex';

                    setTimeout(() => {
                        mensagemElemento.innerText = '';
                        mensagemElemento.classList.remove('mensagem-sucesso', 'personalizado');
                        mensagemElemento.style.display = 'none';
                    }, 5000);
                } else if (json && json.mensagem) {
                    mensagemElemento.innerText = json.mensagem;
                    mensagemElemento.classList.add('mensagem-sucesso', 'personalizado');
                    mensagemElemento.style.display = 'flex';

                    setTimeout(() => {
                        mensagemElemento.innerText = '';
                        mensagemElemento.classList.remove('mensagem-erroo', 'personalizado');
                        mensagemElemento.style.display = 'none';
                    }, 3000);
                } else {
                    mensagemElemento.innerText = 'Erro inesperado ao cadastrar a carteira.';
                    mensagemElemento.classList.add('mensagem-erroo', 'personalizado');
                    mensagemElemento.style.display = 'flex';

                    setTimeout(() => {
                        mensagemElemento.innerText = '';
                        mensagemElemento.classList.remove('mensagem-erroo', 'personalizado');
                        mensagemElemento.style.display = 'none';
                    }, 3000);
                }
            })
            .catch(error => {
                console.error('Erro ao enviar dados - erro da CARTEIRA:', error);
                const mensagemElemento = document.getElementById('mensagem-formulario');
                mensagemElemento.innerText = 'Erro ao enviar dados - erro da CARTEIRA';
                mensagemElemento.classList.add('mensagem-erro', 'personalizado');
                mensagemElemento.style.display = 'flex';

                setTimeout(() => {
                    mensagemElemento.innerText = '';
                    mensagemElemento.classList.remove('mensagem-erro', 'personalizado');
                    mensagemElemento.style.display = 'none';
                }, 3000);
            });
    }
});

const FECHAR_CATEGORIA = document.getElementById("fecharCategoria");
const FECHAR_CARTEIRA = document.getElementById("fecharCarteira");
const FECHAR_TIPO_CARTEIRA = document.getElementById("fecharTipoCarteira");

FECHAR_CATEGORIA.addEventListener("click", () => {
    MODAL_CATEGORIA.close();
    DESCRICAO_CATEGORIA.value = "";
});

FECHAR_CARTEIRA.addEventListener("click", () => {
    MODAL_CARTEIRA.close();
    DESCRICAO_CARTEIRA.value = "";
});

FECHAR_TIPO_CARTEIRA.addEventListener("click", () => {
    MODAL_TIPO_CARTEIRA.close();
    DESCRICAO_TIPO_CARTEIRA.value = "";
});

TIPO_CARTEIRA_MODAL_ACIONADOR.addEventListener("click", () => {
    MODAL_TIPO_CARTEIRA.showModal();
    DESCRICAO_TIPO_CARTEIRA.value = "";
});

const insertTipoCarteiraOption = (texto, valor) => {
    let opcao = document.createElement('option');
    opcao.innerText = texto;
    opcao.value = valor;
    opcao.selected = true;
    SELECT_TIPO_CARTEIRA.appendChild(opcao);
    SELECT_TIPO_CARTEIRA.value = valor;
};

const selectTipoCarteira = (dados) => {
    let json = JSON.parse(dados);
    insertTipoCarteiraOption(DESCRICAO_TIPO_CARTEIRA.value, json.dados['codigo_tipo_carteira']);
    MODAL_TIPO_CARTEIRA.close();
};

GRAVAR_TIPO_CARTEIRA.addEventListener('click', () => {
    let valor = DESCRICAO_TIPO_CARTEIRA.value;
    const pattern = /\S+/g;

    if ((valor.match(pattern)) && (valor.length > 0)) {
        let dados = { 'descricao': valor };
        sendData('./cad-tipo-contasbd-popup.php', dados)
            .then(json => {
                const mensagemElemento = document.getElementById('mensagem-formulario');

                if (json && json.status === 'success') {
                    mensagemElemento.innerText = 'Tipo de Carteira cadastrada com sucesso';
                    mensagemElemento.classList.add('mensagem-sucesso', 'personalizado');
                    mensagemElemento.style.display = 'flex';
                    selectTipoCarteira(JSON.stringify(json));

                    setTimeout(() => {
                        mensagemElemento.innerText = '';
                        mensagemElemento.classList.remove('mensagem-sucesso', 'personalizado');
                        mensagemElemento.style.display = 'none';
                    }, 3000);
                } else if (json && json.mensagem) {
                    mensagemElemento.innerText = json.mensagem;
                    mensagemElemento.classList.add('mensagem-erroo', 'personalizado');
                    mensagemElemento.style.display = 'flex';

                    setTimeout(() => {
                        mensagemElemento.innerText = '';
                        mensagemElemento.classList.remove('mensagem-erroo', 'personalizado');
                        mensagemElemento.style.display = 'none';
                    }, 3000);
                } else {
                    mensagemElemento.innerText = 'Erro inesperado ao cadastrar a carteira.';
                    mensagemElemento.classList.add('mensagem-erroo', 'personalizado');
                    mensagemElemento.style.display = 'flex';

                    setTimeout(() => {
                        mensagemElemento.innerText = '';
                        mensagemElemento.classList.remove('mensagem-erroo', 'personalizado');
                        mensagemElemento.style.display = 'none';
                    }, 3000);
                }
            })
            .catch(error => {
                console.error('Erro ao enviar dados - erro da CARTEIRA:', error);
                const mensagemElemento = document.getElementById('mensagem-formulario');
                mensagemElemento.innerText = 'Erro ao enviar dados - erro da CARTEIRA';
                mensagemElemento.classList.add('mensagem-erro', 'personalizado');
                mensagemElemento.style.display = 'flex';

                setTimeout(() => {
                    mensagemElemento.innerText = '';
                    mensagemElemento.classList.remove('mensagem-erro', 'personalizado');
                    mensagemElemento.style.display = 'none';
                }, 3000);
            });
    }
});

GRAVAR_CARTEIRA_COM_TIPO.addEventListener('click', gravarCarteiraComTipoNovo);

function gravarCarteiraComTipoNovo() {
    let valor = DESCRICAO_CARTEIRA.value;
    const pattern = /\S+/g;

    if ((valor.match(pattern)) && (valor.length > 0)) {
        let dados = { 'descricao': valor, 'tipo': DESCRICAO_TIPO_CARTEIRA.value };
        sendData('./cad-carteirasbd-popup.php', dados)
            .then(json => {
                if (json && json.status) {
                    let data = JSON.stringify(json);
                    console.log(data);
                    if (data.includes('dados')) {
                        selectCarteira(data);

                        if (json.status === 'success') {
                            document.getElementById('mensagem-formulario').innerText = 'Carteira cadastrada com sucesso';
                            document.getElementById('mensagem-formulario').classList.add('mensagem-sucesso', 'personalizado');

                            setTimeout(() => {
                                document.getElementById('mensagem-formulario').innerText = '';
                                document.getElementById('mensagem-formulario').classList.remove('mensagem-sucesso', 'personalizado');
                            }, 3000);
                            MODAL_CARTEIRA.close();
                            selectCarteira(data);
                        } else {
                            document.getElementById('mensagem-formulario').innerText = json.mensagem;
                            document.getElementById('mensagem-formulario').classList.add('mensagem-erro', 'personalizado');
                        }
                    }
                } else {
                    console.error('O objeto JSON retornado é indefinido ou não possui a propriedade "status"');
                }
            })
            .catch(error => {
                console.error('Erro ao enviar dados - erro da CARTEIRA:', error);
            });
    }
};