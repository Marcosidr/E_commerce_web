<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['users']) || ($_SESSION['users']['role'] ?? '') !== 'admin') {
    header('Location: ' . BASE_URL . '/');
    exit;
}
?>
<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Pedidos - Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>body{background:#0f0f10;color:#f5f5f5}.card{background:#151517;border:1px solid #26262a}</style>
</head>
<body class="p-3 p-md-4">
  <div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between mb-3">
      <h1 class="h3 m-0">Pedidos</h1>
      <div>
        <a href="<?= BASE_URL ?>/dashboard" class="btn btn-outline-light">Voltar</a>
      </div>
    </div>
    <div class="card">
      <div class="card-body">
        <?php if (empty($pedidos)): ?>
          <p class="text-muted">Nenhum pedido encontrado ou tabela ausente.</p>
        <?php else: ?>
          <div class="table-responsive">
            <table class="table align-middle">
              <thead>
                <tr><th>ID</th><th>Cliente</th><th>Status</th><th>Total</th><th>Criado em</th><th></th></tr>
              </thead>
              <tbody>
                <?php foreach ($pedidos as $o): ?>
                  <tr>
                    <td>#<?= (int)$o['id'] ?></td>
                    <td><?= htmlspecialchars($o['usuario_id']) ?></td>
                    <td><?= htmlspecialchars($o['status'] ?? '-') ?></td>
                    <td>R$ <?= number_format((float)($o['total'] ?? 0),2,',','.') ?></td>
                    <td><?= htmlspecialchars($o['criado_em'] ?? '-') ?></td>
                    <td class="text-end"><a class="btn btn-sm btn-outline-light" href="<?= BASE_URL ?>/dashboard/pedidos/<?= (int)$o['id'] ?>">Ver</a></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</body>
</html>
