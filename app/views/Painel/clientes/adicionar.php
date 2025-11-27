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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

    <style>
        body { background:#0f0f10; color:#f5f5f5; font-family: 'Poppins', sans-serif; }
        .card { background:#151517; border:1px solid #26262a; border-radius: 15px; }

        .form-control, .form-select {
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.2);
            color: #f5f5f5;
            border-radius: 10px;
            padding: 12px 15px;
        }
        .form-control:focus, .form-select:focus {
            background: rgba(255,255,255,0.12);
            border-color: #e53e3e;
            color: #f5f5f5;
            box-shadow: 0 0 0 0.2rem rgba(229, 62, 62, 0.25);
        }
        .form-label { color: #f5f5f5; font-weight: 500; margin-bottom: 8px; }

        .btn-primary {
            background: linear-gradient(135deg, #e53e3e, #b30000);
            border: none; border-radius: 10px;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(229, 62, 62, 0.3);
        }

        .section-title {
            color: #e53e3e;
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 2rem;
            margin-bottom: 1rem;
            border-bottom: 1px solid rgba(229, 62, 62, 0.3);
            padding-bottom: 0.5rem;
        }
    </style>
</head>

<body class="p-3 p-md-4">
<div class="container-fluid" style="max-width: 900px;">

    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 m-0">Adicionar Novo Cliente</h1>
        <a href="<?= BASE_URL ?>/dashboard/clientes" class="btn btn-outline-light">
            <i class="fa fa-arrow-left me-2"></i> Voltar
        </a>
    </div>

    <!-- FLASH MESSAGE -->
    <?php if (!empty($_SESSION['flash_message'])):
        $f = $_SESSION['flash_message']; unset($_SESSION['flash_message']); ?>
        <div class="alert alert-<?= $f['type'] ?? 'info' ?> alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($f['text'] ?? '') ?>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-lg">
        <div class="card-body p-4">

            <form method="POST" action="<?= BASE_URL ?>/dashboard/clientes/store" id="formAddCliente" novalidate>

                <!-- Dados Básicos -->
                <h6 class="section-title"><i class="fas fa-user me-2"></i>Informações do Cliente</h6>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nome Completo *</label>
                        <input type="text" name="nome" class="form-control" required minlength="3" maxlength="180">
                        <div class="invalid-feedback">Nome é obrigatório</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">E-mail *</label>
                        <input type="email" name="email" class="form-control" required>
                        <div class="invalid-feedback">E-mail válido é obrigatório</div>
                    </div>
                </div>

                <!-- Contato -->
                <h6 class="section-title"><i class="fas fa-phone me-2"></i>Contato</h6>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Telefone</label>
                        <input type="text" name="telefone" class="form-control" placeholder="(00) 00000-0000">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Data de Nascimento</label>
                        <input type="date" name="data_nascimento" class="form-control">
                    </div>
                </div>

                <!-- Perfil -->
                <h6 class="section-title"><i class="fas fa-id-badge me-2"></i>Perfil</h6>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Gênero</label>
                        <select name="genero" class="form-select">
                            <option value="" selected>Selecione...</option>
                            <option value="masculino">Masculino</option>
                            <option value="feminino">Feminino</option>
                            <option value="outro">Outro</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Senha *</label>
                        <input type="password" name="senha" class="form-control" required minlength="6">
                        <div class="invalid-feedback">Senha obrigatória (mín. 6 caracteres)</div>
                    </div>
                </div>

                <div class="row g-3 mt-2">
                    <div class="col-md-6">
                        <label class="form-label">Papel</label>
                        <select name="role" class="form-select">
                            <option value="cliente" selected>Cliente</option>
                            <option value="admin">Administrador</option>
                            <option value="staff">Staff</option>
                        </select>
                    </div>
                </div>

                <!-- Preferências -->
                <h6 class="section-title"><i class="fas fa-envelope-open-text me-2"></i>Preferências</h6>

                <div class="mb-3">
                    <div class="form-check form-switch pt-2">
                        <input class="form-check-input" type="checkbox" name="newsletter" value="1">
                        <label class="form-check-label">Receber Newsletter</label>
                    </div>

                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="sms_marketing" value="1">
                        <label class="form-check-label">Receber SMS de Promoções</label>
                    </div>
                </div>

                <div class="d-grid gap-2 pt-3">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-check me-2"></i>Criar Cliente
                    </button>
                    <a href="<?= BASE_URL ?>/dashboard/clientes" class="btn btn-outline-light">Cancelar</a>
                </div>

            </form>

        </div>
    </div>
</div>

<script>
    document.getElementById("formAddCliente").addEventListener("submit", function (e) {
        if (!this.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        }
        this.classList.add("was-validated");
    });
</script>

</body>
</html>
