<?php
require_once dirname(__DIR__) . "/vendor/autoload.php";
require_once("./login/_sessao.php");
require_once("./login/funcoes_gerais.php");
require_once("./login/ver_saldo.php");

use App\Database\Connection;

$conexao = Connection::get();

// Configurações para o template
$pageTitle = 'Minha Carteira - Tela Inicial';
$basePath = './login';
$contentFile = __DIR__ . '/templates/pages/dashboard-content.php';

// JavaScript customizado para a tela inicial
$customJS = '
    // Função para controlar o tempo e forma que aparece a mensagem de inclusão com sucesso (verde)
    window.addEventListener("DOMContentLoaded", function() {
        var mensagem = document.getElementById("sucesso");
        if (mensagem) {
            mensagem.style.display = "block";

            setTimeout(function() {
                mensagem.style.opacity = 0;
                setTimeout(function() {
                    mensagem.style.display = "none";
                }, 650);
            }, 1650);
        }
    });

    var statusFields = document.getElementsByClassName("status");
    for (var i = 0; i < statusFields.length; i++) {
        var fieldValue = statusFields[i].textContent.toLowerCase();
        if (fieldValue === "pago") {
            statusFields[i].classList.add("verde");
        } else if (fieldValue === "em aberto") {
            statusFields[i].classList.add("vermelho");
        }
    }

    function mostrarTabelaSelecionada() {
        var opcaoSelecionada = document.getElementById("opcoes").value;
        var tabelaUsuarios = document.getElementById("tabela-usuarios");
        var lancamentos7dias = document.getElementById("lancamentos-7dias");
        var lancamentosDeHoje = document.getElementById("lancamentos-de-hoje");
        var tabelaMesAnterior = document.getElementById("tabela-mes-anterior");
        var paragrafoLancamentos7Dias = document.querySelector(".paragrafo-lancamentos7dias");
        var paragrafoLancamentosDeHoje = document.querySelector(".paragrafo-lancamentosDeHoje");
        var paragrafoLancamentosMesAnterior = document.querySelector(".paragrafo-lancamentosMesAnterior");

        // Oculta todas as tabelas e parágrafos
        var tabelas = document.getElementsByTagName("table");
        for (var i = 0; i < tabelas.length; i++) {
            tabelas[i].style.display = "none";
        }

        var paragrafos = document.querySelectorAll(".paragrafo-lancamentos7dias, .paragrafo-lancamentosDeHoje, .paragrafo-lancamentosMesAnterior");
        for (var i = 0; i < paragrafos.length; i++) {
            paragrafos[i].style.display = "none";
        }

        // Exibe a tabela selecionada e o parágrafo correspondente
        if (opcaoSelecionada === "tabela-usuarios") {
            tabelaUsuarios.style.display = "table";
        } else if (opcaoSelecionada === "lancamentos-7dias") {
            lancamentos7dias.style.display = "table";
            paragrafoLancamentos7Dias.style.display = "block";
        } else if (opcaoSelecionada === "lancamentos-de-hoje") {
            lancamentosDeHoje.style.display = "table";
            paragrafoLancamentosDeHoje.style.display = "block";
        } else if (opcaoSelecionada === "tabela-mes-anterior") {
            tabelaMesAnterior.style.display = "table";
            paragrafoLancamentosMesAnterior.style.display = "block";
        }
    }

    function filtrarPorAno() {
        var anoSelecionado = document.getElementById("anoSelect").value;

        // Fazer requisição AJAX para atualizar o saldo
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "atualizar_saldo.php?ano=" + encodeURIComponent(anoSelecionado), true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                // Atualizar o saldo na tela
                var saldoElemento = document.getElementById("saldoAtual");
                if (saldoElemento) {
                    saldoElemento.innerHTML = xhr.responseText;
                }
            }
        };
        xhr.send();
    }
';

// Inclui o template base
require_once __DIR__ . '/templates/base.php';
?>