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

  <title>Clientes - Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- ESTILO IGUAL AO DA PÁGINA DE PRODUTOS -->
  <style>
      * { font-family: 'Poppins', sans-serif; }

      body {
          background: #0d0d0d;
          color: #fff;
          margin: 0;
          padding: 0;
      }

      .container-dashboard {
          max-width: 1300px;
          margin: 40px auto;
          padding: 0 20px;
      }

      /* HEADER */
      .dashboard-header {
          display: flex;
          justify-content: space-between;
          align-items: center;
          border-bottom: 1px solid rgba(255, 255, 255, 0.1);
          padding-bottom: 20px;
          margin-bottom: 30px;
      }

      .dashboard-header h1 {
          font-size: 32px;
          font-weight: 700;
          display: flex;
          align-items: center;
          gap: 10px;
      }

      /* BOTÕES */
      .btn-red {
          background: linear-gradient(135deg, #ff4747, #b30000);
          border: none;
          padding: 10px 18px;
          font-weight: 600;
          border-radius: 10px;
          color: #fff;
          transition: 0.2s;
      }
      .btn-red:hover {
          opacity: 0.9;
          transform: scale(1.03);
      }

      .btn-dark-outline {
          border: 1px solid #444;
          padding: 10px 18px;
          border-radius: 10px;
          color: #fff;
          background: #000;
          transition: 0.2s;
      }
      .btn-dark-outline:hover {
          background: #111;
      }

      /* CARD PRINCIPAL */
      .card-custom {
          background: linear-gradient(145deg, #111, #0a0a0a);
          border-radius: 20px;
          padding: 25px;
          box-shadow: 0px 0px 20px rgba(0,0,0,0.4);
          border: solid 1px rgba(255,255,255,0.05);
      }

      /* TABELA */
      table {
          width: 100%;
          border-collapse: separate;
          border-spacing: 0 8px;
      }

      thead th {
          padding: 15px;
          color: #ff4747;
          font-size: 14px;
          text-transform: uppercase;
          border-bottom: 1px solid rgba(255,255,255,0.1);
      }

      tbody tr {
          background: #121212;
          border-radius: 12px;
          transition: 0.2s;
      }
      tbody tr:hover {
          background: #1a1a1a;
      }

      tbody td {
          padding: 15px;
          border-top: 1px solid rgba(255,255,255,0.03);
      }

      /* AÇÕES */
      .action-btn {
          background: #b30000;
          border: none;
          padding: 8px 12px;
          border-radius: 10px;
          color: #fff;
          transition: 0.2s;
      }
      .action-btn:hover {
          background: #ff4747;
      }

      .action-edit {
          background: #d63031;
      }
      .action-edit:hover {
          background: #ff4f4f;
      }

      .action-del {
          background: #8b0000;
      }
      .action-del:hover {
          background: #c20000;
      }
  </style>

</head>

<body>

<div class="container-dashboard">

    <!-- HEADER -->
    <div class="dashboard-header">
        <h1>Clientes</h1>

        <div class="d-flex gap-2">
            <a href="<?= BASE_URL ?>/dashboard" class="btn-dark-outline">⟵ Dashboard</a>

            <a href="<?= BASE_URL ?>/dashboard/clientes/adicionar" class="btn-red">
                + Novo Cliente
            </a>
        </div>
    </div>

    <!-- CARD PRINCIPAL -->
    <div class="card-custom">

        <?php if (empty($clientes)): ?>
            <p class="text-muted">Nenhum cliente encontrado.</p>

        <?php else: ?>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Cadastrado em</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($clientes as $c): ?>
                    <tr>
                        <td>#<?= (int)$c['id'] ?></td>
                        <td><?= htmlspecialchars($c['nome']) ?></td>
                        <td><?= htmlspecialchars($c['email']) ?></td>
                        <td><?= htmlspecialchars($c['criado_em'] ?? '-') ?></td>

                        <td class="text-end">

                            <a class="action-btn action-edit"
                               href="<?= BASE_URL ?>/dashboard/clientes/editar/<?= (int)$c['id'] ?>">
                                ✏
                            </a>

                            <a class="action-btn action-del"
                               onclick="return confirm('Tem certeza que deseja excluir este cliente?')"
                               href="<?= BASE_URL ?>/dashboard/clientes/deletar/<?= (int)$c['id'] ?>">
                                🗑
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

</body>
</html>
