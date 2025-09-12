<?php
require_once("_sessao.php");
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minha Carteira - Atualização de Carteira</title>
    <link rel="stylesheet" href="./css/estilo.css">
    <link rel="shortcut icon" href="../../logo_minha_carteira_ICO.ico" type="image/x-icon">
</head>

<body>
    <div class="container">
        <h1>Minha Carteira - Controle Financeiro Pessoal</h1>
        <h1>Atualização de Carteira</h1>
        <?php include("./_menu-telas-consultas.php"); ?>
    </div>

    <?php
    $codigo = filter_input(INPUT_GET, "codigo", FILTER_SANITIZE_NUMBER_INT);
    require_once("./alt-contas-view.php");
    ?><br><br>

    <div class="centralizar-v">
        <form action="alt-contasbd.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="codigo" value="<?php echo $codigo; ?>">
            <div class="row-flex">
                <div class="col" style="margin-top: 15px;">
                    <label for="descricao">Descrição da Carteira</label>
                    <input type="text" name="descricao" id="descricao" placeholder="Descriçao da Conta" value="<?= $resultado['descricao']; ?>">
                </div>
            </div><br>

            <div class="row-flex">
                <div class="col">
                    <div class="row-flex centralizar-h">
                        <div class="col" style="margin-top: 15px;">
                            <label for="tipo_carteira">Tipo de Carteira de Pagamento</label>
                            <select name="tipo_carteira" id="tipo_carteira">
                                <?php
                                // Obtém o código da sessão do usuário
                                $codigo_usuario = $_SESSION['codigo'];

                                try {
                                    require_once("./_conexao/conexao.php");
                                    $stmt = $conexao->prepare("SELECT codigo, descricao FROM tipos_carteiras WHERE codigo_usuario = :usuario ORDER BY descricao ASC");
                                    $stmt->bindValue(':usuario', $codigo_usuario, PDO::PARAM_INT);
                                    $stmt->execute();
                                    while ($row = $stmt->fetch()) {
                                        $idCarteira = (int)$row['codigo'];
                                        $nomeCarteira = htmlspecialchars($row['descricao']);
                                        $selecionado = ($idCarteira == $resultado['codigo_tipo_carteira']) ? 'selected' : '';
                                        echo "<option value=\"{$idCarteira}\" {$selecionado}>{$nomeCarteira}</option>";
                                    }
                                } catch (PDOException $e) {
                                    echo '<option value="">Erro ao carregar tipos de carteiras</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div><br>

            <div class="containercad" style="margin-top: 35px;">
                <form>
                    <div class="voltar" style="background-color: dimgray">
                        <input type="submit" style="background-color:dimgray; border: 1px solid dimgray; font-size: 16px" value="Fechar" formaction="../../tela-inicial.php">
                    </div>
                </form>

                <div class="espaco-m"></div>
                <a href="./view-contas.php"><input type="submit" style="background-color: green; border: 1px solid green; font-size: 16px" value="G R A V A R"></a>
            </div>
        </form>
    </div>
</body>

</html>