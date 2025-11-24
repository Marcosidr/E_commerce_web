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
                    <div class="card-header">
                        <h5 class="card-title mb-0">Endereço de Entrega</h5>
                    </div>
                    <div class="card-body">
                        <form id="formEndereco">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">CEP</label>
                                    <input type="text" class="form-control" id="cep" placeholder="00000-000" required>
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label">Rua</label>
                                    <input type="text" class="form-control" id="rua" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Número</label>
                                    <input type="text" class="form-control" id="numero" required>
                                </div>
                                <div class="col-md-9">
                                    <label class="form-label">Complemento</label>
                                    <input type="text" class="form-control" id="complemento">
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label">Bairro</label>
                                    <input type="text" class="form-control" id="bairro" required>
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label">Cidade</label>
                                    <input type="text" class="form-control" id="cidade" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">UF</label>
                                    <input type="text" class="form-control" id="uf" maxlength="2" required>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="checkout-card mt-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Forma de Pagamento</h5>
                    </div>
                    <div class="card-body">
                        <div class="payment-options">
                            <div class="payment-option">
                                <input type="radio" class="btn-check" name="payment" id="pix" value="pix" checked>
                                <label class="btn btn-outline-light w-100 text-start" for="pix">
                                    <i class="fas fa-qrcode me-2"></i>
                                    <strong>PIX</strong>
                                    <small class="d-block text-muted">Aprovação imediata</small>
                                </label>
                            </div>
                            <div class="payment-option">
                                <input type="radio" class="btn-check" name="payment" id="cartao" value="cartao">
                                <label class="btn btn-outline-light w-100 text-start" for="cartao">
                                    <i class="fas fa-credit-card me-2"></i>
                                    <strong>Cartão de Crédito</strong>
                                    <small class="d-block text-muted">Em até 12x sem juros</small>
                                </label>
                            </div>
                            <div class="payment-option">
                                <input type="radio" class="btn-check" name="payment" id="boleto" value="boleto">
                                <label class="btn btn-outline-light w-100 text-start" for="boleto">
                                    <i class="fas fa-barcode me-2"></i>
                                    <strong>Boleto Bancário</strong>
                                    <small class="d-block text-muted">Vencimento em 3 dias</small>
                                </label>
                            </div>
                        </div>
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
                        <p class="text-muted small mt-2">Revise os dados antes de confirmar.</p>
                        <div class="d-grid gap-2 mt-4">
                            <button class="btn btn-primary btn-lg" type="button" id="btnFinalizarPedido">
                                <i class="fas fa-check me-2"></i>Finalizar Pedido
                            </button>
                            <a href="<?= BASE_URL ?>/carrinho" class="btn btn-outline-light">Voltar ao carrinho</a>
                        </div>
                        <div class="security-badges mt-3">
                            <small class="text-success"><i class="fas fa-lock me-1"></i>Pagamento 100% seguro</small>
                        </div>
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
.form-control{background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);color:#fff;}
.form-control:focus{background:rgba(255,255,255,0.08);border-color:#e53e3e;color:#fff;}
.form-label{color:rgba(255,255,255,0.85);font-weight:500;margin-bottom:0.5rem;}
.payment-options{display:grid;gap:12px;}
.payment-option .btn-outline-light{border:2px solid rgba(255,255,255,0.15);transition:all 0.3s;}
.payment-option .btn-check:checked+.btn-outline-light{background:#e53e3e;border-color:#e53e3e;color:#fff;}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Máscara e busca CEP
    const cepInput = document.getElementById('cep');
    cepInput?.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 5) value = value.slice(0, 5) + '-' + value.slice(5, 8);
        e.target.value = value;
    });

    cepInput?.addEventListener('blur', async function() {
        const cep = this.value.replace(/\D/g, '');
        if (cep.length !== 8) return;
        
        try {
            const res = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
            const data = await res.json();
            if (!data.erro) {
                document.getElementById('rua').value = data.logradouro || '';
                document.getElementById('bairro').value = data.bairro || '';
                document.getElementById('cidade').value = data.localidade || '';
                document.getElementById('uf').value = data.uf || '';
            }
        } catch (e) { console.error(e); }
    });

    // Finalizar pedido
    document.getElementById('btnFinalizarPedido')?.addEventListener('click', function() {
        const formEndereco = document.getElementById('formEndereco');
        const payment = document.querySelector('input[name="payment"]:checked')?.value;
        
        if (!formEndereco.checkValidity()) {
            formEndereco.reportValidity();
            return;
        }
        
        if (!payment) {
            alert('Selecione uma forma de pagamento');
            return;
        }
        
        // Simulação de finalização
        alert('Pedido finalizado com sucesso!\n\nForma de pagamento: ' + payment.toUpperCase() + '\n\nEm breve você receberá as instruções por e-mail.');
        window.location.href = '<?= BASE_URL ?>';
    });
});
</script>
