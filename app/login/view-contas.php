<?php
    require_once("./_sessao.php");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minha Carteira - Consulta de Carteiras</title>
    <link rel="stylesheet" href="./css/estilo.css">
    <link rel="shortcut icon" href="../../logo_minha_carteira_ICO.ico" type="image/x-icon">
</head>
<body>
    <div class="container">
        <h1>Minha Carteira - Controle Financeiro Pessoal</h1>
        <h1>Consulta de Carteiras</h1>
            
            <?php include("./_menu-telas-consultas.php"); 
            
                    $status = filter_input(INPUT_GET, 'status', FILTER_SANITIZE_SPECIAL_CHARS);
                    if(isset($status) && ($status=="sucesso")){
                        echo '<div id="sucesso">Carteira Cadastrada com Sucesso!</div>';
                    }
                    
                    if(isset($status) && ($status=="excsucesso")){
                        echo '<div id="excsucesso">Carteira Excluída com sucesso!</div>';
                    }
                    if(isset($status) && ($status=="altsucesso")){
                        echo '<div id="sucesso">Carteira Alterada com sucesso!</div>';
                    }
                    if(isset($status) && ($status=="altinsucesso")){
                        echo '<div id="altinsucesso">Inconsistência ao realizar a alteração!</div>';
                    }
                    if (isset($status) && ($status =="altNever")) {
                        echo '<div id="altNever">Nenhuma Alteração realizada pelo Usuário!</div>';
                    }
                    if(isset($status) && ($status=="cad_insucesso")){
                        echo '<div id="altinsucesso">Carteira não Cadastrada. Verifique!</div>';
                    }
            ?> 
    </div>
    <br>
    <div class="containercad">
        <div class="row-flex">
            <div class="col">
                <label for="filtro" style="display: inline-block; width: 450px;">Pesquise por Descrição de Carteira:</label>
                    <div style="position: relative;">
                        <input type="text" id="filtro" placeholder="Digite a Descrição da Carteira:">
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
                    <th>Descrição da Carteira</th>
                    <th>Tipo</th>
                    <th width="90">Atualizar</th>
                    <th width="90">Excluir</th>
                </tr>
            </thead>
        <tbody>

                <?PHP
                    require_once("./view-contasbd.php");
                    if ($totalRegistros > 0) {
                        foreach ($dados as $linha) {
                            $codigoCarteira = $linha["codigo"];
                            $descricao = $linha["descricao"];
                            $codigoTipoCarteira = $linha["codigo_tipo_carteira"];
                    
                            // Verificar se o tipo de carteira está vinculado na tabela lancamentos_despesas
                            // Verificar se categoria está vinculada a algum registro de lancamentos
                            $verificarVinculo = $conexao->prepare("SELECT SUM(total) FROM (
                                SELECT COUNT(*) AS total FROM lancamentos_despesas WHERE carteira = :codigo 
                                UNION ALL
                                SELECT COUNT(*) AS total FROM lancamentos_receitas WHERE carteira = :codigo
                            ) AS combined_counts");
                            $verificarVinculo->bindParam(':codigo', $codigoCarteira, PDO::PARAM_INT);
                            $verificarVinculo->execute();
                            $vinculado = $verificarVinculo->fetchColumn();
                            ?>
                            <tr class="contas">
                                <!-- <td><?= $codigoCarteira; ?></td> -->
                                <td><?= $descricao; ?></td>
                                <td><?= $codigoTipoCarteira; ?></td>
                                <td align="center">
                                    <a href="alt-contas.php?codigo=<?= $codigoCarteira; ?>">
                                        <img src="../../btn_editar.png" alt="Atualizar" width="30">
                                    </a>
                                </td>
                                <td align="center">
                                    
                                <?php if ($vinculado == 0) : ?>
                                        <!-- Exibir o botão de excluir somente se não estiver vinculado -->
                                        <a href="exc-contas.php?codigo=<?= $codigoCarteira; ?>">
                                            <img src="../../btn_excluir.png" alt="Excluir" width="30">
                                        </a>
                                    <?php else : ?>
                                        <!-- Desabilitar o botão de excluir se estiver vinculado -->
                                        <button type="button" class="disabled-button-carteiras" disabled>
                                            <img src="../../btn_excluir.png" alt="Excluir" width="30">
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                    <?php
                        }
                    } else {
                        echo("
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

        <div class="containercad"  style="margin-top: 35px;"> 
            <form>
                <div class="voltar" style="background-color: dimgray">
                    <input type="submit" 
                        style="background-color:dimgray; border: 1px solid dimgray; font-size: 16px"
                        value="Fechar" formaction="../../tela-inicial.php">
                </div>
            </form>

            <div class="espaco-m"></div>
                <a href="./cad-contas.php"><input type="submit" 
                    style="background-color: green; border: 1px solid green; font-size: 16px" 
                    value="Adicionar"></a>
        </div>         
    </div>

                <script>
                        //Javascript para filtra a segunda coluna da tabela
                            document.getElementById('filtro').addEventListener('input', function() {
                            var filtro = this.value.toLowerCase();
                            var contas = document.querySelectorAll('#tabela-contas tbody .contas');

                            contas.forEach(function(conta) {
                            var nomeConta = conta.querySelector('td:nth-child(1)').textContent.toLowerCase();

                            if (nomeConta.includes(filtro)) {
                                conta.style.display = 'table-row';
                                } else {
                                conta.style.display = 'none';
                            }
                            });

                        var tabela = document.getElementById('tabela-contas');
                        tabela.classList.add('show');
                        });

                        //Javascript para mensagem de cadastro aparecer por determinado tempo
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

                        //Javascript para mensagem de exclusão aparecer por determinado tempo
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

                        //Javascript para mensagem de exclusão aparecer por determinado tempo
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

                        //Javascript para mensagem de exclusão aparecer por determinado tempo
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