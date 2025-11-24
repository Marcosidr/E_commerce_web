<section class="py-5 cart-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="fw-black text-uppercase mb-4">
                    <i class="fas fa-shopping-cart me-3"></i>Meu Carrinho
                </h1>
                
                <!-- Mensagens -->
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <?php if (empty($carrinho)): ?>
            <!-- Carrinho vazio -->
            <div class="row">
                <div class="col-12 text-center py-5">
                    <div class="empty-cart">
                        <i class="fas fa-shopping-cart empty-icon mb-4"></i>
                        <h3 class="text-muted mb-3">Seu carrinho está vazio</h3>
                        <p class="text-muted mb-4">Adicione produtos incríveis ao seu carrinho e finalize sua compra</p>
                        <a href="<?= BASE_URL ?>/catalogo" class="btn btn-primary btn-lg">
                            <i class="fas fa-arrow-left me-2"></i>Continuar Comprando
                        </a>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <!-- Itens do carrinho -->
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="cart-items">
                        <div class="card cart-card">
                            <div class="card-body cart-items-container">
                                <?php foreach ($carrinho as $key => $item): ?>
                                    <div class="cart-item" data-key="<?= htmlspecialchars($key) ?>">
                                        <div class="row align-items-center g-3">
                                            <!-- Imagem do produto -->
                                            <div class="col-md-2 col-4">
                                                <div class="product-image">
                                                    <?php if ($item['imagem']): ?>
                                                        <img src="<?= getProductImageUrl($item['produto_id']) ?>" 
                                                             alt="<?= htmlspecialchars($item['nome']) ?>" 
                                                             class="img-fluid rounded">
                                                    <?php else: ?>
                                                        <div class="no-image">
                                                            <i class="fas fa-image"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            
                                            <!-- Detalhes do produto -->
                                            <div class="col-md-3 col-8">
                                                <h5 class="product-name mb-1"><?= htmlspecialchars($item['nome']) ?></h5>
                                                <p class="text-muted mb-1">
                                                    <small>Marca: <?= htmlspecialchars($item['marca']) ?></small>
                                                </p>
                                                <?php if ($item['tamanho'] && $item['tamanho'] !== 'UNICO'): ?>
                                                    <p class="text-muted mb-0">
                                                        <small>Tamanho: <?= htmlspecialchars($item['tamanho']) ?></small>
                                                    </p>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <!-- Preço unitário -->
                                            <div class="col-md-2 col-6 text-center">
                                                <small class="d-block text-muted mb-1">Preço</small>
                                                <span class="fw-semibold text-white">R$ <?= number_format($item['preco'], 2, ',', '.') ?></span>
                                            </div>
                                            
                                            <!-- Quantidade -->
                                            <div class="col-md-3 col-6">
                                                <small class="d-block text-muted mb-1 text-center">Quantidade</small>
                                                <div class="quantity-controls d-flex align-items-center justify-content-center gap-1">
                                                    <button type="button" class="btn btn-outline-secondary btn-sm quantity-btn" data-action="decrease">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                    <input type="number" class="form-control form-control-sm quantity-input text-center" 
                                                           value="<?= $item['quantidade'] ?>" min="1" max="99" style="width: 60px;">
                                                    <button type="button" class="btn btn-outline-secondary btn-sm quantity-btn" data-action="increase">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            
                                            <!-- Subtotal e ações -->
                                            <div class="col-md-2 col-12">
                                                <div class="d-flex flex-column align-items-end gap-3">
                                                    <div class="text-end">
                                                        <small class="d-block text-muted mb-1">Subtotal</small>
                                                        <span class="fw-bold subtotal text-white fs-5">R$ <?= number_format($item['subtotal'], 2, ',', '.') ?></span>
                                                    </div>
                                                    <button type="button" class="btn btn-danger btn-sm remove-item" 
                                                            title="Remover item do carrinho">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <?php if ($key !== array_key_last($carrinho)): ?>
                                        <hr class="my-3">
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <!-- Ações do carrinho -->
                        <div class="cart-actions mt-4">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <a href="<?= BASE_URL ?>/catalogo" class="btn btn-outline-primary w-100">
                                        <i class="fas fa-arrow-left me-2"></i>Continuar Comprando
                                    </a>
                                </div>
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-outline-danger w-100" id="limparCarrinho">
                                        <i class="fas fa-trash me-2"></i>Limpar Carrinho
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Resumo do pedido -->
                <div class="col-lg-4">
                    <div class="order-summary sticky-top" style="top: 100px;">
                        <div class="card summary-card">
                            <div class="card-header">
                                <h5 class="mb-0 fw-bold">
                                    <i class="fas fa-receipt me-2"></i>Resumo do Pedido
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="summary-line">
                                    <span>Subtotal</span>
                                    <span class="fw-semibold" id="subtotalValue">R$ <?= number_format($total, 2, ',', '.') ?></span>
                                </div>
                                
                                <div class="summary-line">
                                    <span>Frete</span>
                                    <span class="text-success fw-semibold">GRÁTIS</span>
                                </div>
                                
                                <div class="summary-line text-muted">
                                    <small>Desconto</small>
                                    <small>-R$ 0,00</small>
                                </div>
                                
                                <hr>
                                
                                <div class="summary-total">
                                    <span class="fw-bold">Total</span>
                                    <span class="fw-bold text-primary fs-5" id="totalValue">R$ <?= number_format($total, 2, ',', '.') ?></span>
                                </div>
                                
                                <!-- Calcular Frete -->
                                <div class="frete-calculator mb-3">
                                    <label class="form-label text-white fw-semibold">
                                        <i class="fas fa-map-marker-alt me-2"></i>Calcular Frete
                                    </label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="cepInput" placeholder="Digite seu CEP" maxlength="9">
                                        <button class="btn btn-outline-secondary" type="button" id="btnCalcularFrete">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                    <div id="freteResult" class="mt-2"></div>
                                </div>

                                <div class="d-grid gap-2">
                                    <a class="btn btn-primary btn-lg" href="<?= BASE_URL ?>/checkout">
                                        <i class="fas fa-lock me-2"></i>Finalizar Compra
                                    </a>
                                </div>
                                
                                <!-- Segurança -->
                                <div class="security-badges mt-4 text-center">
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <div class="security-item">
                                                <i class="fas fa-shield-alt text-success"></i>
                                                <small class="d-block text-muted">Compra Segura</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="security-item">
                                                <i class="fas fa-truck text-primary"></i>
                                                <small class="d-block text-muted">Frete Grátis</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Cupom de desconto -->
                        <div class="card mt-3">
                            <div class="card-body">
                                <h6 class="fw-bold mb-3">
                                    <i class="fas fa-tag me-2"></i>Cupom de Desconto
                                </h6>
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Digite seu cupom">
                                    <button class="btn btn-outline-primary" type="button">Aplicar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<style>
/* === CARRINHO URBANSTREET - DESIGN PREMIUM === */

.cart-section {
    background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 50%, #0f0f0f 100%);
    min-height: calc(100vh - 140px);
    position: relative;
    overflow: hidden;
}

.cart-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: 
        radial-gradient(circle at 20% 20%, rgba(229, 62, 62, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 80% 80%, rgba(229, 62, 62, 0.05) 0%, transparent 50%);
    pointer-events: none;
}

.cart-section .container {
    position: relative;
    z-index: 1;
}

/* === HEADER DO CARRINHO === */
.cart-section h1 {
    color: #ffffff;
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: 2px;
    font-size: clamp(2rem, 5vw, 3rem);
    text-shadow: 0 0 20px rgba(229, 62, 62, 0.3);
    margin-bottom: 2rem;
}

.cart-section h1 i {
    color: #e53e3e;
    margin-right: 1rem;
    filter: drop-shadow(0 0 10px rgba(229, 62, 62, 0.5));
}

/* === CARDS PRINCIPAIS === */
.cart-card {
    background: rgba(255, 255, 255, 0.02);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 20px;
    box-shadow: 
        0 20px 40px rgba(0, 0, 0, 0.3),
        inset 0 1px 0 rgba(255, 255, 255, 0.1);
    overflow: hidden;
    position: relative;
}

.cart-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 2px;
    background: linear-gradient(90deg, transparent, #e53e3e, transparent);
}

.cart-card .card-body {
    padding: 2rem;
}

/* === ITENS DO CARRINHO === */
.cart-item {
    padding: 2rem 0;
    border-radius: 16px;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
}

.cart-item::before {
    content: '';
    position: absolute;
    left: -2rem;
    right: -2rem;
    top: 0;
    bottom: 0;
    background: rgba(229, 62, 62, 0.03);
    border-radius: 16px;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.cart-item:hover::before {
    opacity: 1;
}

.cart-item:hover {
    transform: translateY(-2px);
}

/* === IMAGENS DOS PRODUTOS === */
.product-image {
    position: relative;
    overflow: hidden;
    border-radius: 16px;
}

.product-image img {
    width: 90px;
    height: 90px;
    object-fit: cover;
    border-radius: 16px;
    transition: transform 0.3s ease;
    border: 2px solid rgba(229, 62, 62, 0.2);
}

.product-image:hover img {
    transform: scale(1.05);
}

.no-image {
    width: 90px;
    height: 90px;
    background: linear-gradient(135deg, rgba(229, 62, 62, 0.1), rgba(229, 62, 62, 0.05));
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 16px;
    color: #e53e3e;
    font-size: 2rem;
    border: 2px solid rgba(229, 62, 62, 0.3);
}

/* === INFORMAÇÕES DO PRODUTO === */
.product-name {
    color: #ffffff;
    font-weight: 600;
    font-size: 1.1rem;
    margin-bottom: 0.5rem;
}

.cart-item .text-muted {
    color: rgba(255, 255, 255, 0.6) !important;
    font-size: 0.9rem;
}

.cart-item .fw-semibold {
    color: #e53e3e;
    font-weight: 700;
    font-size: 1.1rem;
}

/* === CONTROLES DE QUANTIDADE === */
.quantity-controls {
    gap: 0.5rem;
}

.quantity-btn {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    border: 1px solid rgba(229, 62, 62, 0.3);
    background: rgba(229, 62, 62, 0.1);
    color: #e53e3e;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    font-size: 0.9rem;
}

.quantity-btn:hover {
    background: #e53e3e;
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 5px 15px rgba(229, 62, 62, 0.4);
}

.quantity-input {
    width: 70px !important;
    height: 40px;
    text-align: center;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    color: #ffffff;
    font-weight: 600;
    transition: all 0.3s ease;
}

.quantity-input:focus {
    background: rgba(255, 255, 255, 0.08);
    border-color: #e53e3e;
    box-shadow: 0 0 0 0.2rem rgba(229, 62, 62, 0.25);
    color: #ffffff;
}

/* === SUBTOTAL E AÇÕES === */
.subtotal {
    color: #e53e3e;
    font-weight: 700;
    font-size: 1.2rem;
    text-shadow: 0 0 10px rgba(229, 62, 62, 0.3);
}

.remove-item {
    background: linear-gradient(135deg, rgba(220, 53, 69, 0.15), rgba(220, 53, 69, 0.25));
    border: 1px solid rgba(220, 53, 69, 0.4);
    color: #dc3545;
    border-radius: 10px;
    width: 42px;
    height: 42px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    font-size: 1rem;
    position: relative;
    overflow: hidden;
}

.remove-item::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, #dc3545, #c82333);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.remove-item i {
    position: relative;
    z-index: 1;
    transition: transform 0.3s ease;
}

.remove-item:hover {
    border-color: #dc3545;
    transform: translateY(-2px) scale(1.05);
    box-shadow: 0 8px 20px rgba(220, 53, 69, 0.4);
}

.remove-item:hover::before {
    opacity: 1;
}

.remove-item:hover i {
    color: white;
    transform: scale(1.1) rotate(-10deg);
}

.remove-item:active {
    transform: translateY(0) scale(0.95);
}

/* === RESUMO DO PEDIDO === */
.summary-card {
    background: rgba(255, 255, 255, 0.03);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 20px;
    box-shadow: 
        0 20px 40px rgba(0, 0, 0, 0.2),
        inset 0 1px 0 rgba(255, 255, 255, 0.1);
    overflow: hidden;
}

.summary-card .card-header {
    background: rgba(229, 62, 62, 0.1);
    border-bottom: 1px solid rgba(229, 62, 62, 0.2);
    padding: 1.5rem;
}

.summary-card .card-header h5 {
    color: #ffffff;
    font-weight: 700;
    margin: 0;
}

.summary-card .card-body {
    padding: 2rem;
}

.summary-line {
    display: flex;
    justify-content: space-between;
    margin-bottom: 1rem;
    color: rgba(255, 255, 255, 0.8);
    font-size: 0.95rem;
}

.summary-line .fw-semibold {
    color: #ffffff;
    font-weight: 600;
}

.summary-total {
    display: flex;
    justify-content: space-between;
    font-size: 1.3rem;
    font-weight: 700;
    color: #ffffff;
    padding: 1rem 0;
    border-top: 2px solid rgba(229, 62, 62, 0.3);
    margin-top: 1rem;
}

.summary-total .text-primary {
    color: #e53e3e !important;
}

/* === BOTÕES === */
.btn-primary {
    background: linear-gradient(135deg, #e53e3e 0%, #c53030 100%);
    border: none;
    border-radius: 12px;
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(229, 62, 62, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(229, 62, 62, 0.4);
    background: linear-gradient(135deg, #c53030 0%, #e53e3e 100%);
}

.btn-outline-primary {
    border: 2px solid #e53e3e;
    color: #e53e3e;
    background: transparent;
    border-radius: 12px;
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-outline-primary:hover {
    background: #e53e3e;
    color: white;
    transform: translateY(-1px);
}

.btn-outline-secondary {
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: rgba(255, 255, 255, 0.8);
    background: transparent;
    border-radius: 12px;
    transition: all 0.3s ease;
}

.btn-outline-secondary:hover {
    background: rgba(255, 255, 255, 0.1);
    color: #ffffff;
    border-color: rgba(255, 255, 255, 0.5);
}

.btn-outline-danger {
    border: 2px solid #dc3545;
    color: #dc3545;
    background: transparent;
    border-radius: 12px;
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-outline-danger:hover {
    background: #dc3545;
    color: white;
    transform: translateY(-1px);
}

/* === BADGES DE SEGURANÇA === */
.security-item {
    background: rgba(229, 62, 62, 0.1);
    border: 1px solid rgba(229, 62, 62, 0.2);
    border-radius: 12px;
    padding: 1rem;
    text-align: center;
    transition: all 0.3s ease;
}

.security-item:hover {
    background: rgba(229, 62, 62, 0.15);
    transform: translateY(-2px);
}

.security-item i {
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
    display: block;
}

.security-item small {
    color: rgba(255, 255, 255, 0.8);
    font-weight: 500;
}

/* === CARRINHO VAZIO === */
.empty-cart {
    color: rgba(255, 255, 255, 0.6);
    text-align: center;
    padding: 4rem 2rem;
}

.empty-icon {
    font-size: 5rem;
    color: rgba(229, 62, 62, 0.3);
    margin-bottom: 2rem;
    filter: drop-shadow(0 0 20px rgba(229, 62, 62, 0.2));
}

.empty-cart h3 {
    color: rgba(255, 255, 255, 0.8);
    font-weight: 600;
    margin-bottom: 1rem;
}

.empty-cart p {
    color: rgba(255, 255, 255, 0.6);
    font-size: 1.1rem;
}

/* === CUPOM DE DESCONTO === */
.input-group .form-control {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: #ffffff;
    border-radius: 12px 0 0 12px;
}

.input-group .form-control:focus {
    background: rgba(255, 255, 255, 0.08);
    border-color: #e53e3e;
    color: #ffffff;
    box-shadow: none;
}

.input-group .form-control::placeholder {
    color: rgba(255, 255, 255, 0.5);
}

.input-group .btn {
    border-radius: 0 12px 12px 0;
}

/* === RESPONSIVIDADE === */
@media (max-width: 768px) {
    .cart-section h1 {
        font-size: 2rem;
        margin-bottom: 1.5rem;
    }
    
    .cart-item {
        padding: 1.5rem 0;
    }
    
    .product-image img,
    .no-image {
        width: 70px;
        height: 70px;
    }
    
    .quantity-input {
        width: 60px !important;
        font-size: 0.9rem;
    }
    
    .quantity-btn {
        width: 35px;
        height: 35px;
    }
    
    .summary-card .card-body {
        padding: 1.5rem;
    }
    
    .cart-card .card-body {
        padding: 1.5rem;
    }
}

@media (max-width: 576px) {
    .cart-section {
        padding: 2rem 0;
    }
    
    .cart-item {
        padding: 1rem 0;
    }
    
    .product-name {
        font-size: 1rem;
    }
    
    .summary-total {
        font-size: 1.1rem;
    }
}

/* === ANIMAÇÕES AVANÇADAS === */
@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.cart-item {
    animation: slideIn 0.6s ease-out;
}

.btn:active {
    transform: scale(0.95);
}

/* === SCROLL PERSONALIZADO === */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 4px;
}

::-webkit-scrollbar-thumb {
    background: rgba(229, 62, 62, 0.3);
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: rgba(229, 62, 62, 0.5);
}
</style>

<script>
// CARRINHO - Controles de quantidade e remoção (delegação simples)
document.addEventListener('DOMContentLoaded', function() {
    const container = document.querySelector('.cart-items-container');
    if (!container) return;

    container.addEventListener('click', function (e) {
        const cartItem = e.target.closest('.cart-item');
        if (!cartItem) return;

        const quantityBtn = e.target.closest('.quantity-btn');
        const removeBtn   = e.target.closest('.remove-item');

        // Botões + e -
        if (quantityBtn) {
            e.preventDefault();
            const input = cartItem.querySelector('.quantity-input');
            if (!input) return;

            let value = parseInt(input.value) || 1;
            const action = quantityBtn.dataset.action;

            if (action === 'increase' && value < 99) value++;
            if (action === 'decrease' && value > 1)  value--;

            input.value = value;
            updateCart(cartItem, value);
            return;
        }

        // Botão remover
        if (removeBtn) {
            e.preventDefault();
            if (!confirm('Deseja remover este item do carrinho?')) return;
            removeFromCart(cartItem);
            return;
        }
    });

    document.getElementById('limparCarrinho')?.addEventListener('click', function() {
        if (confirm('Tem certeza que deseja limpar todo o carrinho?')) {
            clearCart();
        }
    });

    function updateCart(cartItem, quantidade) {
        const itemKey = cartItem.getAttribute('data-key');
        fetch('<?= BASE_URL ?>/carrinho/atualizar', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `item_key=${encodeURIComponent(itemKey)}&quantidade=${quantidade}`
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                updateCartUI(data);
                updateItemSubtotal(cartItem, quantidade);
            }
        });
    }

    function removeFromCart(cartItem) {
        const itemKey = cartItem.getAttribute('data-key');
        fetch('<?= BASE_URL ?>/carrinho/remover', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `item_key=${encodeURIComponent(itemKey)}`
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                cartItem.remove();
                updateCartUI(data);
                if (data.totalItens === 0) location.reload();
            }
        });
    }

    function clearCart() {
        fetch('<?= BASE_URL ?>/carrinho/limpar', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'ajax=1'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                showMessage(data.message, 'error');
            }
        })
        .catch(error => {
            showMessage('Erro ao limpar carrinho', 'error');
        });
    }
    
    function updateCartUI(data) {
        // Atualizar contador no header (se existir)
        const cartBadge = document.querySelector('.cart-badge');
        if (cartBadge) {
            cartBadge.textContent = data.totalItens;
        }
        
        // Atualizar totais
        const subtotalElement = document.getElementById('subtotalValue');
        const totalElement = document.getElementById('totalValue');
        
        if (subtotalElement) {
            subtotalElement.textContent = 'R$ ' + data.totalCarrinho.toLocaleString('pt-BR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }
        
        if (totalElement) {
            totalElement.textContent = 'R$ ' + data.totalCarrinho.toLocaleString('pt-BR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }
    }
    
    function updateItemSubtotal(cartItem, quantidade) {
        const precoText = cartItem.querySelector('.fw-semibold').textContent;
        const preco = parseFloat(precoText.replace('R$ ', '').replace(',', '.'));
        const subtotal = preco * quantidade;
        
        const subtotalElement = cartItem.querySelector('.subtotal');
        subtotalElement.textContent = 'R$ ' + subtotal.toLocaleString('pt-BR', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }
    
    function showMessage(message, type) {
        // Criar e mostrar toast ou alert
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show position-fixed" 
                 style="top: 20px; right: 20px; z-index: 9999;">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', alertHtml);
        
        // Remover após 3 segundos
        setTimeout(() => {
            const alert = document.querySelector('.alert:last-of-type');
            if (alert) {
                alert.remove();
            }
        }, 3000);
    }

    // Calcular Frete
    const cepInput = document.getElementById('cepInput');
    const btnCalcularFrete = document.getElementById('btnCalcularFrete');
    const freteResult = document.getElementById('freteResult');

    // Máscara de CEP
    cepInput?.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 5) {
            value = value.slice(0, 5) + '-' + value.slice(5, 8);
        }
        e.target.value = value;
    });

    btnCalcularFrete?.addEventListener('click', async function() {
        const cep = cepInput.value.replace(/\D/g, '');
        
        if (cep.length !== 8) {
            freteResult.innerHTML = '<small class="text-danger">CEP inválido</small>';
            return;
        }

        freteResult.innerHTML = '<small class="text-muted">Calculando...</small>';

        try {
            const response = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
            const data = await response.json();
            
            if (data.erro) {
                freteResult.innerHTML = '<small class="text-danger">CEP não encontrado</small>';
                return;
            }

            // Simula valores de frete baseados na região
            const uf = data.uf;
            let valorFrete = 0;
            let prazo = 0;

            if (['SP', 'RJ', 'MG'].includes(uf)) {
                valorFrete = 15.90;
                prazo = 3;
            } else if (['PR', 'SC', 'RS', 'ES'].includes(uf)) {
                valorFrete = 22.90;
                prazo = 5;
            } else {
                valorFrete = 35.90;
                prazo = 7;
            }

            freteResult.innerHTML = `
                <div class="frete-info">
                    <small class="text-success d-block fw-semibold">✓ ${data.localidade} - ${uf}</small>
                    <small class="text-white d-block mt-1">Frete: <strong>R$ ${valorFrete.toFixed(2).replace('.', ',')}</strong></small>
                    <small class="text-muted d-block">Entrega em ${prazo} dias úteis</small>
                </div>
            `;
        } catch (error) {
            freteResult.innerHTML = '<small class="text-danger">Erro ao calcular frete</small>';
        }
    });
});
</script>