<?php
// Conteúdo específico da tela inicial (dashboard)
?>

<div class="centralizar-r">
    <p>
        <?php if (isset($_SESSION['nome'])): ?> 
            <?php $saldoTelaPrincipal = getSaldo($_SESSION['codigo'], $conexao) ?>
            <p class='saldo-principal'>
                Olá, <?= $_SESSION['nome'] ?><br>
                Seu Saldo atual é: <?= formatarMoeda($saldoTelaPrincipal) ?></p>
        <?php endif; ?>
    </p>
    <?php
    $status = filter_input(INPUT_GET, 'status', FILTER_SANITIZE_SPECIAL_CHARS);
    if (isset($status) && ($status == "sucesso")) {
        echo '<div id="sucesso" style="margin-top: 5px;">Login Realizado com sucesso!</div>';
    }

    $status = filter_input(INPUT_GET, 'status', FILTER_SANITIZE_SPECIAL_CHARS);
    if (isset($status) && ($status == "sucesso-cad")) {
        echo '<div id="sucesso" style="margin-top: 5px;">Usuário Cadastrado com sucesso!</div>';
    }
    ?>
</div>   

<?php
// Queries para dashboard - convertendo mysqli para PDO
$idUsuario = $_SESSION['codigo'];

try {
    // Consulta para contas vencidas
    $stmt = $conexao->prepare("SELECT descricao, data_vencimento, valor 
                              FROM lancamentos_despesas 
                              WHERE data_vencimento < CURDATE() 
                              AND situacao != 'Pago'
                              AND codigo_usuario = :usuario
                              ORDER BY data_vencimento ASC 
                              LIMIT 5");
    $stmt->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
    $stmt->execute();
    $contasVencidas = $stmt->fetchAll();

    // Consulta para contas a vencer
    $stmt = $conexao->prepare("SELECT descricao, data_vencimento, valor 
                              FROM lancamentos_despesas 
                              WHERE data_vencimento >= CURDATE() 
                              AND situacao != 'Pago' 
                              AND codigo_usuario = :usuario
                              ORDER BY data_vencimento ASC 
                              LIMIT 5");
    $stmt->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
    $stmt->execute();
    $contasAVencer = $stmt->fetchAll();

    // Consulta para despesas em aberto
    $stmt = $conexao->prepare("SELECT descricao, data_vencimento, valor 
                              FROM lancamentos_despesas 
                              WHERE situacao = 'Em Aberto' 
                              AND codigo_usuario = :usuario
                              ORDER BY data_vencimento ASC 
                              LIMIT 5");
    $stmt->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
    $stmt->execute();
    $despesasEmAberto = $stmt->fetchAll();

    // Consulta para receitas em aberto
    $stmt = $conexao->prepare("SELECT descricao, data_credito, valor 
                              FROM lancamentos_receitas 
                              WHERE situacao = 'Em Aberto'
                              AND codigo_usuario = :usuario
                              ORDER BY data_credito ASC 
                              LIMIT 5");
    $stmt->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
    $stmt->execute();
    $receitasEmAberto = $stmt->fetchAll();

    // Consulta para receitas vencidas
    $stmt = $conexao->prepare("SELECT descricao, data_credito, valor 
                              FROM lancamentos_receitas 
                              WHERE data_credito < CURDATE() 
                              AND situacao != 'RECEBIDO'
                              AND codigo_usuario = :usuario
                              ORDER BY data_credito ASC 
                              LIMIT 5");
    $stmt->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
    $stmt->execute();
    $receitasVencidas = $stmt->fetchAll();

    // Contadores
    $stmt = $conexao->prepare("SELECT COUNT(*) as count FROM lancamentos_despesas WHERE data_vencimento < CURDATE() AND situacao != 'Pago' AND codigo_usuario = :usuario");
    $stmt->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
    $stmt->execute();
    $countContasVencidas = $stmt->fetch()['count'];

    $stmt = $conexao->prepare("SELECT COUNT(*) as count FROM lancamentos_despesas WHERE data_vencimento >= CURDATE() AND situacao != 'Pago' AND codigo_usuario = :usuario");
    $stmt->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
    $stmt->execute();
    $countContasAVencer = $stmt->fetch()['count'];

    $stmt = $conexao->prepare("SELECT COUNT(*) as count FROM lancamentos_despesas WHERE situacao = 'Em Aberto' AND codigo_usuario = :usuario");
    $stmt->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
    $stmt->execute();
    $countContasPagas = $stmt->fetch()['count'];

    $stmt = $conexao->prepare("SELECT COUNT(*) as count FROM lancamentos_receitas WHERE situacao = 'Em Aberto' AND codigo_usuario = :usuario");
    $stmt->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
    $stmt->execute();
    $countReceitasAbertas = $stmt->fetch()['count'];

    $stmt = $conexao->prepare("SELECT COUNT(*) as count FROM lancamentos_receitas WHERE data_credito < CURDATE() AND situacao != 'Recebido' AND codigo_usuario = :usuario");
    $stmt->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
    $stmt->execute();
    $countReceitasVencidas = $stmt->fetch()['count'];

} catch (PDOException $e) {
    error_log('[DASHBOARD ERROR] ' . $e->getMessage());
    echo '<div class="centralizar-r"><p>Erro ao carregar dados do dashboard.</p></div>';
    return;
}
?>

<div class="dashboard">
    <div class="card">
        <h3>Visão Geral</h3>
        <p>
        <div style="display: inline-block; font-weight: bold; margin-right: 70px; color:#ff3333">
            <?php echo 'Lançamentos de DESPESAS VENCIDOS: '; ?>
        </div>
        <div class="number" style="display: inline-block;
        align-items: stretch;
        font-size: 24px;
        color: #333;
        background-color: #ff3333;
        border: 2px solid white;
        border-radius: 10px;
        font-weight: bold; 
        padding: 10px 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transform: translateY(-8px);
        align-items: center;
        margin-left: -17px;"><?php echo $countContasVencidas; ?></div>
        </p>

        <p>
        <div style="display: inline-block; font-weight: bold; margin-right: 70px; color:#ff9999">
            <?php echo 'Lançamentos de DESPESA A VENCER:  '; ?>
        </div>
        <div class="number" style="display: inline-block;
        align-items: stretch;
        font-size: 24px;
        color: #333;
        background-color: #ff9999;
        border: 2px solid white;
        border-radius: 10px;
        font-weight: bold; 
        padding: 10px 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transform: translateY(-8px);
        margin-left: -10px;"><?php echo $countContasAVencer; ?></div>
        </p>

        <p>
        <div style="display: inline-block; margin-right: 70px; font-weight: bold; color:#FFA500">
            <?php echo 'Lançamentos de DESPESA EM ABERTO:  '; ?>
        </div>
        <div class="number" style="display: inline-block;
        align-items: stretch;
        font-size: 24px;
        color: #333;
        background-color: #FFA500;
        border: 2px solid White;
        border-radius: 10px;
        font-weight: bold; 
        padding: 10px 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transform: translateY(-8px);
        margin-left: -18px;"><?php echo $countContasPagas; ?></div>
        </p>

        <p>
        <div style="display: inline-block; margin-right: 70px; font-weight: bold; color:#3CB371">
            <?php echo 'Lançamentos de RECEITA EM ABERTO: '; ?>
        </div>
        <div class="number" style="display: inline-block;
        align-items: stretch;
        font-size: 24px;
        color: #333;
        background-color: #3CB371;
        border: 2px solid white;
        border-radius: 10px;
        font-weight: bold; 
        padding: 10px 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transform: translateY(-8px);
        margin-left: -14px;"><?php echo $countReceitasAbertas; ?></div>
        </p>

        <p>
        <div style="display: inline-block; margin-right: 70px; font-weight: bold; color:#004b23">
            <?php echo 'RECEITAS com DATA CRÉDITO VENCIDO: '; ?>
        </div>
        <div class="number" style="display: inline-block;
        align-items: stretch;
        font-size: 24px;
        color: WHITE;
        background-color: #004b23;
        border: 2px solid white;
        border-radius: 10px;
        font-weight: bold; 
        padding: 10px 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transform: translateY(-8px);
        margin-left: -25px;"><?php echo $countReceitasVencidas; ?></div>
        </p>
    </div>

    <div class="card-despesa">
        <h3>Despesas Vencidas</h3>
        <br>
        <table>
            <?php
            if (count($contasVencidas) > 0) {
                echo '<table>';
                echo '<thead>';
                echo '<tr>';
                echo '<th>Vencimento</th>';
                echo '<th>Descrição</th>';
                echo '<th>Valor</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                foreach ($contasVencidas as $row) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars(formatarData($row['data_vencimento'])) . '</td>';
                    echo '<td>' . htmlspecialchars($row['descricao']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['valor']) . '</td>';
                    echo '</tr>';
                }
                echo '</tbody>';
                echo '</table>';
            } else {
                echo '<p style="font-size: 18px; background-color: #007bff; color: white; padding: 10px; border-radius: 5px; text-align: center;">Nenhuma DESPESA VENCIDA encontrada</p>';
            }
            ?>
            </tbody>
        </table>
    </div>
    
    <div class="card-despesa">
        <h3>Despesas a vencer</h3>
        <br>
        <table>
            <?php
            if (count($contasAVencer) > 0) {
                echo '<table>';
                echo '<thead>';
                echo '<tr>';
                echo '<th>Vencimento</th>';
                echo '<th>Descrição</th>';
                echo '<th>Valor</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                foreach ($contasAVencer as $row) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars(formatarData($row['data_vencimento'])) . '</td>';
                    echo '<td>' . htmlspecialchars($row['descricao']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['valor']) . '</td>';
                    echo '</tr>';
                }
                echo '</tbody>';
                echo '</table>';
            } else {
                echo '<p style="font-size: 18px; background-color: #007bff; color: white; padding: 10px; border-radius: 5px; text-align: center;">Nenhuma DESPESA A VENCER encontrada</p>';
            }
            ?>
            </tbody>
        </table>
    </div>

    <div class="card-despesa">
        <h3>Lançamentos de Despesas em Aberto</h3>
        <br>
        <table>
            <?php
            if (count($despesasEmAberto) > 0) {
                echo '<table>';
                echo '<thead>';
                echo '<tr>';
                echo '<th>Vencimento</th>';
                echo '<th>Descrição</th>';
                echo '<th>Valor</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                foreach ($despesasEmAberto as $row) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars(formatarData($row['data_vencimento'])) . '</td>';
                    echo '<td>' . htmlspecialchars($row['descricao']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['valor']) . '</td>';
                    echo '</tr>';
                }
                echo '</tbody>';
                echo '</table>';
            } else {
                echo '<p style="font-size: 18px; background-color: #007bff; color: white; padding: 10px; border-radius: 5px; text-align: center;">Nenhuma DESPESA EM ABERTO encontrada</p>';
            }
            ?>
            </tbody>
        </table>
    </div>

    <div class="card-receita">
        <h3>Lançamentos de Receita em Aberto</h3>
        <br>
        <table>
            <tbody>
                <?php
                if (count($receitasEmAberto) > 0) {
                    echo '<table>';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th>Data Crédito</th>';
                    echo '<th>Descrição</th>';
                    echo '<th>Valor</th>';
                    echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';
                    foreach ($receitasEmAberto as $row) {
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars(formatarData($row['data_credito'])) . '</td>';
                        echo '<td>' . htmlspecialchars($row['descricao']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['valor']) . '</td>';
                        echo '</tr>';
                    }
                    echo '</tbody>';
                    echo '</table>';
                } else {
                    echo '<p style="font-size: 18px; background-color: #007bff; color: white; padding: 10px; border-radius: 5px; text-align: center;">Nenhuma RECEITA EM ABERTO encontrada</p>';
                }
                ?>
            </tbody>
        </table>
    </div>

    <div class="card-receita">
        <h3>Lançamentos de Receita Vencidas</h3>
        <br>
        <table>
            <tbody>
                <?php
                if (count($receitasVencidas) > 0) {
                    echo '<table>';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th>Data Crédito</th>';
                    echo '<th>Descrição</th>';
                    echo '<th>Valor</th>';
                    echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';
                    foreach ($receitasVencidas as $row) {
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars(formatarData($row['data_credito'])) . '</td>';
                        echo '<td>' . htmlspecialchars($row['descricao']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['valor']) . '</td>';
                        echo '</tr>';
                    }
                    echo '</tbody>';
                    echo '</table>';
                } else {
                    echo '<p style="font-size: 18px; background-color: #007bff; color: white; padding: 10px; border-radius: 5px; text-align: center;">Nenhuma RECEITA VENCIDA encontrada</p>';
                }
                ?>
            </tbody>
        </table>
    </div>
</div>