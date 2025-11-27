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

    <style>
        body { background:#0f0f10; color:#f5f5f5; font-family: 'Poppins', sans-serif; }
        .card { background:#151517; border:1px solid #26262a; border-radius: 15px; }
        .form-control, .form-select { background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.2); color: #f5f5f5; border-radius: 10px; padding: 12px 15px; }
        .form-control:focus, .form-select:focus { background: rgba(255,255,255,0.12); border-color: #e53e3e; color: #f5f5f5; box-shadow: 0 0 0 0.2rem rgba(229, 62, 62, 0.25); }
        .form-label { color: #f5f5f5; font-weight: 500; margin-bottom: 8px; }
        .btn-primary { background: linear-gradient(135deg, #e53e3e, #b30000); border: none; border-radius: 10px; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(229, 62, 62, 0.3); }
        .section-title { color: #e53e3e; font-weight: 600; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 2rem; margin-bottom: 1rem; border-bottom: 1px solid rgba(229, 62, 62, 0.3); padding-bottom: 0.5rem; }
        .img-preview { border-radius: 10px; max-height: 200px; object-fit: cover; }
    </style>
</head>

<body class="p-3 p-md-4">
<div class="container-fluid" style="max-width: 900px;">

    <!-- CABEÇALHO -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 m-0">Adicionar Novo Produto</h1>
        <a href="<?= BASE_URL ?>/dashboard/produtos" class="btn btn-outline-light">
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

    <!-- FORMULÁRIO -->
    <div class="card shadow-lg">
        <div class="card-body p-4">
            <form method="POST" action="<?= BASE_URL ?>/dashboard/produtos/store" enctype="multipart/form-data" id="formAddProduct" novalidate>

                <!-- INFORMAÇÕES BÁSICAS -->
                <h6 class="section-title"><i class="fas fa-info-circle me-2"></i>Informações Básicas</h6>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nome do Produto *</label>
                        <input type="text" name="name" class="form-control" placeholder="Ex: Tênis Air Force Urban" required minlength="3" maxlength="180">
                        <div class="invalid-feedback">Nome é obrigatório (mín. 3 caracteres)</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Marca *</label>
                        <input type="text" name="brand" class="form-control" placeholder="Ex: NIKE" required minlength="2" maxlength="120">
                        <div class="invalid-feedback">Marca é obrigatória</div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Descrição *</label>
                    <textarea name="description" class="form-control" placeholder="Descreva o produto em detalhes..." rows="4" required minlength="10" maxlength="1000"></textarea>
                    <div class="invalid-feedback">Descrição é obrigatória (mín. 10 caracteres)</div>
                </div>

                <!-- CATEGORIA E PREÇO -->
                <h6 class="section-title"><i class="fas fa-tag me-2"></i>Categoria e Preço</h6>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Categoria *</label>
                        <select name="category_id" class="form-select" required>
                            <option value="" disabled selected>Selecione uma categoria...</option>
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
                        <input type="number" name="price" class="form-control" placeholder="0.00" step="0.01" min="0" required>
                        <div class="invalid-feedback">Preço é obrigatório</div>
                    </div>
                </div>

                <!-- ESTOQUE E STATUS -->
                <h6 class="section-title"><i class="fas fa-box me-2"></i>Estoque e Status</h6>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Quantidade em Estoque *</label>
                        <input type="number" name="stock_quantity" class="form-control" placeholder="0" min="0" required>
                        <div class="invalid-feedback">Estoque é obrigatório</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Status</label>
                        <div class="form-check form-switch pt-2">
                            <input class="form-check-input" type="checkbox" name="active" value="1" id="statusAtivo" checked>
                            <label class="form-check-label" for="statusAtivo">
                                Ativo (visível no catálogo)
                            </label>
                        </div>
                    </div>
                </div>

                <!-- DESTAQUE -->
                <div class="mb-3">
                    <div class="form-check form-switch pt-2">
                        <input class="form-check-input" type="checkbox" name="featured" value="1" id="destaque">
                        <label class="form-check-label fw-bold" for="destaque" style="color: #e53e3e;">
                            <i class="fas fa-star me-2"></i>Marcar como Destaque (exibir na home)
                        </label>
                    </div>
                </div>

                <!-- IMAGENS -->
                <h6 class="section-title"><i class="fas fa-image me-2"></i>Imagens do Produto</h6>

                <div class="mb-3">
                    <label class="form-label">Upload de Imagens</label>
                    <div class="input-group">
                        <input type="file" name="images[]" class="form-control" accept="image/*" multiple id="fileImages">
                        <label class="input-group-text bg-dark border-secondary" for="fileImages">
                            <i class="fas fa-cloud-upload-alt"></i>
                        </label>
                    </div>
                    <small class="text-muted d-block mt-2">
                        <i class="fas fa-info-circle"></i> Formatos: JPG, PNG, WebP | Máx. 5MB por imagem
                    </small>
                </div>

                <div id="imagePreview" class="row g-2 mb-3"></div>

                <!-- BOTÕES -->
                <div class="d-grid gap-2 pt-3">
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
        
        Array.from(this.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function(event) {
                const col = document.createElement('div');
                col.className = 'col-md-4 col-lg-3';
                col.innerHTML = `<img src="${event.target.result}" class="img-preview img-fluid" alt="Preview">`;
                preview.appendChild(col);
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
