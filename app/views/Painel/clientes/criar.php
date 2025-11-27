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

    <title>Adicionar Cliente - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        * { font-family: 'Poppins', sans-serif; }
        html, body { background: radial-gradient(circle at top right, #111 0%, #000 80%); }
        body { color: #f5f5f5; min-height: 100vh; }

        .container-dashboard {
            max-width: 900px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        /* Header */
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
            margin: 0;
            color: #fff;
        }

        /* Card */
        .card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }
        .card-body { padding: 2rem; }

        /* Buttons */
        .btn {
            border-radius: 10px;
            font-weight: 600;
            padding: 0.65rem 1.3rem;
            transition: all 0.3s;
        }
        .btn-primary {
            background: linear-gradient(135deg, #e53e3e, #b30000);
            border: none;
            color: #fff;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(229, 62, 62, 0.3);
            color: #fff;
        }

        .btn-outline-light {
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .btn-outline-light:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.5);
            color: #fff;
        }

        /* Inputs */
        label { font-weight: 600; margin-bottom: 5px; }
        .form-control {
            background: #1a1a1a;
            border: 1px solid #333;
            color: #fff;
            padding: 12px;
            border-radius: 10px;
        }
        .form-control:focus {
            background: #1f1f1f;
            border-color: #e53e3e;
            box-shadow: 0 0 0 0.2rem rgba(229, 62, 62, 0.25);
            color: #fff;
        }

        @media (max-width: 768px) {
            .dashboard-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
            .card-body { padding: 1.5rem; }
        }
    </style>

</head>

<body>

<div class="container-dashboard">

    <div class="dashboard-header">
        <h1>Adicionar Cliente</h1>

        <a href="<?= BASE_URL ?>/dashboard/clientes" class="btn btn-outline-light">
            Voltar
        </a>
    </div>

    <div class="card">
        <div class="card-body">

            <form action="<?= BASE_URL ?>/dashboard/clientes/store" method="POST">

                <!-- Nome -->
                <div class="mb-3">
                    <label for="nome">Nome do Cliente</label>
                    <input type="text" id="nome" name="nome"
                           class="form-control" placeholder="Digite o nome" required>
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email"
                           class="form-control" placeholder="email@exemplo.com" required>
                </div>

                <!-- Botão -->
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        Salvar Cliente
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

</body>
</html>
