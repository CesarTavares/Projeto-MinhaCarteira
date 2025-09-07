<?php
require_once("./_sessao.php");
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minha Carteira - Consulta de Categorias</title>
    <link rel="stylesheet" href="./css/estilo.css">
    <link rel="shortcut icon" href="../../logo_minha_carteira_ICO.ico" type="image/x-icon">
</head>

<body>
    <div class="container">
        <h1>Minha Carteira - Controle Finaceiro Digital</h1>
        <h1>Consulta de Categorias</h1>
        
        <?php include("./_menu-telas-consultas.php");

        $status = filter_input(INPUT_GET, 'status', FILTER_SANITIZE_SPECIAL_CHARS);
        if (isset($status) && ($status == "sucesso")) {
            echo '<div id="sucesso">Categoria cadastrada com Sucesso!</div>';
        }
        if (isset($status) && ($status == "excsucesso")) {
            echo '<div id="excsucesso">Categoria Excluída com sucesso!</div>';
        }
        if (isset($status) && ($status == "altsucesso")) {
            echo '<div id="excsucesso">Categoria Alterada com sucesso!</div>';
        }
        if (isset($status) && ($status == "altNever")) {
            echo '<div id="altNever">Nenhuma Alteração realizada pelo Usuário!</div>';
        }

        if (isset($status) && ($status == "excinsucesso")) {
            echo '<div id="excinsucesso">Não foi possível excluir o Registro!</div>';
        }

        ?>
    </div>
    <br>
    <div class="containercad">
        <div class="row-flex">
            <div class="col">
                <label for="filtro" style="display: inline-block; width: 450px;">Pesquise por Descrição da Categoria:</label>
                <div style="position: relative;">
                    <input type="text" id="filtro" placeholder="Digite a Descrição da Cartegoria">
                    <span id="limpar-filtro" style="position: absolute; right: 5px; top: 50%; transform: translateY(-50%); cursor: pointer;">Limpar</span>
                </div>

            </div>
        </div>
    </div>
    <div class="centralizar-v">
        <table id="tabela-contas">
            <thead>
                <tr class="contas">
                    <!-- <th>Código</th> -->
                    <th>Descrição da Categoria</th>
                    <th>Tipo</th>
                    <th width="90">Atualizar</th>
                    <th width="90">Excluir</th>
                </tr>
            </thead>
            <tbody>

                <?PHP
                require_once("./view-categoriasbd.php");
                if ($totalRegistros > 0) {
                    foreach ($dados as $linha) {
                        $codigoCategoria = $linha["codigo"];
                        $descricao = $linha["descricao"];
                        $tipo = $linha["tipo"];

                        // Verificar se categoria está vinculada a algum registro de lancamentos
                            $verificarVinculo = $conexao->prepare("SELECT SUM(total) FROM (
                                SELECT COUNT(*) AS total FROM lancamentos_despesas WHERE categoria = :codigo 
                                UNION ALL
                                SELECT COUNT(*) AS total FROM lancamentos_receitas WHERE categoria = :codigo
                            ) AS combined_counts");
                        $verificarVinculo->bindParam(':codigo', $codigoCategoria, PDO::PARAM_INT);
                        $verificarVinculo->execute();
                        $vinculado = $verificarVinculo->fetchColumn();
                                    ?>

                        <tr class="contas">
                            <!-- <td><?= $linha["codigo"]; ?></td> -->
                            <td><?= $linha["descricao"]; ?></td>
                            <td><?= $linha["tipo"]; ?></td>

                            <td align="center">
                                <a href="alt-categorias.php?codigo=<?= $linha['codigo']; ?>">
                                    <img src="../../btn_editar.png" alt="Atualizar" width="30">
                                </a>
                            </td>

                            <td align="center">
                                <?php if ($vinculado == 0) : ?>
                                    <!--Exibi o botão de excluir somente se não estiver vinculado a nenhum lançamento.-->
                                    <a href="exc-categorias.php?codigo=<?= $codigoCategoria; ?>">
                                        <img src="../../btn_excluir.png" alt="Excluir" width="30">
                                    </a>
                                <?php else : ?>
                                    <!--Desabilita o botão de excluir se tiver algum vinco-->
                                    <button type="button" class="disabled-button-categorias" disabled>
                                        <img src="../../btn_excluir.png" alt="Excluir" width="30">
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                <?php
                    }
                } else {
                    echo ("
                                    <tr>
                                        <td colspan=6>
                                            NÃO HÁ REGISTROS GRAVADOS.
                                        </td>
                                    </tr>
                                 ");
                }
                ?>
            </tbody>
        </table>

        <div class="containercad" style="margin-top: 35px;">
            <form>
                <div class="voltar" style="background-color: dimgray">
                    <input type="submit" style="background-color:dimgray; border: 1px solid dimgray; font-size: 16px" value="Fechar" formaction="../../tela-inicial.php">
                </div>
            </form>
            <div class="espaco-m"></div>
            <a href="./cad-categorias.php"><input type="submit" style="background-color: green; border: 1px solid green; font-size: 16px" value="Adicionar"></a>
        </div>
    </div>

    <script>
        //função para controlar o tempo e forma que aparece a mensagem de inclusão com sucesso(verde)
        window.addEventListener('DOMContentLoaded', function() {
            var mensagem = document.getElementById('sucesso');
            sucesso.style.display = 'block';

            setTimeout(function() {
                mensagem.style.opacity = 0;
                setTimeout(function() {
                    mensagem.style.display = 'none';
                }, 320);
            }, 3000);
        });

        //função para controlar o tempo e forma que aparece a mensagens de insucesso
        window.addEventListener('DOMContentLoaded', function() {
            var mensagem = document.getElementById('altinsucesso');
            excsucesso.style.display = 'block';

            setTimeout(function() {
                mensagem.style.opacity = 0;
                setTimeout(function() {
                    mensagem.style.display = 'none';
                }, 320);
            }, 3000);
        });

        //função para controlar o tempo e forma que aparece a mensagens de exclusão
        window.addEventListener('DOMContentLoaded', function() {
            var mensagem = document.getElementById('excsucesso');
            excsucesso.style.display = 'block';

            setTimeout(function() {
                mensagem.style.opacity = 0;
                setTimeout(function() {
                    mensagem.style.display = 'none';
                }, 320);
            }, 3000);
        });

        //função para controlar o tempo e forma que aparece a mensagens de exclusão
        window.addEventListener('DOMContentLoaded', function() {
            var mensagem = document.getElementById('altNever');
            altNever.style.display = 'block';

            setTimeout(function() {
                mensagem.style.opacity = 0;
                setTimeout(function() {
                    mensagem.style.display = 'none';
                }, 320);
            }, 3000);
        });

        //Javascript para filtrar apensa na segunda coluna da tabela
        document.getElementById('filtro').addEventListener('input', function() {
            var filtro = this.value.toLowerCase();
            var contas = document.querySelectorAll('#tabela-contas tbody .contas');

            contas.forEach(function(conta) {
                var descricao = conta.querySelector('td:nth-child(1)').textContent.toLowerCase();

                if (descricao.includes(filtro)) {
                    conta.style.display = 'table-row';
                } else {
                    conta.style.display = 'none';
                }
            });

            var tabela = document.getElementById('tabela-contas');
            tabela.classList.add('show');
        });

        //Javascript para Limpar o filtro principal
        var inputFiltro = document.getElementById('filtro');
        var limparFiltro = document.getElementById('limpar-filtro');

        limparFiltro.addEventListener('click', function() {
            inputFiltro.value = '';
            location.reload();
        });

        function filtrarTabela() {
            var filtro = inputFiltro.value.toLowerCase();
            var usuarios = document.querySelectorAll('#tabela-usuarios tbody .usuario');

            usuarios.forEach(function(usuario) {
                var descricaoCarteira = usuario.querySelectorAll('td')[1].textContent.toLowerCase();

                if (descricaoCarteira.includes(filtro)) {
                    usuario.style.display = 'table-row';
                } else {
                    usuario.style.display = 'none';
                }
            });

            var tabela = document.getElementById('tabela-usuarios');
            tabela.classList.add('show');
        }

        inputFiltro.addEventListener('input', filtrarTabela);
        document.addEventListener('DOMContentLoaded', filtrarTabela);
    </script>
</body>
</html>