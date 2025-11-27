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
  <title>Pedido - Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>body{background:#0f0f10;color:#f5f5f5}.card{background:#151517;border:1px solid #26262a}</style>
</head>
<body class="p-3 p-md-4">
  <div class="container">
    <div class="d-flex align-items-center justify-content-between mb-3">
      <h1 class="h4 m-0">Detalhe do Pedido</h1>
      <a href="<?= BASE_URL ?>/dashboard/pedidos" class="btn btn-outline-light">Voltar</a>
    </div>

    <?php if (!$pedido): ?>
      <div class="alert alert-warning">Pedido não encontrado (ou tabela ausente).</div>
    <?php else: ?>
      <div class="card mb-3"><div class="card-body">
        <div class="row g-3">
          <div class="col-md-3"><strong>ID:</strong> #<?= (int)$pedido['id'] ?></div>
          <div class="col-md-3"><strong>Cliente (ID):</strong> <?= htmlspecialchars($pedido['usuario_id']) ?></div>
          <div class="col-md-3"><strong>Status:</strong> <?= htmlspecialchars($pedido['status'] ?? '-') ?></div>
          <div class="col-md-3"><strong>Total:</strong> R$ <?= number_format((float)($pedido['total'] ?? 0),2,',','.') ?></div>
          <div class="col-md-6"><strong>Criado em:</strong> <?= htmlspecialchars($pedido['criado_em'] ?? '-') ?></div>
          <div class="col-md-6"><strong>Atualizado em:</strong> <?= htmlspecialchars($pedido['atualizado_em'] ?? '-') ?></div>
        </div>
      </div></div>

      <div class="card"><div class="card-body">
        <h5 class="mb-3">Itens</h5>
        <?php if (empty($itens)): ?>
          <p class="text-muted">Sem itens.</p>
        <?php else: ?>
          <div class="table-responsive">
            <table class="table align-middle">
              <thead><tr><th>Produto</th><th>Qtd</th><th>Preço</th><th>Subtotal</th></tr></thead>
              <tbody>
              <?php foreach ($itens as $i): $sub = (float)($i['preco_unitario']??0) * (int)($i['quantidade']??0); ?>
                <tr>
                  <td><?= htmlspecialchars($i['nome_produto'] ?? ('#'.($i['produto_id']??''))) ?></td>
                  <td><?= (int)($i['quantidade'] ?? 0) ?></td>
                  <td>R$ <?= number_format((float)($i['preco_unitario'] ?? 0),2,',','.') ?></td>
                  <td>R$ <?= number_format($sub,2,',','.') ?></td>
                </tr>
              <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </div></div>
    <?php endif; ?>
  </div>
</body>
</html>
