<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$user = $_SESSION['users'] ?? null;
$userName = $user['nome'] ?? 'Cliente';
$userEmail = $user['email'] ?? '';
?>
<section class="checkout-section py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12 d-flex flex-wrap align-items-center justify-content-between">
                <div>
                    <p class="text-uppercase text-muted mb-1">URBANSTREET checkout</p>
                    <h1 class="fw-bold mb-0">Finalizar pedido</h1>
                </div>
                <div class="steps d-flex gap-3">
                    <span class="step active">1. Identificação</span>
                    <span class="step">2. Entrega</span>
                    <span class="step">3. Pagamento</span>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="checkout-card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Seus dados</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-2 fw-semibold text-white">Olá, <?= htmlspecialchars($userName) ?>!</p>
                        <p class="text-muted mb-0">Continuaremos usando o e-mail <strong><?= htmlspecialchars($userEmail) ?></strong> para as atualizações do pedido.</p>
                    </div>
                </div>

                <div class="checkout-card mt-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Itens do pedido</h5>
                        <small class="text-muted"><?= count($carrinho) ?> produto(s)</small>
                    </div>
                    <div class="card-body">
                        <?php foreach ($carrinho as $item): ?>
                            <?php
                                $sizeLabel = '';
                                if (!empty($item['tamanho'])) {
                                    $sizeLabel = ' · Tamanho ' . htmlspecialchars($item['tamanho']);
                                }
                            ?>
                            <div class="checkout-item d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="mb-0 text-white fw-semibold"><?= htmlspecialchars($item['nome']) ?></p>
                                    <small class="text-muted">Qtd: <?= (int)$item['quantidade'] ?> <?= $sizeLabel ?></small>
                                </div>
                                <div class="text-end">
                                    <p class="mb-0 text-white fw-bold">R$ <?= number_format($item['subtotal'], 2, ',', '.') ?></p>
                                    <small class="text-muted">R$ <?= number_format($item['preco'], 2, ',', '.') ?> cada</small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="checkout-summary">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Resumo</h5>
                    </div>
                    <div class="card-body">
                        <div class="summary-line">
                            <span>Subtotal</span>
                            <span>R$ <?= number_format($total, 2, ',', '.') ?></span>
                        </div>
                        <div class="summary-line">
                            <span>Frete</span>
                            <span class="text-success">Grátis</span>
                        </div>
                        <hr>
                        <div class="summary-total">
                            <span>Total</span>
                            <span>R$ <?= number_format($total, 2, ',', '.') ?></span>
                        </div>
                        <p class="text-muted small mt-2">O pagamento só será confirmado na próxima etapa.</p>
                        <div class="d-grid gap-2 mt-4">
                            <button class="btn btn-primary btn-lg" type="button" disabled>
                                Escolher forma de pagamento
                            </button>
                            <a href="<?= BASE_URL ?>/carrinho" class="btn btn-outline-light">Voltar ao carrinho</a>
                        </div>
                        <small class="text-muted d-block mt-3">Etapas de entrega e pagamento serão habilitadas quando os métodos estiverem configurados.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.checkout-section{background:linear-gradient(135deg,#09090b 0%,#161925 100%);color:#fff;min-height:calc(100vh - 140px);} 
.checkout-card,.checkout-summary{background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.08);border-radius:20px;box-shadow:0 20px 40px rgba(0,0,0,.25);} 
.checkout-card .card-header,.checkout-summary .card-header{padding:20px 24px;border-bottom:1px solid rgba(255,255,255,0.05);} 
.checkout-card .card-body,.checkout-summary .card-body{padding:24px;} 
.checkout-item+ .checkout-item{border-top:1px solid rgba(255,255,255,0.06);padding-top:16px;margin-top:16px;} 
.steps .step{font-weight:600;color:rgba(255,255,255,.4);} 
.steps .step.active{color:#fff;} 
.summary-line{display:flex;justify-content:space-between;margin-bottom:8px;color:rgba(255,255,255,.85);} 
.summary-total{display:flex;justify-content:space-between;font-size:1.2rem;font-weight:700;} 
</style>
