<?php
//Carregar o Composer
require "../../vendor/autoload.php";

//Receber da URL o termo usado para pesquisar
$texto_pesquisar = filter_input(INPUT_GET, 'texto_pesquisar', FILTER_DEFAULT);
$nome = "%" . $texto_pesquisar . "%";

//incluir conexão com o bd
include_once './_conexao/conexao.php';

$sql_usuarios = "SELECT codigo, nome, email, nivel, status 
        FROM usuarios
        WHERE nome LIKE :nome
        ORDER BY codigo DESC";
//preparar a QUERY
$result_usuarios = $conexao->prepare($sql_usuarios);

$result_usuarios->bindParam(':nome', $nome);
//Executar a Query
$result_usuarios->execute();

//Informações para o PDF
$dados = "<!DOCTYPE html>";
$dados .= "<html lang='pt-br'>";
$dados .= "<head>";
$dados .= "<meta charset='UTF-8'>";
$dados .= "<link rel='stylesheet' href='./css/estilo.css'>";
$dados .= "<title>Minha Carteira - Relatorio de Usuários PDF</title>";
$dados .= "</head>";
$dados .= "</body>";
$dados .= "<h1> Relatorio de Usuários em PDF</h1>";

//Ler os registros retornados do BD
while($row_usuarios = $result_usuarios->fetch(PDO::FETCH_ASSOC)){
    extract($row_usuarios);
    $dados .= "Código: $codigo <br>";
    $dados .= "Nome: $nome <br>";
    $dados .= "E-mail: $email <br>";
    $dados .= "Nivel: $nivel <br>";
    $dados .= "Status: $status <br>";
    $dados .= "<hr>";
}

$dados .= "</body>";
$dados .= "</html>";

//Referenciar o namespace Dompdf
use Dompdf\Dompdf;
//Instaciar e usar a classe dompdf
$dompdf = new Dompdf(['enable_remote' => true]);
//Instanciar o metodo loadHtml e enviar o conteudo do PDF
$dompdf->loadHtml($dados);
//Configurar o tamanho e a orientação do papel
$dompdf->setPaper('A4', 'portrait');
//Renderizar o HTML como PDF
$dompdf->render();
//Gerar PDF
$dompdf->stream();
