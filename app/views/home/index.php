<?php
    // Força uso prioritário da imagem específica solicitada (banners/tenis.png)
    $heroImage = null;
    $forced = 'banners/tenis.png';
    $forcedPath = PUBLIC_PATH . '/images/' . $forced;
    if (file_exists($forcedPath) && is_file($forcedPath)) {
        $heroImage = $forced; // guarda caminho relativo para mapear classe
    }

    // Lista de candidatos caso a principal não esteja disponível
    if (!$heroImage) {
        $heroCandidates = [
            'banners/tenis.png',
            'banners/banner.jpg',
            'hero-sneaker.png', 'hero-sneaker.jpg',
            'hero-tenis.jpg', 'banner-tenis.jpg', 'hero.jpg'
        ];
        foreach ($heroCandidates as $file) {
            $full = PUBLIC_PATH . '/images/' . $file;
            if (file_exists($full) && is_file($full)) {
                $heroImage = $file;
                break;
            }
        }
    }

    if (!$heroImage) { $heroImage = 'fallback'; }

    $heroClassMap = [
        'banners/tenis.png'    => 'hero-bg-tenis-png',
        'banners/banner.jpg'   => 'hero-bg-banner-jpg',
        'hero-sneaker.png'     => 'hero-bg-hero-sneaker-png',
        'hero-sneaker.jpg'     => 'hero-bg-hero-sneaker-jpg',
        'hero-tenis.jpg'       => 'hero-bg-hero-tenis-jpg',
        'banner-tenis.jpg'     => 'hero-bg-banner-tenis-jpg',
        'hero.jpg'             => 'hero-bg-hero-jpg',
        'fallback'             => 'hero-bg-fallback'
    ];
    $heroBgClass = $heroClassMap[$heroImage] ?? 'hero-bg-fallback';
?>
<!-- Hero Section com blur -->
<section class="hero-section <?= htmlspecialchars($heroBgClass) ?>">
    <div class="hero-backdrop"></div>
    <div class="container position-relative">
        <div class="row min-vh-100 align-items-center">
            <div class="col-xl-7 col-lg-8 col-md-10">
                <div class="hero-glass">
                    <span class="badge badge-nova mb-3">NOVA COLEÇÃO</span>
                    <h1 class="display-2 fw-black mb-4 hero-title">
                        ESTILO<br>
                        URBANO<br>
                        <span class="text-primary">AUTÊNTICO</span>
                    </h1>
                    <p class="lead mb-4 hero-sub">
                        Descubra as últimas tendências em streetwear. Moda que representa sua atitude.
                    </p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="<?= BASE_URL ?>/catalogo" class="btn btn-primary btn-lg px-4 shadow-sm">VER COLEÇÃO</a>
                        <a href="<?= getCategoryUrl('tenis') ?>" class="btn btn-outline-light btn-lg px-4">TÊNIS EXCLUSIVOS</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="hero-noise"></div>
</section>

<!-- Featured Products -->
<section class="featured-section py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-4 fw-bold mb-3">
                <span style="color: #fff; text-shadow: 0 2px 12px rgba(0,0,0,0.25);">DESTAQUES</span>
                <span style="color: #E53E3E; text-shadow: 0 2px 12px rgba(0,0,0,0.15);">DA</span>
                <span style="color: #E53E3E; text-shadow: 0 2px 12px rgba(0,0,0,0.15);">SEMANA</span>
            </h2>
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
<!-- Newsletter Section -->
<section class="newsletter-section py-5">
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
                        <button class="btn btn-primary" type="submit">INSCREVER</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<!-- Estilos específicos da newsletter -->
</section>
<style>
.newsletter-section { background:#000; }
.newsletter-section h2 { color: var(--primary-color); }
.newsletter-section p { color:#fff; }
.newsletter-section .form-control { background:#111; border:1px solid #333; color:#fff; }
.newsletter-section .form-control:focus { border-color: var(--primary-color); box-shadow:0 0 0 2px rgba(229,62,62,0.35); }
.newsletter-section .form-control::placeholder { color:#888; }
.newsletter-section .btn-primary { background: var(--primary-gradient); border:none; font-weight:600; }
.newsletter-section .btn-primary:hover { background: linear-gradient(135deg,#ff5555,#a60000); }
</style>
<!-- Categories Section -->
<section class="categories-section py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-4 fw-black">EXPLORE NOSSAS <span class="text-primary">CATEGORIAS</span></h2>
            <p class="text-muted">Acesso rápido aos estilos que definem a rua.</p>
        </div>
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-4">

            <!-- CARD DE CATEGORIA 1: TÊNIS -->
            <div class="col">
                <a href="<?= getCategoryUrl('tenis') ?>" class="product-card text-decoration-none">
                    <div class="product-image">
                        <img src="<?= BASE_URL ?>/images/categories/b1.png"
                             alt="Categoria Tênis"
                             class="img-fluid"
                             onerror="this.onerror=null;this.src='https://placehold.co/600x600/1A1A1A/E53E3E?text=T%C3%8ANIS';"
                        >
                    </div>
                    <div class="product-info text-center py-3 px-2">
                        <h5 class="product-title m-0" style="color: #fff;">TÊNIS STREET</h5>
                    </div>
                </a>
            </div>

            <!-- CARD DE CATEGORIA 2: CAMISETAS -->
            <div class="col">
                <a href="<?= getCategoryUrl('camisetas') ?>" class="product-card text-decoration-none">
                    <div class="product-image">
                        <img src="<?= BASE_URL ?>/images/categories/b2.png"
                             alt="Categoria Camisetas"
                             class="img-fluid"
                             onerror="this.onerror=null;this.src='https://placehold.co/600x600/1A1A1A/FFC107?text=CAMISETAS';"
                        >
                    </div>
                    <div class="product-info text-center py-3 px-2">
                        <h5 class="product-title m-0" style="color: #fff;">CAMISETAS</h5>
                    </div>
                </a>
            </div>

            <!-- CARD DE CATEGORIA 3: MOLETONS -->
            <div class="col">
                <a href="<?= getCategoryUrl('moletons') ?>" class="product-card text-decoration-none">
                    <div class="product-image">
                        <img src="<?= BASE_URL ?>/images/categories/b3.png"
                             alt="Categoria Moletons"
                             class="img-fluid"
                             onerror="this.onerror=null;this.src='https://placehold.co/600x600/1A1A1A/00B894?text=MOLETONS';"
                        >
                    </div>
                    <div class="product-info text-center py-3 px-2">
                        <h5 class="product-title m-0" style="color: #fff;">MOLETONS</h5>
                    </div>
                </a>
            </div>

            <!-- CARD DE CATEGORIA 4: CALÇA -->
            <div class="col">
                <a href="<?= getCategoryUrl('calca') ?>" class="product-card text-decoration-none">
                    <div class="product-image">
                        <img src="<?= BASE_URL ?>/images/categories/b4.png"
                             alt="Categoria Calça"
                             class="img-fluid"
                             onerror="this.onerror=null;this.src='https://placehold.co/600x600/1A1A1A/6C757D?text=CAL%C3%87A';"
                        >
                    </div>
                    <div class="product-info text-center py-3 px-2">
                        <h5 class="product-title m-0" style="color: #fff;">CALÇA</h5>
                    </div>
                </a>
            </div>

            <!-- CARD DE CATEGORIA 5: CATÁLOGO COMPLETO -->
            <div class="col">
                <a href="<?= BASE_URL ?>/catalogo" class="product-card text-decoration-none card-all">
                    <div class="product-image">
                        <img src="<?= BASE_URL ?>/images/categories/b5.png"
                             alt="Catálogo Completo"
                             class="img-fluid"
                             onerror="this.onerror=null;this.src='https://placehold.co/600x600/1A1A1A/007BFF?text=CAT%C3%81LOGO';"
                        >
                    </div>
                    <div class="product-info text-center py-3 px-2">
                        <h5 class="product-title m-0" style="color: #fff;">CATÁLOGO COMPLETO</h5>
                    </div>
                </a>
            </div>

        </div>
    </div>
</section>
