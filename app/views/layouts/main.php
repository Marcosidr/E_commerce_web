<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'URBANSTREET - Estilo Urbano Autêntico' ?></title>
    <meta name="description" content="<?= $metaDescription ?? 'Descubra as últimas tendências em streetwear. Moda que representa sua atitude.' ?>">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?= BASE_URL ?>/public/css/urbanstreet.css" rel="stylesheet">
</head>
<body class="<?= $pageClass ?? '' ?>">
    <!-- Header -->
    <header class="navbar-dark bg-dark fixed-top">
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <!-- Logo -->
                <a class="navbar-brand fw-bold fs-3" href="<?= BASE_URL ?>">
                    URBANSTREET
                </a>
                
                <!-- Mobile toggle -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <!-- Navigation -->
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= BASE_URL ?>/catalogo">CATÁLOGO</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                CATEGORIAS
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="<?= BASE_URL ?>/categoria/tenis">Tênis</a></li>
                                <li><a class="dropdown-item" href="<?= BASE_URL ?>/categoria/camisetas">Camisetas</a></li>
                                <li><a class="dropdown-item" href="<?= BASE_URL ?>/categoria/moletons">Moletons</a></li>
                                <li><a class="dropdown-item" href="<?= BASE_URL ?>/categoria/calcas">Calças</a></li>
                                <li><a class="dropdown-item" href="<?= BASE_URL ?>/categoria/acessorios">Acessórios</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= BASE_URL ?>/sobre">SOBRE</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= BASE_URL ?>/contato">CONTATO</a>
                        </li>
                    </ul>
                    
                    <!-- Search & Cart -->
                    <div class="d-flex align-items-center gap-3">
                        <!-- Search -->
                        <form class="d-flex" method="GET" action="<?= BASE_URL ?>/buscar">
                            <div class="input-group">
                                <input class="form-control" type="search" name="q" placeholder="Buscar..." value="<?= $_GET['q'] ?? '' ?>">
                                <button class="btn btn-outline-light" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                        
                        <!-- Cart -->
                        <a href="#" class="btn btn-outline-light position-relative">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                0
                            </span>
                        </a>
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
                        <li><a href="<?= BASE_URL ?>/categoria/tenis" class="text-light text-decoration-none">Tênis</a></li>
                        <li><a href="<?= BASE_URL ?>/categoria/camisetas" class="text-light text-decoration-none">Camisetas</a></li>
                        <li><a href="<?= BASE_URL ?>/categoria/moletons" class="text-light text-decoration-none">Moletons</a></li>
                        <li><a href="<?= BASE_URL ?>/categoria/calcas" class="text-light text-decoration-none">Calças</a></li>
                        <li><a href="<?= BASE_URL ?>/categoria/acessorios" class="text-light text-decoration-none">Acessórios</a></li>
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
    <script src="<?= BASE_URL ?>/public/js/urbanstreet.js"></script>
</body>
</html>