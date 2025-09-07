<?php
    require_once("_sessao.php");
    require_once("./_conexao/conexao.php")
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minha Carteira - Atualização Lançamento de Receitas</title>
    <link rel="stylesheet" href="./css/estilo.css">
    <link rel="shortcut icon" href="../../logo_minha_carteira_ICO.ico" type="image/x-icon">
</head>
<body>
<div class="container">
<h1>Minha Carteira - Controle Financeiro Pessoal</h1>
        <h1>Atualização Lançamento de Receitas</h1>
        <?php include("./_menu-pagina-inicial.php"); ?> 
    </div>
    <?php
    $codigo= filter_input(INPUT_GET, "codigo", FILTER_SANITIZE_NUMBER_INT);
    require_once("./alt-lancamento-view-receita.php");
    ?>
    <br><br>
    <div class="centralizar-v">
    <form action="alt-lancamentobd-receita.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="codigo" value="<?php echo $codigo;?>">

             <div class="row-flex">
                <div class="col" style="margin-top: 15px;">                    
                    <label for="data_credito" >Data do Crédito</label>
                    <input type="date"  style="padding:15px; font-size: 20px;" name="data_credito" id="data_credito"        
                     placeholder="Data do Lançamento" value="<?=$resultado['data_credito'];?>"
                     >
                </div>            
            </div>

            <div class="row-flex">
                <div class="col">
                    <label for="text">Descrição</label>
                    <input type="text" name="descricao" id="descricao" 
                    placeholder="Descrição do Crédito" value="<?=$resultado['descricao'];?>"
                    >
                </div>                
            </div>

            <div class="row-flex">
                <div class="col">
                    <label for="valor">Valor</label>
                    <input type="text" name="valor" id="valor" 
                    placeholder="Valor do Lançamento" value="<?=$resultado['valor'];?>"
                    >
                </div>                
            </div>
        
            <div class="row-flex">
                    <div class="col">
                        <label for="categoria">Categoria</label>
                        <select name="categoria" id="categoria">
                            <?php
                            //Obtém o código da sessão do usuário
                             $codigo = $_SESSION['codigo'];

                            //conecta ao banco de dados
                             $conn = mysqli_connect("localhost", "root", "", "minhacarteira");
                           
                            //Verifica se a conexão foi estabelecida com sucesso
                            if ($conn) {
                                // Execute a consulta para buscar as categorias do usuário
                                $query = "SELECT codigo, descricao FROM categorias WHERE codigo_usuario = '$codigo'";
                                $result = mysqli_query($conn, $query);

                                //Verifique se há categorias retornadas
                                if (mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $codigoCategoria = $row["codigo"];
                                        $nomeCategoria = $row["descricao"];
                                        //Verifique se a categoria atual é igual à categoria do registro sendo editado
                                        $selecionado = ($codigoCategoria == $resultado['categoria']) ? 'selected' : '';
                                        echo "<option value=\"$codigoCategoria\" $selecionado>$nomeCategoria</option>";
                                    }
                                }
                                // Feche a conexão com o banco de dados
                                mysqli_close($conn);
                            }
                            ?>
                        </select>
                    </div>
                </div>



                <div class="row-flex">
                    <div class="col">
                        <label for="carteira">Carteira</label>
                            <select name="carteira" id="carteira">
                                <?php
                                // Obtém o código da sessão do usuário
                                $codigo = $_SESSION['codigo'];

                                // Conecte-se ao banco de dados
                                $conn = mysqli_connect("localhost", "root", "", "minhacarteira");

                                // Verifique se a conexão foi estabelecida com sucesso
                                if ($conn) {
                                    // Execute a consulta para buscar as categorias do usuário
                                $query = "SELECT codigo, descricao FROM carteiras WHERE codigo_usuario = '$codigo'";
                                $result = mysqli_query($conn, $query);

                                //Verifique se há categorias retornadas
                                if (mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $codigoCarteira = $row["codigo"];
                                        $nomeCarteira = $row["descricao"];
                                        //Verifique se a categoria atual é igual à categoria do registro sendo editado
                                        $selecionado = ($codigoCarteira == $resultado['carteira']) ? 'selected' : '';
                                        echo "<option value=\"$codigoCarteira\" $selecionado>$nomeCarteira</option>";
                                    }
                                }
                                // Feche a conexão com o banco de dados
                                mysqli_close($conn);
                            }
                            ?>
                        </select>
                    </div>
                </div>


            <div class="row-flex">
                <div class="col">
                    <div class="row-flex centralizar-h">
                        <div class="col" style="margin-top: 15px;">
                            <label for="situacao">Situação</label>
                            <select name="situacao">
                                <option value="Em Aberto" <?php if ($resultado['situacao'] == "Em Aberto") echo "selected"; ?>>Em Aberto</option>
                                <option value="Recebido" <?php if ($resultado['situacao'] == "Recebido") echo "selected"; ?>>Recebido</option>
                            </select>
                            <br>
                        </div>
                    </div>
                </div>
            </div>
  
                   
            <div class="containercad"  style="margin-top: 35px;"> 
                <form>
                    <div class="voltar" style="background-color: dimgray">
                        <input type="submit" 
                            style="background-color:dimgray; border: 1px solid dimgray; font-size: 16px"
                            value="Fechar" formaction="./view-lancamento.php">
                    </div>
                </form>
                    <div class="espaco-m"></div>
                    <input type="submit" id="submit" value=" G R A V A R ">
            </div> 
        </div>
        </form>
    </div>


    <script>


     // Função para atualizar o elemento com o nome do arquivo selecionado
     function updateFileName() {
        const comprovanteInput = document.getElementById("comprovante");
        const fileSelected = document.getElementById("file-selected");

        if (comprovanteInput.files.length > 0) {
            fileSelected.textContent = "Arquivo selecionado: " + comprovanteInput.files[0].name;
        } else {
            fileSelected.textContent = ""; // Limpa o texto se nenhum arquivo estiver selecionado
        }
    }

   // Função para atualizar a prévia do arquivo (imagem ou PDF)
function updateFilePreview() {
    const comprovanteInput = document.getElementById("comprovante");
    const fileSelected = document.getElementById("file-selected");
    const imagePreview = document.getElementById("image-preview");
    const pdfPreview = document.getElementById("pdf-preview");
    const fileInputButton = document.getElementById("file-input-button");

    if (comprovanteInput.files.length > 0) {
        const selectedFile = comprovanteInput.files[0];
        

        // Verifica se o arquivo é uma imagem ou PDF
        if (/\.(jpe?g|png|gif|bmp)$/i.test(selectedFile.name)) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                imagePreview.style.display = "block";
                pdfPreview.style.display = "none";
                fileInputButton.style.display = "none"; // Oculta o botão "Anexar Arquivo"
            };
            reader.readAsDataURL(selectedFile);
        } else if (/\.(pdf)$/i.test(selectedFile.name)) {
            pdfPreview.src = URL.createObjectURL(selectedFile);
            imagePreview.style.display = "none";
            pdfPreview.style.display = "block";
            fileInputButton.style.display = "none"; // Oculta o botão "Anexar Arquivo"
        } else {
            // Arquivo não suportado, oculta ambos (imagem e PDF)
            imagePreview.style.display = "none";
            pdfPreview.style.display = "none";
        }

        fileSelected.textContent = "Arquivo selecionado: " + selectedFile.name;
    } else {
        fileSelected.textContent = ""; // Limpa o texto se nenhum arquivo estiver selecionado
        imagePreview.style.display = "none";
        pdfPreview.style.display = "none";
        fileInputButton.style.display = "block"; // Torna o botão "Anexar Arquivo" visível novamente
    }
}

// Adicione um ouvinte de evento ao campo de upload de arquivo
const comprovanteInput = document.getElementById("comprovante");
comprovanteInput.addEventListener("change", updateFilePreview);

// Adicione um ouvinte de evento ao botão para acionar o seletor de arquivo
const fileInputButton = document.getElementById("file-input-button");
fileInputButton.addEventListener("click", function () {
    comprovanteInput.click();
});

    </script>
</body>
</html>