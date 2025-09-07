<?php
require("./_sessao.php");
require_once("./_conexao/conexao.php"); // garante que $conexao esteja acessível

if (!isset($conexao) || !is_object($conexao)) {
    die("Erro de conexão com o banco de dados.");
}

function consultarLancamentos($tipo, $ano = null, $mes = null)
{
    global $conexao;

    // Definir tabela e campo de data dependendo do tipo
    $tabela = $tipo === 'despesa' ? "lancamentos_despesas" : "lancamentos_receitas";
    $campoData = $tipo === 'despesa' ? "data_vencimento" : "data_credito";

    // SQL base
    $sql = "SELECT l.codigo, l.descricao, l.valor, l.situacao, l.comprovante,
                   l.$campoData AS data_ref,
                   c.descricao AS categoria_descricao,
                   ca.descricao AS carteira_descricao
            FROM $tabela l
            LEFT JOIN categorias c ON l.codigo_categoria = c.codigo
            LEFT JOIN carteiras ca ON l.codigo_carteira = ca.codigo
            WHERE 1=1";

    $params = [];

    // Filtro por ano
    if ($ano && $ano !== "todos") {
        $sql .= " AND YEAR(l.$campoData) = :ano";
        $params[':ano'] = $ano;
    }

    // Filtro por mês
    if ($mes && $mes !== "todos") {
        $sql .= " AND MONTH(l.$campoData) = :mes";
        $params[':mes'] = $mes;
    }

    $sql .= " ORDER BY l.$campoData DESC";

    $stmt = $conexao->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// ---- Captura filtros do usuário
$anoSelecionado = $_GET['ano'] ?? "todos";
$mesSelecionado = $_GET['mes'] ?? "todos";
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Minha Carteira - Consulta</title>
  <link rel="stylesheet" href="./css/estilo.css">
</head>
<body>
  <div class="container">
    <h1>Minha Carteira - Consulta de Lançamentos</h1>

    <!-- Filtros -->
    <form method="get" class="filtros">
        <label>Ano:
          <select name="ano">
            <option value="todos">Todos</option>
            <?php for ($y = date("Y"); $y >= 2000; $y--) : ?>
              <option value="<?= $y ?>" <?= $anoSelecionado == $y ? "selected" : "" ?>>
                <?= $y ?>
              </option>
            <?php endfor; ?>
          </select>
        </label>

        <label>Mês:
  <select name="mes">
    <option value="todos">Todos</option>
    <?php
    $formatter = new IntlDateFormatter(
        'pt_BR',                       // idioma
        IntlDateFormatter::NONE,
        IntlDateFormatter::NONE,
        'America/Sao_Paulo',           // timezone
        IntlDateFormatter::GREGORIAN,
        'MMMM'                         // formato -> nome completo do mês
    );

    for ($m = 1; $m <= 12; $m++) {
        $data = new DateTime("2025-$m-01");
        $mesNome = ucfirst($formatter->format($data)); // ex: Janeiro, Fevereiro...
        $selected = ($mesSelecionado == $m) ? "selected" : "";
        echo "<option value='$m' $selected>$mesNome</option>";
    }
    ?>
  </select>
</label>

        <button type="submit">Filtrar</button>
    </form>

    <!-- DESPESAS -->
    <h2 style="color:red;">Despesas</h2>
    <table>
      <tr>
        <th>Descrição</th>
        <th>Data Vencimento</th>
        <th>Valor</th>
        <th>Categoria</th>
        <th>Carteira</th>
        <th>Situação</th>
      </tr>
      <?php
      $despesas = consultarLancamentos("despesa", $anoSelecionado, $mesSelecionado);
      $totalDespesas = 0;
      foreach ($despesas as $d) :
        $totalDespesas += $d['valor'];
      ?>
      <tr>
        <td><?= htmlspecialchars($d['descricao']) ?></td>
        <td><?= date("d/m/Y", strtotime($d['data_ref'])) ?></td>
        <td>R$ <?= number_format($d['valor'], 2, ',', '.') ?></td>
        <td><?= htmlspecialchars($d['categoria_descricao']) ?></td>
        <td><?= htmlspecialchars($d['carteira_descricao']) ?></td>
        <td><?= $d['situacao'] ?></td>
      </tr>
      <?php endforeach; ?>
      <tr><td colspan="6"><strong>Total Despesas: R$ <?= number_format($totalDespesas, 2, ',', '.') ?></strong></td></tr>
    </table>

    <!-- RECEITAS -->
    <h2 style="color:green;">Receitas</h2>
    <table>
      <tr>
        <th>Descrição</th>
        <th>Data Crédito</th>
        <th>Valor</th>
        <th>Categoria</th>
        <th>Carteira</th>
        <th>Situação</th>
      </tr>
      <?php
      $receitas = consultarLancamentos("receita", $anoSelecionado, $mesSelecionado);
      $totalReceitas = 0;
      foreach ($receitas as $r) :
        $totalReceitas += $r['valor'];
      ?>
      <tr>
        <td><?= htmlspecialchars($r['descricao']) ?></td>
        <td><?= date("d/m/Y", strtotime($r['data_ref'])) ?></td>
        <td>R$ <?= number_format($r['valor'], 2, ',', '.') ?></td>
        <td><?= htmlspecialchars($r['categoria_descricao']) ?></td>
        <td><?= htmlspecialchars($r['carteira_descricao']) ?></td>
        <td><?= $r['situacao'] ?></td>
      </tr>
      <?php endforeach; ?>
      <tr><td colspan="6"><strong>Total Receitas: R$ <?= number_format($totalReceitas, 2, ',', '.') ?></strong></td></tr>
    </table>

    <!-- SALDO -->
    <h2>Saldo: R$ <?= number_format($totalReceitas - $totalDespesas, 2, ',', '.') ?></h2>
  </div>
</body>
</html>
