<?php ob_start(); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Nossos Produtos</h1>
    
    <!-- Busca -->
    <form class="d-flex" method="GET" action="/buscar" id="search-form">
        <input class="form-control me-2" type="search" name="q" placeholder="Buscar produtos..." value="<?= $_GET['q'] ?? '' ?>">
        <button class="btn btn-outline-primary" type="submit">Buscar</button>
    </form>
</div>

<div class="row">
    <?php if (!empty($products)): ?>
        <?php foreach ($products as $product): ?>
            <div class="col-md-4 col-sm-6 mb-4">
                <div class="card h-100">
                    <img src="<?= getProductImageUrl($product['id']) ?>" class="card-img-top" alt="<?= htmlspecialchars($product['nome']) ?>">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?= htmlspecialchars($product['nome']) ?></h5>
                        <p class="card-text flex-grow-1"><?= htmlspecialchars(substr($product['descricao'] ?? '', 0, 100)) ?>...</p>
                        <div class="mt-auto">
                            <p class="card-text"><strong class="text-primary">R$ <?= number_format($product['preco'], 2, ',', '.') ?></strong></p>
                            <div class="d-flex gap-2">
                                <a href="/produto/<?= $product['id'] ?>" class="btn btn-primary flex-fill">Ver Detalhes</a>
                                <button class="btn btn-success add-to-cart" data-product-id="<?= $product['id'] ?>">
                                    <i class="fas fa-cart-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12">
            <div class="alert alert-info text-center" role="alert">
                <h4>Nenhum produto encontrado</h4>
                <p>Não há produtos disponíveis no momento.</p>
                <a href="/" class="btn btn-primary">Voltar ao Início</a>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php $content = ob_get_clean(); ?>
<?php require_once APP_PATH . '/views/layouts/main.php'; ?>