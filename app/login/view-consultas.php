<?php
require("./_sessao.php");
require_once("./view-lancamentosbd.php");

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minha Carteira - Consulta de Lançamentos</title>
    <link rel="stylesheet" href="./css/estilo.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="shortcut icon" href="../../logo_minha_carteira_ICO.ico" type="image/x-icon">
</head>
<body>
    <div class="container">        
            <h1>Minha Carteira - Controle Financeiro Pessoal</h1>
            <h1>Consulta de Lançamentos</h1>
            <?php include ("./_menu-telas-consultas.php"); ?>
    </div>
    <div class="centralizar-tabela-lancamentos">
        <h2>Filtrar por Ano e Mês</h2>

        <form method="GET" action="">
            <label for="ano">Ano:</label>
            <select name="ano" id="ano">
                <option value="">Todos</option>
                <?php
                // Gera lista dinâmica de anos
                $anoAtual = date("Y");
                for ($ano = $anoAtual; $ano >= 2000; $ano--) {
                    $selecionado = (isset($_GET['ano']) && $_GET['ano'] == $ano) ? "selected" : "";
                    echo "<option value='$ano' $selecionado>$ano</option>";
                }
                ?>
            </select>

            <label for="mes">Mês:</label>
            <select name="mes" id="mes">
                <option value="">Todos</option>
                <?php
                $meses = [
                    "01" => "Janeiro", "02" => "Fevereiro", "03" => "Março",
                    "04" => "Abril", "05" => "Maio", "06" => "Junho",
                    "07" => "Julho", "08" => "Agosto", "09" => "Setembro",
                    "10" => "Outubro", "11" => "Novembro", "12" => "Dezembro"
                ];
                foreach ($meses as $num => $nome) {
                    $selecionado = (isset($_GET['mes']) && $_GET['mes'] == $num) ? "selected" : "";
                    echo "<option value='$num' $selecionado>$nome</option>";
                }
                ?>
            </select>
                <div class="espaco-m"></div>
                <a href="./ca"></a>
            <button type="submit">Selecionar</button>
            
        </form>
    </div>
   
    




    
    <div class="footer">
        <!-- <?php include("./_footer.php"); ?>  -->
    </div>

</body>
</html>



