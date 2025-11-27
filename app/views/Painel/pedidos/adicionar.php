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

    <title>Criar Pedido - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        * { font-family: 'Poppins', sans-serif; }

        body {
            background: #0d0d0d;
            color: #fff;
        }

        .container-dashboard {
            max-width: 900px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .dashboard-header h1 {
            font-size: 30px;
            font-weight: 700;
        }

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

        .card-custom {
            background: linear-gradient(145deg, #111, #0a0a0a);
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0px 0px 20px rgba(0,0,0,0.4);
            border: solid 1px rgba(255,255,255,0.05);
        }

        label {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .form-control, .form-select {
            background: #1a1a1a;
            border: 1px solid #333;
            color: #fff;
            padding: 10px;
            border-radius: 10px;
        }
        .form-control:focus, .form-select:focus {
            background: #1f1f1f;
            border-color: #ff4747;
            box-shadow: 0 0 0 0.2rem rgba(255, 71, 71, 0.25);
            color: #fff;
        }
    </style>

</head>

<body>

<div class="container-dashboard">

    <!-- HEADER -->
    <div class="dashboard-header">
        <h1>Criar Pedido</h1>

        <a href="<?= BASE_URL ?>/dashboard/pedidos" class="btn-dark-outline">
            ⟵ Voltar
        </a>
    </div>


    <div class="card-custom">

        <form action="<?= BASE_URL ?>/dashboard/pedidos/store" method="POST">

            <!-- CLIENTE -->
            <div class="mb-3">
                <label for="usuario_id">Cliente</label>
                <select id="usuario_id" name="usuario_id" class="form-select" required>
                    <option value="">Selecione um cliente</option>

                    <?php foreach ($clientes as $c): ?>
                        <option value="<?= $c['id'] ?>">
                            <?= htmlspecialchars($c['nome']) ?> (<?= htmlspecialchars($c['email']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- STATUS -->
            <div class="mb-3">
                <label for="status">Status do Pedido</label>
                <select id="status" name="status" class="form-select" required>
                    <option value="pendente">Pendente</option>
                    <option value="processando">Processando</option>
                    <option value="enviado">Enviado</option>
                    <option value="concluido">Concluído</option>
                    <option value="cancelado">Cancelado</option>
                </select>
            </div>

            <!-- TOTAL -->
            <div class="mb-3">
                <label for="total">Valor Total (R$)</label>
                <input type="number" step="0.01" min="0" id="total" name="total"
                       class="form-control" placeholder="Ex: 149.90" required>
            </div>

            <!-- BOTÃO -->
            <div class="mt-4">
                <button type="submit" class="btn-red">Salvar Pedido</button>
            </div>

        </form>

    </div>

</div>

</body>
</html>
