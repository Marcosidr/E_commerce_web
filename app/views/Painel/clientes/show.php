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
  <title>Cliente - Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>body{background:#0f0f10;color:#f5f5f5}.card{background:#151517;border:1px solid #26262a}</style>
</head>
<body class="p-3 p-md-4">
  <div class="container">
    <div class="d-flex align-items-center justify-content-between mb-3">
      <h1 class="h4 m-0">Detalhe do Cliente</h1>
      <a href="<?= BASE_URL ?>/dashboard/clientes" class="btn btn-outline-light">Voltar</a>
    </div>
    <?php if (!$cliente): ?>
      <div class="alert alert-warning">Cliente não encontrado.</div>
    <?php else: ?>
      <div class="card"><div class="card-body">
        <div class="row g-3">
          <div class="col-md-3"><strong>ID:</strong> #<?= (int)$cliente['id'] ?></div>
          <div class="col-md-5"><strong>Nome:</strong> <?= htmlspecialchars($cliente['name'] ?? '-') ?></div>
          <div class="col-md-4"><strong>Email:</strong> <?= htmlspecialchars($cliente['email'] ?? '-') ?></div>
          <div class="col-md-4"><strong>Telefone:</strong> <?= htmlspecialchars($cliente['phone'] ?? '-') ?></div>
          <div class="col-md-4"><strong>Cadastrado em:</strong> <?= htmlspecialchars($cliente['created_at'] ?? '-') ?></div>
        </div>
      </div></div>
    <?php endif; ?>
  </div>
</body>
</html>
