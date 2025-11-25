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
  <title>Produtos - Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    body{background:#0f0f10;color:#f5f5f5}
    .card{background:#151517;border:1px solid #26262a}
    a{color:#e53e3e}
    .table>:not(caption)>*>*{background:transparent;color:#ddd}
    .form-switch .form-check-input{cursor:pointer}
  </style>
</head>
<body class="p-3 p-md-4">
  <div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between mb-3">
      <h1 class="h3 m-0">Produtos</h1>
      <div class="d-flex gap-2">
        <a href="<?= BASE_URL ?>/dashboard" class="btn btn-outline-light"><i class="fa fa-arrow-left me-2"></i>Voltar</a>
      </div>
    </div>

    <?php if (!empty($_SESSION['flash_message'])): $f=$_SESSION['flash_message']; unset($_SESSION['flash_message']); ?>
      <div class="alert alert-<?= $f['type'] ?? 'info' ?>"><?= htmlspecialchars($f['text'] ?? '') ?></div>
    <?php endif; ?>

    <div class="card">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table align-middle">
            <thead>
              <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Preço</th>
                <th>Estoque</th>
                <th>Ativo</th>
                <th>Destaque</th>
                <th class="text-end">Ações</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach (($produtos ?? []) as $p): ?>
                <tr>
                  <td>#<?= (int)$p['id'] ?></td>
                  <td><?= htmlspecialchars($p['nome']) ?></td>
                  <td>R$ <?= number_format((float)$p['preco'], 2, ',', '.') ?></td>
                  <td><?= (int)($p['estoque'] ?? 0) ?></td>
                  <td>
                    <span class="badge bg-<?= ($p['ativo']??0)?'success':'secondary' ?>"><?= ($p['ativo']??0)?'Ativo':'Inativo' ?></span>
                  </td>
                  <td>
                    <div class="form-switch">
                      <input class="form-check-input toggle-featured" type="checkbox" data-id="<?= (int)$p['id'] ?>" <?= (int)($p['destaque']??0) ? 'checked' : '' ?>>
                    </div>
                  </td>
                  <td class="text-end">
                    <a class="btn btn-sm btn-outline-light" href="<?= BASE_URL ?>/dashboard/produtos/<?= (int)$p['id'] ?>/editar">
                      <i class="fa fa-pen"></i>
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <script>
  document.querySelectorAll('.toggle-featured').forEach(el => {
    el.addEventListener('change', async function(){
      const id = this.dataset.id;
      const featured = this.checked ? 1 : 0;
      try{
        const res = await fetch('<?= BASE_URL ?>/dashboard/produtos/'+id+'/destaque', {
          method:'POST', headers:{'Content-Type':'application/x-www-form-urlencoded'}, body:'featured='+featured
        });
        const data = await res.json();
        if(!data.success){ this.checked = !this.checked; alert('Falha ao alterar destaque'); }
      }catch(e){ this.checked = !this.checked; alert('Erro ao alterar destaque'); }
    });
  });
  </script>
</body>
</html>
