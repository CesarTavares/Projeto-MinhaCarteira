<?php
require("./_sessao.php");
include_once("./_conexao/conexao.php");
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minha Carteira - Relatório de Usuários</title>
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script> -->
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
        <h1>Minha Carteira -Controle Financeiro Pessoal</h1>
        <h1> Relatorio de Usuarios em PDF </h1>
    </div>    

    <?php 
    $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    
    ?> 
        <div class="containercad" style="margin-top: 35px;">       
            <form method="POST" action="">          
                           
            <label>Pesquisar</label>
           
               <input type="text" name="texto_pesquisar" placeholder="Pesquisar por nome"><br><br>
            <div class="containercad" style="margim-top: 35px;">
                <div class="espaco-m"></div>
                <input type="submit" value="Pesquisar" name="PesqUsuario">        
           <?php
                $texto_pesquisar = "";
                    if(isset($dados['texto_pesquisar'])) {
                     $texto_pesquisar = $dados['texto_pesquisar'];
                }         echo "<a class='link-as-button' href='gerar_pdf.php?texto_pesquisar=$texto_pesquisar'>Gerar PDF</a><br><br>";  
            ?>  
                <div class="voltar" style="background-color: dimgray">
                    <input type="submit" style="background-color:dimgray; border: 1px solid dimgray; font-size: 20px" value="Fechar" formaction="../../tela-inicial.php">
                </div>
            </div>
                
        </form>    
        </div>
         
        <?php
        if(!empty($dados['PesqUsuario'])){
            $nome = "%" . $dados['texto_pesquisar'] . "%";
            $query_usuarios = "SELECT codigo, nome, email, status 
                            FROM usuarios    
                            WHERE nome LIKE :nome 
                            ORDER BY codigo DESC";
            $result_usuarios = $conexao->prepare($query_usuarios);
            $result_usuarios->bindParam(':nome', $nome);
            $result_usuarios->execute();

        if(($result_usuarios) and ($result_usuarios->rowCount() != 0)){
            while($row_usuario = $result_usuarios->fetch(PDO::FETCH_ASSOC)){
               extract($row_usuario);               
               echo "Nome: $nome <br>";
               echo "E-mail: $email <br>";
               echo "Status: $status <br>";
               echo "<hr>";
            }
        }else{
            echo "<p style='color: #f00;'>Erro: Nenhum usuário encontrado!</p>";
        }
    } 
    ?>    
</body>
</html>