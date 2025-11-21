<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$currentUser = $_SESSION['users'] ?? null;
$isAdminSession = isset($currentUser['role']) && $currentUser['role'] === 'admin';
$cartCount = isset($_SESSION['carrinho_count']) ? (int)$_SESSION['carrinho_count'] : 0;
$firstName = null;
if ($currentUser && !empty($currentUser['nome'])) {
    $parts = explode(' ', trim($currentUser['nome']));
    $firstName = $parts[0] ?? $currentUser['nome'];
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'URBANSTREET - Estilo Urbano Autêntico' ?></title>
    <meta name="description"
        content="<?= $metaDescription ?? 'Descubra as últimas tendências em streetwear. Moda que representa sua atitude.' ?>">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?= BASE_URL ?>/css/urbanstreet.css?v=<?= time() ?>" rel="stylesheet">
    <script>
        window.BASE_URL = '<?= BASE_URL ?>';
    </script>
</head>

<body class="<?= $pageClass ?? '' ?>">
    <!-- Header -->
    <header class="navbar-dark bg-dark fixed-top">
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <!-- Logo -->
                <a class="navbar-brand logo fw-bold fs-3" href="<?= BASE_URL ?>">
                    <span class="urban">URBAN</span><span class="street">STREET</span>
                </a>

                <!-- Mobile toggle -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Navigation -->
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" style="color: white;" href="<?= BASE_URL ?>/catalogo">CATÁLOGO</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" style="color: white;" href="<?= BASE_URL ?>/sobre">SOBRE </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" style="color: white;" href="<?= BASE_URL ?>/contato">CONTATO</a>
                        </li>
                    </ul>

                    <!-- Search & Cart -->
                    <div class="d-flex align-items-center gap-3">
                        <!-- Search -->
                        <form class="d-flex" method="GET" action="<?= BASE_URL ?>/catalogo">
                            <div class="input-group">
                                <input class="form-control" type="search" name="q" placeholder="Buscar produtos..."
                                    value="<?= $_GET['q'] ?? '' ?>">
                                <button class="btn btn-outline-light" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>

                        <!-- Cart -->
                        <a href="<?= BASE_URL ?>/carrinho" class="btn btn-outline-light position-relative cart-btn">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-badge <?= $cartCount > 0 ? '' : 'd-none' ?>" id="cartBadge">
                                <?= $cartCount ?>
                            </span>
                        </a>

                    

                        <!-- Profile -->
                        <?php if ($currentUser): ?>
                            <div class="dropdown">
                                <button class="btn btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-user-circle me-1"></i>
                                    <?= htmlspecialchars($firstName ?? 'Minha conta') ?>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><span class="dropdown-item-text text-muted">Logado como <?= htmlspecialchars($currentUser['email']) ?></span></li>
                                    <?php if ($isAdminSession): ?>
                                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/dashboard">Ir para o dashboard</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                    <?php endif; ?>
                                    <li><a class="dropdown-item" href="<?= BASE_URL ?>/carrinho">Meu carrinho</a></li>
                                    <li><a class="dropdown-item" href="<?= BASE_URL ?>/logout">Sair</a></li>
                                </ul>
                            </div>
                        <?php else: ?>
                            <a href="<?= BASE_URL ?>/login" class="btn btn-outline-light position-relative perfil-btn">
                                <i class="fas fa-user perfil"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <?= $content ?? '' ?>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white py-5 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5 class="fw-bold mb-3">URBANSTREET</h5>
                    <p class="text-light">Moda streetwear autêntica para quem vive a cultura urbana.</p>
                </div>

                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="fw-bold mb-3">CATEGORIAS</h6>
                    <ul class="list-unstyled">
                        <li><a href="<?= getCategoryUrl('tenis') ?>" class="text-light text-decoration-none">Tênis</a></li>
                        <li><a href="<?= getCategoryUrl('camisetas') ?>" class="text-light text-decoration-none">Camisetas</a></li>
                        <li><a href="<?= getCategoryUrl('moletons') ?>" class="text-light text-decoration-none">Moletons</a></li>
                        <li><a href="<?= getCategoryUrl('calcas') ?>" class="text-light text-decoration-none">Calças</a></li>
                        <li><a href="<?= getCategoryUrl('acessorios') ?>" class="text-light text-decoration-none">Acessórios</a></li>
                    </ul>
                </div>

                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="fw-bold mb-3">INFORMAÇÕES</h6>
                    <ul class="list-unstyled">
                        <li><a href="<?= BASE_URL ?>/sobre" class="text-light text-decoration-none">Sobre Nós</a></li>
                        <li><a href="<?= BASE_URL ?>/contato" class="text-light text-decoration-none">Contato</a></li>
                        <li><a href="#" class="text-light text-decoration-none">Trocas e Devoluções</a></li>
                        <li><a href="#" class="text-light text-decoration-none">Política de Privacidade</a></li>
                    </ul>
                </div>

                <div class="col-lg-4 mb-4">
                    <h6 class="fw-bold mb-3">NEWSLETTER</h6>
                    <p class="text-light mb-3">Receba novidades e ofertas exclusivas</p>
                    <form class="newsletter-form" id="newsletterForm">
                        <div class="input-group">
                            <input type="email" class="form-control" name="email" placeholder="Seu e-mail" required>
                            <button class="btn btn-light" type="submit">ENVIAR</button>
                        </div>
                    </form>
                </div>
            </div>

            <hr class="my-4">
            <div class="text-center">
                <p class="mb-0">&copy; 2025 UrbanStreet. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= BASE_URL ?>/js/urbanstreet.js"></script>
    
    <!-- Estilos para carrinho -->
    <style>
    .cart-badge {
        transition: all 0.2s ease;
        font-size: 0.7rem;
        min-width: 18px;
        height: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .cart-btn:hover .cart-badge {
        background-color: #dc3545 !important;
        transform: scale(1.1);
    }
    
    .add-to-cart {
        transition: all 0.3s ease;
    }
    
    .add-to-cart:hover {
        transform: translateY(-2px);
    }
    
    /* Toast personalizado */
    .alert {
        border-radius: 8px;
        border: none;
    }
    
    .alert-success {
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        color: #155724;
    }
    
    .alert-danger {
        background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
        color: #721c24;
    }
    </style>
</body>

</html>