<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erro Interno - URBANSTREET</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-lg-6 text-center">
                <div class="error-content">
                    <h1 class="display-1 text-danger mb-4">500</h1>
                    <h2 class="h3 mb-4">Erro Interno do Servidor</h2>
                    <p class="text-muted mb-4">
                        Algo deu errado no nosso servidor. Nossa equipe já foi notificada e está trabalhando para resolver o problema.
                    </p>
                    <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                        <a href="<?= BASE_URL ?? '/' ?>" class="btn btn-primary btn-lg">
                            <i class="fas fa-home me-2"></i>Voltar ao Início
                        </a>
                        <button onclick="window.location.reload()" class="btn btn-outline-primary btn-lg">
                            <i class="fas fa-refresh me-2"></i>Tentar Novamente
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <style>
    .error-content .display-1 {
        font-size: 8rem;
        font-weight: 900;
        line-height: 1;
    }
    
    @media (max-width: 768px) {
        .error-content .display-1 {
            font-size: 6rem;
        }
    }
    </style>
</body>
</html>