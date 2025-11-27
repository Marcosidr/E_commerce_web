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
    <title>Adicionar Produto - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">

    <style>
        * { font-family: 'Poppins', sans-serif; }
        html, body { background: radial-gradient(circle at top right, #111 0%, #000 80%); }
        body { color: #f5f5f5; min-height: 100vh; }
        
        .container-dashboard { max-width: 900px; margin: 0 auto; padding: 2rem 1rem; }
        
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
        
        /* Form */
        .form-control, .form-select {
            background: rgba(255, 255, 255, 0.08) !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
            color: #f5f5f5 !important;
            border-radius: 10px;
            padding: 12px 15px;
            transition: all 0.3s;
        }
        .form-control::placeholder { 
            color: rgba(255, 255, 255, 0.5) !important; 
        }
        .form-control:focus, .form-select:focus {
            background: rgba(255, 255, 255, 0.12) !important;
            border-color: #e53e3e !important;
            color: #f5f5f5 !important;
            box-shadow: 0 0 0 0.3rem rgba(229, 62, 62, 0.2) !important;
        }
        .form-select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3E%3Cpath fill='none' stroke='%23f5f5f5' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 16px 12px;
            padding-right: 2.5rem;
        }
        .form-select option {
            background: #1a1a1d;
            color: #f5f5f5;
            padding: 10px;
        }
        .form-select option:hover {
            background: rgba(229, 62, 62, 0.3);
        }
        .form-select option:checked {
            background: linear-gradient(#e53e3e, #e53e3e);
            color: #fff;
        }
        
        /* Labels */
        .form-label {
            color: #f5f5f5;
            font-weight: 600;
            margin-bottom: 10px;
            font-size: 0.95rem;
        }
        
        /* Section Title */
        .section-title {
            color: #e53e3e;
            font-weight: 700;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 2.5rem;
            margin-bottom: 1.5rem;
            border-bottom: 2px solid rgba(229, 62, 62, 0.3);
            padding-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        /* Buttons */
        .btn {
            border-radius: 10px;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
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
        
        /* Checkbox/Switch */
        .form-check {
            padding-left: 0;
        }
        .form-check-input {
            width: 1.25rem;
            height: 1.25rem;
            margin-left: 0;
            margin-right: 0.75rem;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .form-check-input:checked {
            background: #e53e3e;
            border-color: #e53e3e;
        }
        .form-check-input:focus {
            box-shadow: 0 0 0 0.25rem rgba(229, 62, 62, 0.25);
        }
        .form-check-label {
            color: #f5f5f5;
            cursor: pointer;
            margin-bottom: 0;
        }
        
        /* Switch */
        .form-switch .form-check-input {
            width: 2.5rem;
            height: 1.5rem;
            border-radius: 1rem;
        }
        
        /* Validação */
        .invalid-feedback {
            color: #ff6b7a;
            display: block;
            font-size: 0.85rem;
            margin-top: 0.25rem;
        }
        .form-control.is-invalid,
        .form-select.is-invalid {
            border-color: #ff6b7a !important;
            background-image: none;
        }
        
        /* Input Group */
        .input-group-text {
            background: rgba(255, 255, 255, 0.08) !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
            color: #f5f5f5 !important;
            border-radius: 10px;
        }
        
        /* Alert */
        .alert {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            color: #f5f5f5;
        }
        .alert-success {
            background: rgba(40, 167, 69, 0.1) !important;
            border-left: 4px solid #28a745;
            color: #6bcf7e !important;
        }
        .alert-danger {
            background: rgba(220, 53, 69, 0.1) !important;
            border-left: 4px solid #dc3545;
            color: #ff6b7a !important;
        }
        
        /* Image Preview */
        .img-preview-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 1rem;
        }
        .img-preview-item {
            position: relative;
            border-radius: 10px;
            overflow: hidden;
            border: 2px solid rgba(229, 62, 62, 0.3);
            transition: all 0.2s;
        }
        .img-preview-item:hover {
            border-color: rgba(229, 62, 62, 0.6);
            box-shadow: 0 4px 12px rgba(229, 62, 62, 0.2);
        }
        .img-preview {
            width: 100%;
            height: 150px;
            object-fit: cover;
            display: block;
        }
        .img-name {
            font-size: 0.75rem;
            color: #999;
            padding: 0.5rem;
            text-align: center;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            background: rgba(0, 0, 0, 0.3);
        }
        
        /* Small text */
        .text-muted {
            color: rgba(255, 255, 255, 0.6) !important;
            font-size: 0.85rem;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .dashboard-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
            .card-body { padding: 1.5rem; }
            .section-title { margin-top: 1.5rem; }
        }
    </style>
</head>

<body>
<div class="container-dashboard">

    <!-- CABEÇALHO -->
    <div class="dashboard-header">
        <h1><i class="fas fa-cube me-3" style="color: #e53e3e;"></i>Adicionar Novo Produto</h1>
        <a href="<?= BASE_URL ?>/dashboard/produtos" class="btn btn-outline-light">
            <i class="fa fa-arrow-left me-2"></i> Voltar
        </a>
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

    <!-- FORMULÁRIO -->
    <div class="card shadow-lg">
        <div class="card-body">
            <form method="post" enctype="multipart/form-data" action="<?= BASE_URL ?>/dashboard/produtos/store" id="formAddProduct" novalidate>

                <!-- INFORMAÇÕES BÁSICAS -->
                <div class="section-title">
                    <i class="fas fa-info-circle"></i>
                    Informações Básicas
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nome do Produto *</label>
                        <input type="text" name="nome" class="form-control" placeholder="Ex: Camiseta Oversized" required minlength="3" maxlength="180">
                        <div class="invalid-feedback">Nome é obrigatório (mínimo 3 caracteres)</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Marca *</label>
                        <input type="text" name="marca" class="form-control" placeholder="Ex: NIKE" required minlength="2" maxlength="120">
                        <div class="invalid-feedback">Marca é obrigatória</div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Descrição *</label>
                    <textarea name="descricao" class="form-control" rows="4" placeholder="Descreva o produto em detalhes..." required minlength="10" maxlength="1000"></textarea>
                    <div class="invalid-feedback">Descrição é obrigatória (mínimo 10 caracteres)</div>
                </div>

                <!-- CATEGORIA E PREÇO -->
                <div class="section-title">
                    <i class="fas fa-tag"></i>
                    Categoria e Preço
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Categoria *</label>
                        <select name="categoria_id" class="form-select" required>
                            <option value="" selected disabled>Selecione uma categoria...</option>
                            <?php foreach (($categorias ?? []) as $cat): ?>
                                <option value="<?= (int)$cat['id'] ?>">
                                    <?= htmlspecialchars($cat['nome']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback">Categoria é obrigatória</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Preço (R$) *</label>
                        <input type="number" name="preco" class="form-control" step="0.01" min="0" placeholder="0.00" required>
                        <div class="invalid-feedback">Preço é obrigatório</div>
                    </div>
                </div>

                <!-- ESTOQUE E STATUS -->
                <div class="section-title">
                    <i class="fas fa-box"></i>
                    Estoque e Status
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Quantidade em Estoque *</label>
                        <input type="number" name="estoque" class="form-control" min="0" value="0" required>
                        <div class="invalid-feedback">Estoque é obrigatório</div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <div class="form-check form-switch pt-1">
                                <input class="form-check-input" type="checkbox" name="ativo" value="1" id="statusAtivo" checked>
                                <label class="form-check-label" for="statusAtivo">
                                    Ativo (visível no catálogo)
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- DESTAQUE -->
                <div class="form-check form-switch mb-3 pb-3" style="border-bottom: 2px solid rgba(229, 62, 62, 0.3);">
                    <input class="form-check-input" type="checkbox" name="destaque" value="1" id="destaque">
                    <label class="form-check-label fw-bold" for="destaque" style="color: #e53e3e;">
                        <i class="fas fa-star me-2"></i>Marcar como Destaque (exibir na home)
                    </label>
                </div>

                <!-- IMAGENS -->
                <div class="section-title">
                    <i class="fas fa-image"></i>
                    Imagens do Produto
                </div>

                <div class="mb-3">
                    <label class="form-label">Adicionar Imagens *</label>
                    <div class="input-group">
                        <input type="file" name="imagens[]" class="form-control" accept="image/*" multiple id="fileImages" required>
                        <label class="input-group-text" for="fileImages">
                            <i class="fas fa-cloud-upload-alt"></i>
                        </label>
                    </div>
                    <small class="text-muted d-block mt-2">
                        <i class="fas fa-info-circle"></i> Formatos: JPG, PNG, WebP | Máx. 5MB por imagem | Você pode adicionar quantas fotos desejar
                    </small>
                    <div class="invalid-feedback d-block">Adicione pelo menos uma imagem</div>
                </div>

                <div id="imagePreview" class="img-preview-container mb-4"></div>

                <!-- BOTÕES -->
                <div class="d-grid gap-2 pt-2">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-check me-2"></i>Criar Produto
                    </button>
                    <a href="<?= BASE_URL ?>/dashboard/produtos" class="btn btn-outline-light">
                        Cancelar
                    </a>
                </div>

            </form>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Preview de imagens
    document.getElementById('fileImages').addEventListener('change', function(e) {
        const preview = document.getElementById('imagePreview');
        preview.innerHTML = '';
        
        if (this.files.length === 0) return;
        
        Array.from(this.files).forEach((file, index) => {
            if (!file.type.startsWith('image/')) return;
            
            const reader = new FileReader();
            reader.onload = function(event) {
                const item = document.createElement('div');
                item.className = 'img-preview-item';
                item.innerHTML = `
                    <img src="${event.target.result}" class="img-preview" alt="Preview ${index + 1}">
                    <div class="img-name" title="${file.name}">${file.name}</div>
                `;
                preview.appendChild(item);
            };
            reader.readAsDataURL(file);
        });
    });

    // Validação do formulário
    document.getElementById('formAddProduct').addEventListener('submit', function(e) {
        if (!this.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        }
        this.classList.add('was-validated');
    }, false);
</script>

</body>
</html>
