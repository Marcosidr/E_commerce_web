<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['users']) || ($_SESSION['users']['role'] ?? '') !== 'admin') {
    header('Location: ' . BASE_URL . '/');
    exit;
}
$selectedType = $type ?? '';
$from = $from ?? '';
$to = $to ?? '';
$report = $report ?? null;
?>
<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Relatórios - Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body{background:#0f0f10;color:#f5f5f5}
    .card{background:#151517;border:1px solid #26262a}
    label{color:#bbb}
    .form-control,.form-select{background:#0f0f10;border-color:#2a2a2e;color:#e5e5e5}
  </style>
</head>
<body class="p-3 p-md-4">
  <div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between mb-3">
      <h1 class="h3 m-0">Relatórios</h1>
      <a href="<?= BASE_URL ?>/dashboard" class="btn btn-outline-light">Voltar</a>
    </div>

    <div class="card mb-3"><div class="card-body">
      <form method="get" action="<?= BASE_URL ?>/dashboard/relatorios" class="row g-3 align-items-end">
        <div class="col-md-3">
          <label class="form-label">Tipo de relatório</label>
          <select name="type" class="form-select" required>
            <option value="" disabled <?= $selectedType? '':'selected' ?>>Selecione…</option>
            <option value="vendas" <?= $selectedType==='vendas'?'selected':''; ?>>Vendas (pedidos detalhados)</option>
            <option value="vendas_diario" <?= $selectedType==='vendas_diario'?'selected':''; ?>>Vendas diário (agregado por dia)</option>
            <option value="pedidos" <?= $selectedType==='pedidos'?'selected':''; ?>>Pedidos</option>
            <option value="produtos" <?= $selectedType==='produtos'?'selected':''; ?>>Produtos</option>
            <option value="clientes" <?= $selectedType==='clientes'?'selected':''; ?>>Clientes</option>
            <option value="tudo" <?= $selectedType==='tudo'?'selected':''; ?>>Visão geral</option>
          </select>
        </div>
        <div class="col-md-2">
          <label class="form-label">De</label>
          <input type="date" name="from" class="form-control" value="<?= htmlspecialchars($from) ?>">
        </div>
        <div class="col-md-2">
          <label class="form-label">Até</label>
          <input type="date" name="to" class="form-control" value="<?= htmlspecialchars($to) ?>">
        </div>
        <div class="col-md-5 d-flex gap-2">
          <button class="btn btn-primary" type="submit">Visualizar</button>
          <?php if ($selectedType): ?>
            <?php
              $q = http_build_query(['from'=>$from,'to'=>$to]);
              $exportBase = [
                'produtos' => BASE_URL . '/dashboard/relatorios/produtos',
                'pedidos'  => BASE_URL . '/dashboard/relatorios/pedidos',
                'vendas'   => BASE_URL . '/dashboard/relatorios/vendas',
                'vendas_diario' => BASE_URL . '/dashboard/relatorios/vendas_diario',
                'clientes' => BASE_URL . '/dashboard/relatorios/clientes',
              ][$selectedType] ?? '';
            ?>
            <?php if ($exportBase): ?>
              <a class="btn btn-outline-light" href="<?= $exportBase ?>.csv<?= $q?('?'.$q):'' ?>">Exportar CSV</a>
              <a class="btn btn-outline-light" href="<?= $exportBase ?>.xlsx<?= $q?('?'.$q):'' ?>">Exportar Excel</a>
              <a class="btn btn-outline-light" href="<?= $exportBase ?>.docx<?= $q?('?'.$q):'' ?>">Exportar Word</a>
              <a class="btn btn-outline-light" href="<?= $exportBase ?>.pdf<?= $q?('?'.$q):'' ?>">Exportar PDF</a>
            <?php endif; ?>
          <?php endif; ?>
        </div>
      </form>
    </div></div>

    <?php if ($report && !empty($report['rows'])): ?>
      <div class="card"><div class="card-body">
        <div class="table-responsive">
          <table class="table table-sm align-middle">
            <thead>
              <tr>
                <?php foreach ($report['columns'] as $c): ?>
                  <th><?= htmlspecialchars((string)$c) ?></th>
                <?php endforeach; ?>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($report['rows'] as $r): ?>
                <tr>
                  <?php foreach ($report['columns'] as $c): ?>
                    <td><?= htmlspecialchars((string)($r[$c] ?? '')) ?></td>
                  <?php endforeach; ?>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div></div>
    <?php elseif ($selectedType): ?>
      <div class="alert alert-warning">Sem dados para os filtros informados ou tabela ausente.</div>
    <?php endif; ?>
  </div>
</body>
</html>
