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

  <!-- ESTILO IGUAL AO DA LISTAGEM DE PRODUTOS -->
  <style>
        * { font-family: "Poppins", sans-serif; }

        body {
            background: radial-gradient(circle at top right, #111 0%, #000 80%);
            color: #f5f5f5;
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
            padding-bottom: 20px;
            margin-bottom: 30px;
            border-bottom: 2px solid rgba(229, 62, 62, 0.3);
        }

        .dashboard-header h1 {
            font-size: 32px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #fff;
        }

        /* BOTÃO PADRÃO VERMELHO */
        .btn-red {
            background: linear-gradient(135deg, #e53e3e, #b30000);
            border: none;
            padding: 12px 22px;
            font-weight: 600;
            color: #fff;
            border-radius: 10px;
            transition: 0.2s;
        }
        .btn-red:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(229, 62, 62, 0.3);
        }

        /* BOTÃO OUTLINE */
        .btn-dark-outline {
            border-radius: 10px;
            font-weight: 600;
            padding: 12px 22px;
            border: 1px solid rgba(255,255,255,0.3);
            background: transparent;
            color: #fff;
            transition: 0.3s;
        }
        .btn-dark-outline:hover {
            background: rgba(255,255,255,0.1);
            border-color: rgba(255,255,255,0.5);
        }

        /* CARD PRINCIPAL */
        .card-custom {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.3);
        }

        /* TABELA */
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 10px;
        }

        thead th {
            text-transform: uppercase;
            font-size: 13px;
            color: #e53e3e;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        tbody tr {
            background: rgba(255,255,255,0.05);
            border-radius: 12px;
            transition: 0.2s;
        }

        tbody tr:hover {
            background: rgba(255,255,255,0.1);
        }

        tbody td {
            padding: 18px;
        }

        /* AÇÕES */
        .action-btn {
            padding: 8px 12px;
            border-radius: 10px;
            color: #fff;
            border: none;
            transition: 0.2s;
        }

        .action-edit {
            background: linear-gradient(135deg, #e53e3e, #b30000);
        }
        .action-edit:hover {
            transform: scale(1.07);
        }

        .action-del {
            background: linear-gradient(135deg, #6b0000, #b30000);
        }
        .action-del:hover {
            transform: scale(1.07);
        }
  </style>

</head>

<body>

<div class="container-dashboard">

    <!-- HEADER -->
    <div class="dashboard-header">
        <h1>👥 Clientes</h1>

        <div class="d-flex gap-2">
            <a href="<?= BASE_URL ?>/dashboard" class="btn-dark-outline">← Dashboard</a>

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
