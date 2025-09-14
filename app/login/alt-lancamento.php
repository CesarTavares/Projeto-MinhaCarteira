<?php
    require_once("_sessao.php");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minha Carteira - Atualização de Lançamento</title>
    <link rel="stylesheet" href="./css/estilo.css">
    <link rel="shortcut icon" href="../../logo_minha_carteira_ICO.ico" type="image/x-icon">
</head>
<body>
    <div class="container">
    <h1>Minha Carteira - Controle Financeiro Pessoal</h1>
        <h1>Atualização de Lançamento</h1>
        <?php include("./_menu-pagina-inicial.php"); ?> 
    </div>
    <?php
    $codigo= filter_input(INPUT_GET, "codigo", FILTER_SANITIZE_NUMBER_INT);
    require_once("./alt-lancamento-view.php");
    ?>
    <br><br>
    <div class="centralizar-v">
        <form action="alt-lancamentobd.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="codigo" value="<?php echo $codigo;?>">

                <div class="row-flex">
                    <div class="col" style="margin-top: 15px;">                    
                        <label for="data_pagamento" >Data do Pagamento</label>
                        <input type="date"  style="padding:15px; font-size: 20px;" name="data_pagamento" id="data_pagamento"        
                        placeholder="Data do Lançamento" value="<?=$despesa['data_pagamento'];?>"
                        >
                    </div>  
                    <div class="col" style="margin-top: 15px;">                    
                        <label for="data_vencimento" >Data de Vencimento</label>
                        <input type="date"  style="padding:15px; font-size: 20px;" name="data_vencimento" id="data_vencimento"        
                        placeholder="Data do Lançamento" value="<?=$despesa['data_vencimento'];?>"
                        >
                    </div>                
                </div>

                <div class="row-flex">
                    <div class="col">
                        <label for="text">Descrição</label>
                        <input type="text" name="descricao" id="descricao" 
                        placeholder="Descrição do Pagamento" value="<?=$despesa['descricao'];?>"
                        >
                    </div>                
                </div>

                <div class="row-flex">
                    <div class="col">
                        <label for="valor">Valor</label>
                        <input type="text" name="valor" id="valor" 
                        placeholder="Valor da Lançamento" value="<?=$despesa['valor'];?>"
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

                            try {
                                require_once("./_conexao/conexao.php");
                                $stmt = $conexao->prepare("SELECT codigo, descricao FROM categorias WHERE codigo_usuario = :usuario ORDER BY descricao ASC");
                                $stmt->bindValue(':usuario', $codigo, PDO::PARAM_INT);
                                $stmt->execute();
                                while ($row = $stmt->fetch()) {
                                    $codigoCategoria = (int)$row['codigo'];
                                    $nomeCategoria = htmlspecialchars($row['descricao']);
                                    $selecionado = ($codigoCategoria == $despesa['categoria']) ? 'selected' : '';
                                    echo "<option value=\"{$codigoCategoria}\" {$selecionado}>{$nomeCategoria}</option>";
                                }
                            } catch (PDOException $e) {
                                echo '<option value="">Erro ao carregar categorias</option>';
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

                                try {
                                    require_once("./_conexao/conexao.php");
                                    $stmt = $conexao->prepare("SELECT codigo, descricao FROM carteiras WHERE codigo_usuario = :usuario ORDER BY descricao ASC");
                                    $stmt->bindValue(':usuario', $codigo, PDO::PARAM_INT);
                                    $stmt->execute();
                                    while ($row = $stmt->fetch()) {
                                        $codigoCarteira = (int)$row['codigo'];
                                        $nomeCarteira = htmlspecialchars($row['descricao']);
                                        $selecionado = ($codigoCarteira == $despesa['carteira']) ? 'selected' : '';
                                        echo "<option value=\"{$codigoCarteira}\" {$selecionado}>{$nomeCarteira}</option>";
                                    }
                                } catch (PDOException $e) {
                                    echo '<option value="">Erro ao carregar carteiras</option>';
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
                                    <option value="Em Aberto" <?php if ($despesa['situacao'] == "Em Aberto") echo "selected"; ?>>Em Aberto</option>
                                    <option value="Pago" <?php if ($despesa['situacao'] == "Pago") echo "selected"; ?>>Pago</option>
                                </select>
                                <br>
                            </div>
                        </div>
                    </div>
                </div>

            <div class="row-flex">
                <div class="col">
                    <label for="comprovante">Comprovante de Pagamento</label>
                    <input type="file" name="comprovante" id="comprovante" style="display: none;">
                    <?php
                           
                    ?>
                    <img id="image-preview" src="<?php echo $comprovantePath; ?>" alt="Preview" style="display: none;">
                    <embed id="pdf-preview" class="pdf-preview" src="" type="application/pdf" width="710" height="500" style="display: none;">
                    <label for="file-input-button" class="custom-file-input-label">Anexar Arquivo</label>
                    <input type="button" id="file-input-button" class="custom-file-input" value="Escolher Arquivo">
                </div>
            </div>

            <label id= "comprovante" for="">Comprovante Atual:</label><br>
            <span><?=$despesa['comprovante'];?></span>
                <?php
                                            try {
                                                require_once("./_conexao/conexao.php");
                                                // Verifique se a variável $codigo está definida e é um número inteiro válido
                        if (isset($_GET['codigo']) && is_numeric($_GET['codigo'])) {
                            $codigo = $_GET['codigo'];
                
                            // Consulta SQL para recuperar o caminho do comprovante com base no código
                            $consultaComprovante = "SELECT comprovante FROM lancamentos_despesas WHERE codigo = :codigo";
                
                            // Preparar a consulta
                                                        $stmt = $conexao->prepare($consultaComprovante);
                            $stmt->bindParam(':codigo', $codigo, PDO::PARAM_INT);
                            $stmt->execute();
                
                            $result = $stmt->fetch(PDO::FETCH_ASSOC);
                
                            $comprovantePath = ""; // Inicialize a variável antes de verificar o banco de dados
                
                            if ($result && !empty($result['comprovante'])) {
                                $comprovantePath = $result['comprovante'];
                
                                // Verifique o tipo de arquivo e exiba-o
                                $fileExtension = pathinfo($comprovantePath, PATHINFO_EXTENSION);
                
                                if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'bmp'])) {
                                    // É uma imagem, use a tag <img> para exibi-la com a classe CSS
                                    echo '<img src="' . $comprovantePath . '" alt="Comprovante" class="comprovante-img">';
                                } elseif ($fileExtension === 'pdf') {
                                    // É um arquivo PDF, use o elemento <embed> para exibi-lo
                                    echo '<embed src="' . $comprovantePath . '" type="application/pdf" width="100%" height="500px">';
                                } else {
                                    // Tipo de arquivo não suportado, exiba uma mensagem de erro
                                    echo 'Tipo de arquivo não suportado.';
                                }
                            } else {
                                echo 'Nenhum comprovante disponível para este registro.';
                            }
                        } else {
                            echo 'Código inválido ou não fornecido.';
                        }
                    } catch (PDOException $e) {
                        echo 'Erro ao recuperar o comprovante: ' . $e->getMessage();
                    }
                ?>
                   
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