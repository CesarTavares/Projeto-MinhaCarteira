<?php
require_once("./_sessao.php");
//Caregar o Composer
require "../../vendor/autoload.php";
use Dompdf\Dompdf;
use Dompdf\Options;

//incluir conexão com o BD
include_once './_conexao/conexao.php';
require_once("funcoes_gerais.php");

// Verificar se o usuário está logado e recuperar o nome do usuário da sessão
if (isset($_SESSION['nome'])) {
    $nome_usuario = $_SESSION['nome'];
} else {
    // Se o usuário não estiver logado, redirecionar para a página de login ou mostrar uma mensagem de erro
    echo "Usuário não logado.";
    exit;
}

//Receber da URL a Categoria 
$data_inicio = isset($_GET['data_inicio']) ? $_GET['data_inicio'] : '';
$data_final = isset($_GET['data_final']) ? $_GET['data_final'] : '';

$data_atual = date("d/m/Y");

$idUsuario = $_SESSION['codigo'];

if ($data_inicio && $data_final) {
    try {
//QUERY para recuperar os registros do banco de dados
$sql_lancamentos = "SELECT des.descricao, valor, data_credito, cat.descricao as descricao_categoria, car.descricao as descricao_carteira, situacao
                              FROM lancamentos_receitas des
                              JOIN categorias cat on des.categoria = cat.codigo 
                              JOIN carteiras car on des.carteira = car.codigo 
                              WHERE data_credito BETWEEN :data_inicio AND :data_final
                              AND des.codigo_usuario = :codigo_usuario
                              ORDER BY data_credito ASC";
//preparar a QUERY
$result_lancamentos = $conexao->prepare($sql_lancamentos);
$result_lancamentos->bindParam(':data_inicio', $data_inicio);
$result_lancamentos->bindParam(':data_final', $data_final);
$result_lancamentos->bindParam(':codigo_usuario', $idUsuario);
//Executar a QUERY
$result_lancamentos->execute();

//Informações para o PDF
$dados = "<!DOCTYPE html>";
$dados .= "<html lang='pt-br'>";
$dados .= "<head>";
$dados .= "<meta charset='UTF-8'>";
$dados .= "<style> body { font-family: Arial, sans-serif; margin: 0; padding: 0; color: #333; }
.header {
    background-color: rgb(41, 133, 238);
    color: white;
    margin-top: 0px;
    padding: 20px 0;
    }
    .header h1 {
        margin: 0;
        font-size: 24px;
        aling-itens: center;
    }
    .content {
        padding: 20px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    table, th, td {
        border: 1px solid #ddd;
    }
    th, td {
        padding: 8px;
        text-align: left;
    }
    th {
        background-color: rgb(41, 133, 238);
        color: white;
    }
    .footer {
        width: 100%;
                        text-align: center;
                        position: absolute;
                        bottom: 0;
                        padding: 10px;
                        background-color: #f1f1f1;
                        color: #333;
                        font-size: 12px;
                    }
   </style>";
$dados .= "<title>Minha Carteira - Relatório de Lançamentos de Receitas</title>";
$dados .= "</head>";
$dados .= "<style>
.header h1 {
    text-align: center;
} 
.header img {
    display: block;
    margin: 0 auto;
    width: 150px;
}
</style>";
$dados .= "</body>";
$dados .= "<div class='header'>";
// $dados .= "<img src='../../logo_minha_carteira1.png' alt='Logo'>";
$dados .= "<h1>Minha Carteira - Controle Finaceiro Pessoal</h1>";
$dados .= "<h1>Relatório de Lançamentos de Receitas por Data</h1>";
$dados .= "</div>";
$dados .= "<div class='content'>";
        $dados .= "<table>";
        $dados .= "<thead>";
        $dados .= "<tr>";
        $dados .= "<th>Descrição</th>";
        $dados .= "<th>Valor</th>";
        $dados .= "<th>Data Crédito</th>";
        $dados .= "<th>Categoria</th>";
        $dados .= "<th>Carteira</th>";
        $dados .= "<th>Situação</th>";
        $dados .= "</tr>";
        $dados .= "</thead>";
        $dados .= "<tbody>";

//Ler os registros retornados do BD
while ($row_lancamento = $result_lancamentos->fetch(PDO::FETCH_ASSOC)) {
    extract($row_lancamento);
    $dados .= "<tr>";
    $dados .= "<td>$descricao</td>";
    $dados .= "<td>" . formatarMoeda($valor) . "</td>";
    $dados .= "<td>" . formatarData($data_credito) . "</td>";
    $dados .= "<td>$descricao_categoria</td>";
    $dados .= "<td>$descricao_carteira</td>";
    $dados .= "<td>$situacao</td>";
    $dados .= "</tr>";
}

$dados .= "</tbody>";
$dados .= "</table>";
$dados .= "</div>";
$dados .= "</body>";
$dados .= "</html>";



//Instaciar e usar a classe dompdf
// Instanciar e usar a classe Dompdf com opções
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);

//Instanciar o metodo loadHtml e enviar o conteudo do PDF
$dompdf->loadHtml($dados);
//Configurar o tamanho e a orientação do papel
$dompdf->setPaper('A4', 'landscape');
//Renderizar o HTML como PDF
$dompdf->render();

 // Adicionar o rodapé com o nome do usuário e a data
 $canvas = $dompdf->getCanvas();
 $canvas->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) use ($nome_usuario, $data_atual) {
     $text = "Usuário: $nome_usuario | Data: $data_atual";
     $font = $fontMetrics->get_font('Arial, Helvetica, sans-serif', 'normal');
     $size = 12;
     $width = $fontMetrics->getTextWidth($text, $font, $size);
     $canvas->text(20, 570, $text, $font, $size);
 });

 
//Gerar PDF
$dompdf->stream();

    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }
} else {
    echo "Categoria Inválida.";
}
?>