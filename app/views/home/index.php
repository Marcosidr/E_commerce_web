<!-- Hero Section -->
<section class="hero-section bg-dark text-white position-relative">
    <div class="hero-overlay"></div>
    <div class="container">
        <div class="row align-items-center min-vh-100">
            <div class="col-lg-6">
                <div class="hero-content">
                    <span class="badge bg-primary mb-3">NOVA COLEÇÃO</span>
                    <h1 class="display-2 fw-black mb-4">
                        ESTILO<br>
                        URBANO<br>
                        <span class="text-primary">AUTÊNCTICO</span>
                    </h1>
                    <p class="lead mb-4">Descubra as últimas tendências em streetwear. Moda que representa sua atitude.</p>
                    <div class="d-flex gap-3">
                        <a href="<?= BASE_URL ?>/catalogo" class="btn btn-primary btn-lg px-4">VER COLEÇÃO</a>
                        <a href="<?= BASE_URL ?>/categoria/tenis" class="btn btn-outline-light btn-lg px-4">TÊNIS EXCLUSIVOS</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-image text-center">
                    <img src="<?= BASE_URL ?>/images/hero-sneaker.png" alt="Tênis URBANSTREET" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
    
    <!-- Cart notification -->
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 1050">
        <div class="cart-notification bg-dark text-white rounded p-3 d-flex align-items-center gap-2">
            <i class="fas fa-shopping-cart"></i>
            <span>0</span>
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="featured-section py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-4 fw-bold mb-3">DESTAQUES DA SEMANA</h2>
            <p class="text-muted">Os produtos mais desejados da nossa coleção</p>
        </div>
        
        <div class="row">
            <?php if (!empty($featuredProducts)): ?>
                <?php foreach ($featuredProducts as $product): ?>
                    <div class="col-lg-6 col-xl-3 mb-4">
                        <div class="product-card h-100">
                            <div class="product-image position-relative">
                                <img src="<?= $product['image'] ?? BASE_URL . '/images/no-image.jpg' ?>" 
                                     alt="<?= htmlspecialchars($product['name']) ?>" 
                                     class="img-fluid">
                                <div class="product-badge">
                                    <span class="badge bg-primary">DESTAQUE</span>
                                    <span class="badge bg-secondary"><?= strtoupper($product['category_name'] ?? 'URBAN STYLE') ?></span>
                                </div>
                                <div class="product-overlay">
                                    <a href="<?= BASE_URL ?>/produto/<?= $product['id'] ?>" class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button class="btn btn-secondary btn-sm add-to-cart" data-product-id="<?= $product['id'] ?>">
                                        <i class="fas fa-cart-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="product-info p-3">
                                <h5 class="product-title mb-2"><?= htmlspecialchars($product['name']) ?></h5>
                                <p class="product-price h4 text-primary mb-0">
                                    <?= App\Models\Product::formatPrice($product['price']) ?>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <p class="text-muted">Nenhum produto em destaque no momento.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="categories-section py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-4 fw-bold mb-3">CATEGORIAS</h2>
            <p class="text-muted">Explore nossa coleção por categoria</p>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-2 col-md-4 col-6">
                <a href="<?= BASE_URL ?>/categoria/tenis" class="category-card text-decoration-none">
                    <div class="category-icon mb-3">
                        <i class="fas fa-shoe-prints fa-3x text-primary"></i>
                    </div>
                    <h5 class="category-name">TÊNIS</h5>
                </a>
            </div>
            <div class="col-lg-2 col-md-4 col-6">
                <a href="<?= BASE_URL ?>/categoria/camisetas" class="category-card text-decoration-none">
                    <div class="category-icon mb-3">
                        <i class="fas fa-tshirt fa-3x text-primary"></i>
                    </div>
                    <h5 class="category-name">CAMISETAS</h5>
                </a>
            </div>
            <div class="col-lg-2 col-md-4 col-6">
                <a href="<?= BASE_URL ?>/categoria/moletons" class="category-card text-decoration-none">
                    <div class="category-icon mb-3">
                        <i class="fas fa-hoodie fa-3x text-primary"></i>
                    </div>
                    <h5 class="category-name">MOLETONS</h5>
                </a>
            </div>
            <div class="col-lg-2 col-md-4 col-6">
                <a href="<?= BASE_URL ?>/categoria/calcas" class="category-card text-decoration-none">
                    <div class="category-icon mb-3">
                        <i class="fas fa-jeans fa-3x text-primary"></i>
                    </div>
                    <h5 class="category-name">CALÇAS</h5>
                </a>
            </div>
            <div class="col-lg-2 col-md-4 col-6">
                <a href="<?= BASE_URL ?>/categoria/acessorios" class="category-card text-decoration-none">
                    <div class="category-icon mb-3">
                        <i class="fas fa-hat-cowboy fa-3x text-primary"></i>
                    </div>
                    <h5 class="category-name">ACESSÓRIOS</h5>
                </a>
            </div>
            <div class="col-lg-2 col-md-4 col-6">
                <a href="<?= BASE_URL ?>/catalogo" class="category-card text-decoration-none">
                    <div class="category-icon mb-3">
                        <i class="fas fa-th fa-3x text-primary"></i>
                    </div>
                    <h5 class="category-name">VER TODOS</h5>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Newsletter Section -->
<section class="newsletter-section py-5 bg-primary text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h2 class="display-5 fw-bold mb-3">FIQUE POR DENTRO</h2>
                <p class="lead mb-0">
                    Inscreva-se na nossa newsletter e receba em primeira mão as novidades, 
                    promoções exclusivas e drops limitados
                </p>
            </div>
            <div class="col-lg-6">
                <form class="newsletter-form-home" id="newsletterFormHome">
                    <div class="input-group input-group-lg">
                        <input type="email" class="form-control" name="email" placeholder="Seu melhor e-mail" required>
                        <button class="btn btn-dark" type="submit">INSCREVER</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>