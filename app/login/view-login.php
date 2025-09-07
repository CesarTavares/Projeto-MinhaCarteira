<?php
require("./_sessao.php");
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minha Carteira - Consulta de Usuários</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./css/estilo.css">
    <link rel="shortcut icon" href="../../logo_minha_carteira_ICO.ico" type="image/x-icon">

    <style>
        #sucesso {
            background-color: lightgreen;
            color: green;
            border: 1px solid green;
            width: 500px;
            margin: 0 auto;
            margin-top: 60px;
            height: 60px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 26px;
            border-radius: 5px;
            text-align: center;
            opacity: 1;
            transition: opacity 1.9s ease;
        }

        #excsucesso {
            background-color: lightgreen;
            color: green;
            border: 1px solid green;
            width: 500px;
            margin: 0 auto;
            margin-top: 60px;
            height: 60px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 26px;
            border-radius: 5px;
            text-align: center;
            opacity: 1;
            transition: opacity 1.9s ease;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Minha Carteira - Controle Financeiro Pessoal</h1>        
        <h1>Consulta de Usuários</h1>
        <br>
        <?php include("./_menu-telas-consultas.php"); ?>
        <br>
    </div>

            <?php
                $status = filter_input(INPUT_GET, 'status', FILTER_SANITIZE_SPECIAL_CHARS);
                if (isset($status) && ($status == "sucesso")) {
                    echo '<div id="sucesso">Usuário cadastrado com sucesso!</div>';
                }
                if (isset($status) && ($status == "excsucesso")) {
                    echo '<div id="excsucesso">Usuário Excluído com sucesso!</div>';
                }
                if (isset($status) && ($status == "altsucesso")) {
                    echo '<div id="excsucesso">Usuário Alterado com sucesso!</div>';
                }

                if (isset($_GET['status']) && $_GET['status'] === 'altNever') {
                    echo '<div id="altNever">Nenhuma alteração foi realizada pelo Usuário.</div>';
                }
            ?>

    <script>
        window.addEventListener('DOMContentLoaded', function() {
            var mensagem = document.getElementById('sucesso');
            sucesso.style.display = 'block';

            setTimeout(function() {
                mensagem.style.opacity = 0;
                setTimeout(function() {
                    mensagem.style.display = 'none';
                }, 900);
            }, 5000);
        });

        window.addEventListener('DOMContentLoaded', function() {
            var mensagem = document.getElementById('excsucesso');
            excsucesso.style.display = 'block';

            setTimeout(function() {
                mensagem.style.opacity = 0;
                setTimeout(function() {
                    mensagem.style.display = 'none';
                }, 900);
            }, 4000);
        });

        window.addEventListener('DOMContentLoaded', function() {
            var mensagem = document.getElementById('altsucesso');
            excsucesso.style.display = 'block';

            setTimeout(function() {
                mensagem.style.opacity = 0;
                setTimeout(function() {
                    mensagem.style.display = 'none';
                }, 900);
            }, 4000);
        });

        window.addEventListener('DOMContentLoaded', function() {
            var mensagem = document.getElementById('altNever');
            altNever.style.display = 'block';

            setTimeout(function() {
                mensagem.style.opacity = 0;
                setTimeout(function() {
                    mensagem.style.display = 'none';
                }, 900);
            }, 4000);
        });
    </script>
    
    <div class="containercad">
        <div class="row-flex">
            <div class="col">
                <label for="filtro" style="display: inline-block; width: 450px;">Pesquise por Nome do Usuário:</label>
                <div style="position: relative;">
                    <input type="text" id="filtro" placeholder="Digite o Nome do Usuário">
                    <span id="limpar-filtro" style="position: absolute; right: 5px; top: 50%; transform: translateY(-50%); cursor: pointer;">Limpar</span>
                </div>
            </div>
        </div>
    </div>

    <div class="centralizar-v">
        <table id="tabela-usuarios">
            <thead>

                <tr class="usuario">                    
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Nível</th>
                    <th>Status</th>
                    <th width="90">Atualizar</th>
                    <th width="90">Excluir</th>
                </tr>

            </thead>
        <tbody>

            <?php
                require_once("./view-loginbd.php");

                if ($totalRegistros > 0) {
                    foreach ($dados as $linha) {
                        $codigoUsuario = $linha['codigo'];
                        $nomeUsuario = $linha['nome'];
                        $emailUsuario = $linha['email'];
                    

                        // Verificar se usuário já tem algo movimentado dentro do Sistema
                        $verificarVinculo = $conexao->prepare("
                            SELECT SUM(total) FROM (
                                SELECT COUNT(*) AS total FROM lancamentos_despesas WHERE codigo_usuario = :codigo 
                                UNION ALL
                                SELECT COUNT(*) FROM lancamentos_receitas WHERE codigo_usuario = :codigo 
                                UNION ALL
                                SELECT COUNT(*) FROM categorias WHERE codigo_usuario = :codigo 
                                UNION ALL
                                SELECT COUNT(*) FROM carteiras WHERE codigo_usuario = :codigo 
                                UNION ALL
                                SELECT COUNT(*) FROM tipos_carteiras WHERE codigo_usuario = :codigo 
                            ) AS combined_counts
                        ");

                        $verificarVinculo->bindParam(':codigo', $codigoUsuario, PDO::PARAM_INT);
                        $verificarVinculo->execute();
                        $vinculado = $verificarVinculo->fetchColumn();

             ?>

        <tr class="usuario">
            <td><?= $linha["nome"]; ?></td>
            <td><?= $linha["email"]; ?></td>
            <td><?= $linha["nivel"]; ?></td>
            <td><?= $linha["status"]; ?></td>
            <td align="center">
                <a href="alt-login.php?codigo=<?= $linha['codigo']; ?>">
                    <img src="../../btn_editar.png" alt="Atualizar" width="30">
                </a>
            </td>

            <td align="center">
                <?php if ($vinculado == 0) : ?>
                    <!--Exibe o botão de excluir somente se este usuário não tiver nenhum movimento dentro do sistema para ele.-->
                    <a href="exc-login.php?codigo=<?= $codigoUsuario; ?>">
                                        <img src="../../btn_excluir.png" alt="Excluir" width="30">
                                    </a>
                <?php else : ?>
                    <!--Desabilita o botão de excluir se tiver algum vínculo-->
                    <button type="button" class="disabled-button-usuario" disabled>
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
                                            Não existe dados gravados!
                                        </td>
                                    </tr>
                                 ");
                }
                ?>
            </tbody>
        </table>
    </div>
    <br><br>
    </div>
    <div class="containercad" style="margin-top: 35px;">
        <form>
            <div class="voltar" style="background-color: dimgray">
                <input type="submit" style="background-color:dimgray; border: 1px solid dimgray; font-size: 16px" value="Fechar" formaction="../../tela-inicial.php">
            </div>
        </form>
        <div class="espaco-m"></div>
        <a href="./cad-login.php"><input type="submit" style="background-color: green; border: 1px solid green; font-size: 16px" value="Adicionar"></a>    

    <script>
        document.getElementById('filtro').addEventListener('input', function() {
            var filtro = this.value.toLowerCase();
            var usuarios = document.querySelectorAll('#tabela-usuarios tbody .usuario'); // Corrigido para .usuario

            usuarios.forEach(function(usuario) {
                var nomeUsuario = usuario.querySelector('td:nth-child(1)').textContent.toLowerCase(); // Seleção da segunda coluna (Nome)

                if (nomeUsuario.includes(filtro)) {
                    usuario.style.display = 'table-row';
                } else {
                    usuario.style.display = 'none';
                }
            });

            var tabela = document.getElementById('tabela-usuarios');
            tabela.classList.add('show');
        });

        //Javascript para Limpar o filtro principal
        var inputFiltro = document.getElementById('filtro');
        var limparFiltro = document.getElementById('limpar-filtro');

        limparFiltro.addEventListener('click', function() {
            inputFiltro.value = ''; // Limpar o valor do campo de filtro
            location.reload(); // Recarregar a página
        });

        function filtrarTabela() {
            var filtro = inputFiltro.value.toLowerCase();
            var usuarios = document.querySelectorAll('#tabela-usuarios tbody .usuario');

            usuarios.forEach(function(usuario) {
                var descricaoCarteira = usuario.querySelectorAll('td')[0].textContent.toLowerCase(); // Segunda coluna

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

        // Chame a função de filtragem ao carregar a página para garantir que a tabela seja inicialmente filtrada
        document.addEventListener('DOMContentLoaded', filtrarTabela);
    </script>

    <!-- <br><br><br><br><br><br><br><br><br><br>
    <div class="footer">
        <?php include("./_footer.php"); ?> 
    </div> -->
</body>

</html>