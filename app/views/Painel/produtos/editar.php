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
  <title>Editar Produto - Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body{background:#0f0f10;color:#f5f5f5}
    .card{background:#151517;border:1px solid #26262a}
    label{color:#bbb}
  </style>
</head>
<body class="p-3 p-md-4">
  <div class="container">
    <div class="d-flex align-items-center justify-content-between mb-3">
      <h1 class="h4 m-0">Editar Produto #<?= (int)$produto['id'] ?></h1>
      <a href="<?= BASE_URL ?>/dashboard/produtos" class="btn btn-outline-light">Voltar</a>
    </div>

    <div class="card">
      <div class="card-body">
        <form method="post" action="<?= BASE_URL ?>/dashboard/produtos/<?= (int)$produto['id'] ?>/atualizar">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Nome</label>
              <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($produto['name']) ?>" required>
            </div>
            <div class="col-md-3">
              <label class="form-label">Preço (R$)</label>
              <input type="number" step="0.01" name="price" class="form-control" value="<?= htmlspecialchars($produto['price']) ?>" required>
            </div>
            <div class="col-md-3">
              <label class="form-label">Estoque</label>
              <input type="number" name="stock_quantity" class="form-control" value="<?= (int)($produto['stock_quantity'] ?? 0) ?>">
            </div>
            <div class="col-md-4">
              <label class="form-label">Marca</label>
              <input type="text" name="brand" class="form-control" value="<?= htmlspecialchars($produto['brand'] ?? '') ?>">
            </div>
            <div class="col-md-4">
              <label class="form-label">Categoria (ID)</label>
              <input type="number" name="category_id" class="form-control" value="<?= (int)($produto['category_id'] ?? 0) ?>">
            </div>
            <div class="col-md-2">
              <label class="form-label">Ativo</label>
              <select name="active" class="form-select">
                <option value="1" <?= (int)($produto['active']??0)===1?'selected':''; ?>>Sim</option>
                <option value="0" <?= (int)($produto['active']??0)===0?'selected':''; ?>>Não</option>
              </select>
            </div>
            <div class="col-md-2">
              <label class="form-label">Destaque</label>
              <select name="featured" class="form-select">
                <option value="1" <?= (int)($produto['featured']??0)===1?'selected':''; ?>>Sim</option>
                <option value="0" <?= (int)($produto['featured']??0)===0?'selected':''; ?>>Não</option>
              </select>
            </div>
          </div>
          <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary">Salvar alterações</button>
            <a href="<?= BASE_URL ?>/dashboard/produtos" class="btn btn-secondary">Cancelar</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
