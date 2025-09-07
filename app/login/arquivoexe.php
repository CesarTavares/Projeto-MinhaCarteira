<div class="centralizar-tabela-lancamentos">
        <table id="tabela-usuarios">
            <tbody>
                <?php 
                    echo "<tr>";
                    echo '<th colspan="9" style="background-color: #3CB371; font-size: 26px;">RECEITAS</th>';
                    echo "</tr>";
                        //Obtém o nome do mês atual em inglês, por exemplo, "July"   
                        $mesAtual = date("F"); 
                        require_once("./view-lancamentos-receitasbd.php");
                        
                            //Associação dos nomes em inglês dos meses para o Português
                            $mesesTraduzidos = array(
                                        "January" => "Janeiro",
                                        "February" => "Fevereiro",
                                        "March" => "Março",
                                        "April" => "Abril",
                                        "May" => "Maio",
                                        "June" => "Junho",
                                        "July" => "Julho",
                                        "August" => "Agosto",
                                        "September" => "Setembro",
                                        "October" => "Outubro",
                                        "November" => "Novembro",
                                        "December" => "Dezembro"
                            );
                            $anos = array();
                            $meses = array();
                            $totalReceitasPorMes = array();
                            $totalReceitas = 0;
                            ///$saldo = 0;
            
                                foreach ($dados as $linha) {
                                    if (!empty($linha["data_credito"])) {
                                        $dataRegistro = strtotime($linha["data_credito"]);
                                        $nomeDoMes = $mesesTraduzidos[date("F", $dataRegistro)];
                                        $ano = date("Y", $dataRegistro);

                                        if (!isset($anos[$ano])) {
                                            $anos[$ano] = array();
                                            $totalReceitasPorAno[$ano] = 0;
                                        }
                                        if (!isset($anos[$ano][$nomeDoMes])) {
                                            $anos[$ano][$nomeDoMes] = array();
                                        }
                                        
                                        if (!isset($meses[$nomeDoMes])) {
                                            $meses[$nomeDoMes] = array();
                                            //Para calcular cada mês de forma individual
                                            $totalReceitasPorMes[$nomeDoMes] = 0;
                                        }
                                            $meses[$nomeDoMes][] = $linha;
                                            $anos[$ano][$nomeDoMes][] = $linha;
                                            $totalReceitasPorAno[$ano] += floatval($linha["valor"]);
                                            //Armazena na Variável os valores para somar no final
                                            $totalReceitasPorMes[$nomeDoMes] += floatval($linha["valor"]);    
                                    }   
                                }

                                foreach ($anos as $ano => $mesesDoAno) {
                                    echo '<tr class="ano-header abrir-fechar" style="background-color: #77a174; font-size: 21px; color: black;">';
                                    echo '<td colspan="9">';
                                    echo '<span class="ano-nome">' . $ano . '</span>';
                                    echo '</td>';
                                    echo '</tr>';  
                                
                                    echo '<tr class="mes-detalhes" style="background-color: #3CB371; font-size: 19px; display: none;">';
                                    echo '<td colspan="9">';
                                    echo '<table>';
                                                         
                                foreach ($mesesDoAno as $nomeDoMes => $registros) {
                                echo '<tr class="mes-header abrir-fechar" style="background-color: #90EE90;">';
                                echo '<td colspan="9">';
                                echo '<span class="mes-nome">' . $nomeDoMes . '</span>';
                                echo '</td>';
                                echo '</tr>';   

                                echo '<tr class="mes-detalhes" style="background-color: #ADD8E6; display: none;">';
                                echo '<td colspan="9">';
                                echo '<table>'; 
                                echo '<tr>';                         
                                        ?>
                <th width="400">
                    Descrição
                    <input type="text" id="filtroDescricao" class="filtro-coluna" data-coluna="0">
                    <button class="botao-filtrar">🔍</button>
                    <button class="botao-limpar">Limpar Filtro</button>
                </th>
                <th width="100">
                    Código
                    <input type="text" id="filtroCodigo" class="filtro-coluna" data-coluna="1">
                    <button class="botao-filtrar">🔍</button>
                    <button class="botao-limpar">Limpar Filtro</button>
                </th>
                <th width="150">
                    Data do Crédito
                    <input type="text" id="filtroDescricao" class="filtro-coluna" data-coluna="2">
                    <button class="botao-filtrar">🔍</button>
                    <button class="botao-limpar">Limpar Filtro</button>
                </th>

                <th width="150">
                    Valor
                    <input type="text" id="filtroCodigo" class="filtro-coluna" data-coluna="4">
                    <button class="botao-filtrar">🔍</button>
                    <button class="botao-limpar">Limpar Filtro</button>
                </th>
                <th width="150">
                    Categoria
                    <input type="text" id="filtroCodigo" class="filtro-coluna" data-coluna="5">
                    <button class="botao-filtrar">🔍</button>
                    <button class="botao-limpar">Limpar Filtro</button>
                </th>
                <th width="150">
                    Carteira
                    <input type="text" id="filtroCodigo" class="filtro-coluna" data-coluna="6">
                    <button class="botao-filtrar">🔍</button>
                    <button class="botao-limpar">Limpar Filtro</button>
                </th>
                <th width="90" class="status">Situação
                    <select class="filtro-coluna" data-coluna="7">
                        <option value="Em Aberto ">Em Aberto</option>
                        <option value="Recebido">Recebido</option>
                    </select>
                </th>

                <th class="status" width="80"></th>
                <th width="80"></th>
                <?php          
                                    echo '</tr>';
                                                        
                                        // Loop para exibir receitas
                                        foreach ($registros as $registro) {
                                                                     
                                        // Detalhes do lançamento de Despesas
                                        echo "<tr class='usuario' data-descricao='" . strtolower($registro["descricao"]) . "'>";
                                        echo "<td>" . $registro["descricao"] . "</td>";
                                        echo "<td>" . $registro["codigo"] . "</td>";
                                        echo "<td>" . $registro["data_credito"] . "</td>";
                                        echo "<td>R$". $registro["valor"] . "</td>";
                                        echo "<td>" . $registro["categoria_descricao"] . "</td>";
                                            echo "<td>" . $registro["carteira_descricao"] . "</td>";
                                        echo "<td class='status2'>" . $registro["situacao"] . "</td>";

                                            // Exiba o comprovante na coluna Comprovante
                                            $comprovantePath = ""; // Inicializa a variável com um valor vazio
                                            
                                            if (!empty($registro['comprovante'])) {
                                                $comprovantePath = $registro['comprovante'];
                                            }
                                            
                                            // Exiba o comprovante na coluna Comprovante
                                            $comprovantePath = !empty($registro['comprovante']) ? $registro['comprovante'] : '';
                                                           
                                            // Adicione o valor atual à soma total
                                            $totalReceitas += floatval($registro["valor"]);
                                                    if (!empty($comprovantePath)) {
                                                        if (in_array(pathinfo($comprovantePath, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif', 'bmp'])) {
                                                            // É uma imagem, use a tag <img> para exibi-la com a classe CSS
                                                            echo '<img src="' . $comprovantePath . '" alt="Comprovante" class="comprovante-img">';
                                                        } elseif (pathinfo($comprovantePath, PATHINFO_EXTENSION) === 'pdf') {
                                                            // É um arquivo PDF, use o elemento <embed> para exibi-lo
                                                            echo '<embed src="' . $comprovantePath . '" type="application/pdf" width="100" height="100">';
                                                        } else {
                                                            // Tipo de arquivo não suportado, exiba uma mensagem de erro
                                                            echo 'Tipo de arquivo não suportado.';
                                                        }
                                                    }
                                    echo '</td>';
                                                    ?>
                <td align="center">
                    <a href="alt-lancamento-receita.php?codigo=<?= $registro['codigo'];?>">
                        <img src="../../btn_editar.png" alt="Atualizar" width="30">
                    </a>
                </td>

                <td align="center">
                    <a href="exc-lancamento-receita.php?codigo=<?= $registro['codigo'];?>">
                        <img src="../../btn_excluir.png" alt="Excluir" width="30">
                    </a>
                </td>
                <?php
                                        echo "</tr>";
                                    }
                                                                    
                                        // Após o loop dos registros, exiba a soma dos valores
                                        echo '<tr class="total-valor"><td colspan="3"></td><td>Total de Receitas do Mês ' . $nomeDoMes . ': 
                                        R$' . $totalReceitasPorMes[$nomeDoMes] . '</td><td colspan="5"></td>
                                        </tr>';
      
                                        // Calcula o saldo total
                                        $saldo = $totalReceitas - $totalGeralDespesas;

                                        
                                            ?>

            </tbody>
            <?php
                     echo '</tr>';                           
            echo '</table>';
            echo '</td>';
            echo '</tr>';
                                }
            echo '</table>';
            echo '</td>';
            echo '</tr>';
                            }
                    ?>
    </div>
    <?php 
                                       // Exibe o total de receitas
                                        echo '<tr class="total-valor"><td style="background-color: #3CB371; font-size: 21px;">Total de Receitas: 
                                        R$' . $totalReceitas . '</td></tr>';
                                        ?>


    <?php
                                        // Exibe o saldo atual (despesas - receitas)
                                        echo '<tr class="total-valor"><td style= "font-size: 26px;">Saldo: 
                                        R$' . $saldo - $totalDespesasDoAno . '</td></tr>';
                                        
                                        ?>


    </table>
    <div class="containercad" style="margin-top: 35px;">
        <form>
            <div class="voltar" style="background-color: dimgray">
                <input type="submit" style="background-color:dimgray; border: 1px solid dimgray; font-size: 16px"
                    value="Fechar" formaction="../../tela-inicial.php">
            </div>
        </form>
        <div class="espaco-m"></div>
        <a href="./cad-lancamento.php"><input type="submit"
                style="background-color: green; border: 1px solid green; font-size: 16px" value="Adicionar"></a>
    </div>
