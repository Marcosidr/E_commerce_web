<div class="error-page py-5">
    <div class="container text-center">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="error-content">
                    <h1 class="display-1 text-primary mb-4">404</h1>
                    <h2 class="h3 mb-4">Página Não Encontrada</h2>
                    <p class="text-muted mb-4">
                        Ops! A página que você está procurando não existe ou foi movida.
                        Que tal explorar nossa coleção?
                    </p>
                    <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                        <a href="<?= BASE_URL ?>" class="btn btn-primary btn-lg">
                            <i class="fas fa-home me-2"></i>Voltar ao Início
                        </a>
                        <a href="<?= BASE_URL ?>/catalogo" class="btn btn-outline-primary btn-lg">
                            <i class="fas fa-th-large me-2"></i>Ver Catálogo
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.error-page {
    min-height: 60vh;
    display: flex;
    align-items: center;
}

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