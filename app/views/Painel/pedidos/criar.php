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
  <title>Editar Pedido - Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body{background:#0f0f10;color:#f5f5f5}
    .card{background:#151517;border:1px solid #26262a}
    label{color:#cfcfcf}
  </style>
</head>
<body class="p-3 p-md-4">

<div class="container-fluid">

  <div class="d-flex align-items-center justify-content-between mb-3">
    <h1 class="h3 m-0">Editar Pedido #<?= (int)$pedido['id'] ?></h1>
    <a href="<?= BASE_URL ?>/dashboard/pedidos" class="btn btn-outline-light">Voltar</a>
  </div>

  <div class="card">
    <div class="card-body">

      <?php if (!$pedido): ?>
        <p class="text-danger">Pedido não encontrado.</p>
      <?php else: ?>

      <form action="<?= BASE_URL ?>/dashboard/pedidos/atualizar/<?= (int)$pedido['id'] ?>" method="post">

        <div class="mb-3">
          <label class="form-label">Cliente (ID)</label>
          <input type="number" name="usuario_id" class="form-control"
            value="<?= htmlspecialchars($pedido['usuario_id']) ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Status</label>
          <select name="status" class="form-select">
            <?php
              $statuses = ['pendente','processando','concluido','cancelado'];
              foreach ($statuses as $s):
            ?>
              <option value="<?= $s ?>" <?= ($pedido['status'] === $s ? 'selected' : '') ?>>
                <?= ucfirst($s) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Total (R$)</label>
          <input type="text" name="total" class="form-control"
            value="<?= htmlspecialchars($pedido['total']) ?>" required>
        </div>

        <button class="btn btn-light mt-2">Atualizar</button>
      </form>

      <?php endif; ?>

    </div>
  </div>
</div>

</body>
</html>
