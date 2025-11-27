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

  <!-- CSS ESTILIZADO -->
  <style>
        * { font-family: 'Poppins', sans-serif; }
        html, body { background: radial-gradient(circle at top right, #111 0%, #000 80%); }
        body { color: #f5f5f5; min-height: 100vh; }

        .container-dashboard { max-width: 1100px; margin: 0 auto; padding: 2rem 1rem; }

        .dashboard-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 2px solid rgba(229, 62, 62, 0.3);
        }
        .dashboard-header h1 {
            font-size: 1.8rem;
            font-weight: 700;
            color: #fff;
            margin: 0;
        }

        .card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }
        .card-body { padding: 2rem; }

        .table {
            color: #fff;
        }
        .table thead th {
            border-bottom: 2px solid rgba(229,62,62,0.3);
            font-weight: 600;
            color: #e53e3e;
        }
        .table tbody tr {
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        .table tbody tr:hover {
            background: rgba(255,255,255,0.05);
        }

        .btn-outline-light {
            border: 1px solid rgba(255,255,255,0.3);
        }
        .btn-outline-light:hover {
            background: rgba(255,255,255,0.1);
            border-color: rgba(255,255,255,0.5);
        }
  </style>

</head>
<body>

<div class="container-dashboard">

    <!-- HEADER -->
    <div class="dashboard-header">
        <h1>Pedidos</h1>

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
                            <tr>
                                <th>ID</th>
                                <th>Cliente</th>
                                <th>Status</th>
                                <th>Total</th>
                                <th>Criado em</th>
                                <th class="text-end">Ações</th>
                            </tr>
                        </thead>

                        <tbody>
                        <?php foreach ($pedidos as $o): ?>
                            <tr>
                                <td>#<?= (int)$o['id'] ?></td>
                                <td><?= htmlspecialchars($o['usuario_id']) ?></td>
                                <td><?= htmlspecialchars($o['status'] ?? '-') ?></td>
                                <td>R$ <?= number_format((float)($o['total'] ?? 0), 2, ',', '.') ?></td>
                                <td><?= htmlspecialchars($o['criado_em'] ?? '-') ?></td>

                                <td class="text-end">
                                    <a 
                                        href="<?= BASE_URL ?>/dashboard/pedidos/<?= (int)$o['id'] ?>" 
                                        class="btn btn-sm btn-outline-light">
                                        Ver
                                    </a>
                                </td>
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
