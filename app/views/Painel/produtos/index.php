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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">

    <style>
        * { font-family: 'Poppins', sans-serif; }
        body { 
            background: radial-gradient(circle at top right, #111 0%, #000 80%);
            color: #f5f5f5; 
            min-height: 100vh;
        }
        .container-dashboard { max-width: 1200px; margin: 0 auto; padding: 2rem 1rem; }
        
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
            font-size: 2rem;
            font-weight: 800;
            margin: 0;
            color: #fff;
        }
        .btn-group-header {
            display: flex;
            gap: 1rem;
        }
        
        /* Cards */
        .card-dashboard {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 1.5rem;
        }
        
        /* Table */
        .table-responsive {
            border-radius: 10px;
            overflow: hidden;
        }
        .table {
            margin-bottom: 0;
            color: #f5f5f5;
        }
        .table thead {
            background: rgba(229, 62, 62, 0.1);
            border-bottom: 2px solid rgba(229, 62, 62, 0.3);
        }
        .table thead th {
            color: #e53e3e;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 1rem;
            border: none;
        }
        .table tbody tr {
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            transition: background 0.2s;
        }
        .table tbody tr:hover {
            background: rgba(229, 62, 62, 0.05);
        }
        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
        }
        
        /* Badges */
        .badge-status {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.8rem;
        }
        .badge-ativo {
            background: rgba(40, 167, 69, 0.2);
            color: #6bcf7e;
            border-left: 3px solid #28a745;
        }
        .badge-inativo {
            background: rgba(108, 117, 125, 0.2);
            color: #b0b8c1;
            border-left: 3px solid #6c757d;
        }
        
        /* Buttons */
        .btn-success {
            background: linear-gradient(135deg, #28a745, #1e7e34);
            border: none;
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
            color: #fff;
        }
        .btn-outline-light {
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            transition: all 0.3s;
        }
        .btn-outline-light:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.5);
            color: #fff;
        }
        .btn-edit {
            background: linear-gradient(135deg, #e53e3e, #b30000);
            border: none;
            border-radius: 8px;
            padding: 0.5rem 0.75rem;
            color: #fff;
            transition: all 0.2s;
        }
        .btn-edit:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(229, 62, 62, 0.3);
        }
        
        /* Switch */
        .form-switch .form-check-input {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.2);
            width: 2.5rem;
            height: 1.5rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        .form-switch .form-check-input:checked {
            background: #e53e3e;
            border-color: #e53e3e;
        }
        
        /* Alert */
        .alert {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            margin-bottom: 1.5rem;
        }
        .alert-success {
            background: rgba(40, 167, 69, 0.1) !important;
            border-left: 4px solid #28a745;
            color: #6bcf7e;
        }
        .alert-danger {
            background: rgba(220, 53, 69, 0.1) !important;
            border-left: 4px solid #dc3545;
            color: #ff6b7a;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .dashboard-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
            .btn-group-header {
                width: 100%;
            }
            .btn-group-header button,
            .btn-group-header a {
                flex: 1;
            }
        }
    </style>
</head>

<body>
<div class="container-dashboard">

    <!-- HEADER -->
    <div class="dashboard-header">
        <h1><i class="fas fa-cube me-3" style="color: #e53e3e;"></i>Produtos</h1>
        <div class="btn-group-header">
            <a href="<?= BASE_URL ?>/dashboard/produtos/criar" class="btn btn-success">
                <i class="fa fa-plus me-2"></i> Novo Produto
            </a>
            <a href="<?= BASE_URL ?>/dashboard" class="btn btn-outline-light">
                <i class="fa fa-arrow-left me-2"></i> Dashboard
            </a>
        </div>
    </div>

    <!-- FLASH MESSAGE -->
    <?php if (!empty($_SESSION['flash_message'])): 
        $f = $_SESSION['flash_message']; unset($_SESSION['flash_message']); ?>
        <div class="alert alert-<?= $f['type'] ?? 'info' ?> alert-dismissible fade show" role="alert">
            <i class="fas fa-<?= $f['type'] === 'success' ? 'check-circle' : 'exclamation-circle' ?> me-2"></i>
            <?= htmlspecialchars($f['text'] ?? '') ?>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- TABELA DE PRODUTOS -->
    <div class="card-dashboard">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Preço</th>
                        <th>Estoque</th>
                        <th>Status</th>
                        <th>Destaque</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($produtos)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="fas fa-inbox" style="font-size: 2rem; opacity: 0.5; margin-bottom: 1rem;"></i>
                            <p class="text-muted mb-0">Nenhum produto cadastrado</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($produtos as $p): ?>
                    <tr>
                        <td><strong>#<?= (int)$p['id'] ?></strong></td>
                        <td><?= htmlspecialchars($p['nome']) ?></td>
                        <td>
                            <strong style="color: #e53e3e;">
                                R$ <?= number_format((float)$p['preco'], 2, ',', '.') ?>
                            </strong>
                        </td>
                        <td><?= (int)($p['estoque'] ?? 0) ?> unid.</td>
                        <td>
                            <span class="badge-status <?= ($p['ativo'] ?? 0) ? 'badge-ativo' : 'badge-inativo' ?>">
                                <?= ($p['ativo'] ?? 0) ? '✓ Ativo' : '✗ Inativo' ?>
                            </span>
                        </td>
                        <td>
                            <div class="form-check form-switch">
                                <input class="form-check-input toggle-featured"
                                       type="checkbox"
                                       data-id="<?= (int)$p['id'] ?>"
                                       id="featured<?= (int)$p['id'] ?>"
                                       <?= (int)($p['destaque'] ?? 0) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="featured<?= (int)$p['id'] ?>">
                                    <i class="fas fa-star" style="color: <?= (int)($p['destaque'] ?? 0) ? '#e53e3e' : '#999' ?>;"></i>
                                </label>
                            </div>
                        </td>
                        <td class="text-center">
                            <a class="btn btn-edit btn-sm"
                               href="<?= BASE_URL ?>/dashboard/produtos/<?= (int)$p['id'] ?>/editar"
                               title="Editar produto">
                                <i class="fa fa-pen"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.querySelectorAll('.toggle-featured').forEach(el => {
    el.addEventListener('change', async function(){
        const id = this.dataset.id;
        const featured = this.checked ? 1 : 0;
        const icon = this.closest('td').querySelector('i.fa-star');

        try {
            const res = await fetch('<?= BASE_URL ?>/dashboard/produtos/' + id + '/destaque', {
                method: 'POST',
                headers: {'Content-Type':'application/x-www-form-urlencoded'},
                body: 'featured=' + featured
            });
            const data = await res.json();

            if (!data.success) {
                this.checked = !this.checked;
                icon.style.color = this.checked ? '#e53e3e' : '#999';
                alert('Falha ao alterar destaque');
            } else {
                icon.style.color = this.checked ? '#e53e3e' : '#999';
            }
        } catch (e) {
            this.checked = !this.checked;
            icon.style.color = this.checked ? '#e53e3e' : '#999';
            alert('Erro ao alterar destaque');
        }
    });
});
</script>

</body>
</html>
