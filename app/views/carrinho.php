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
                            <div class="card-body">
                                <?php foreach ($carrinho as $key => $item): ?>
                                    <div class="cart-item" data-key="<?= htmlspecialchars($key) ?>">
                                        <div class="row align-items-center g-3">
                                            <!-- Imagem do produto -->
                                            <div class="col-md-2 col-3">
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
                                            <div class="col-md-4 col-9">
                                                <h5 class="product-name mb-1"><?= htmlspecialchars($item['nome']) ?></h5>
                                                <p class="text-muted mb-1">
                                                    <small>Marca: <?= htmlspecialchars($item['marca']) ?></small>
                                                </p>
                                                <?php if ($item['tamanho']): ?>
                                                    <p class="text-muted mb-0">
                                                        <small>Tamanho: <?= htmlspecialchars($item['tamanho']) ?></small>
                                                    </p>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <!-- Preço unitário -->
                                            <div class="col-md-2 col-6">
                                                <div class="text-center">
                                                    <span class="fw-semibold">R$ <?= number_format($item['preco'], 2, ',', '.') ?></span>
                                                </div>
                                            </div>
                                            
                                            <!-- Quantidade -->
                                            <div class="col-md-2 col-6">
                                                <div class="quantity-controls d-flex align-items-center justify-content-center">
                                                    <button type="button" class="btn btn-outline-secondary btn-sm quantity-btn" data-action="decrease">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                    <input type="number" class="form-control form-control-sm quantity-input mx-2 text-center" 
                                                           value="<?= $item['quantidade'] ?>" min="1" max="99" style="width: 60px;">
                                                    <button type="button" class="btn btn-outline-secondary btn-sm quantity-btn" data-action="increase">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            
                                            <!-- Subtotal e ações -->
                                            <div class="col-md-2 col-12">
                                                <div class="d-flex flex-column align-items-end">
                                                    <span class="fw-bold subtotal mb-2">R$ <?= number_format($item['subtotal'], 2, ',', '.') ?></span>
                                                    <button type="button" class="btn btn-outline-danger btn-sm remove-item" 
                                                            title="Remover item">
                                                        <i class="fas fa-trash"></i>
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
                                
                                <div class="d-grid gap-2 mt-4">
                                    <button class="btn btn-primary btn-lg" type="button">
                                        <i class="fas fa-lock me-2"></i>Finalizar Compra
                                    </button>
                                    <button class="btn btn-outline-secondary" type="button">
                                        <i class="fas fa-calculator me-2"></i>Calcular Frete
                                    </button>
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
}

.remove-item {
    background: rgba(220, 53, 69, 0.1);
    border: 1px solid rgba(220, 53, 69, 0.3);
    color: #dc3545;
    border-radius: 12px;
    width: 40px;
    height: 40px;
    transition: all 0.3s ease;
}

.remove-item:hover {
    background: #dc3545;
    color: white;
    transform: scale(1.1);
    box-shadow: 0 5px 15px rgba(220, 53, 69, 0.4);
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
// JavaScript para funcionalidades do carrinho
document.addEventListener('DOMContentLoaded', function() {
    
    // Atualizar quantidade
    document.querySelectorAll('.quantity-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const cartItem = this.closest('.cart-item');
            const input = cartItem.querySelector('.quantity-input');
            const action = this.dataset.action;
            let currentValue = parseInt(input.value);
            
            if (action === 'increase') {
                input.value = Math.min(currentValue + 1, 99);
            } else if (action === 'decrease' && currentValue > 1) {
                input.value = Math.max(currentValue - 1, 1);
            }
            
            updateCartItem(cartItem);
        });
    });
    
    // Input de quantidade
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('change', function() {
            const cartItem = this.closest('.cart-item');
            const value = Math.max(1, Math.min(99, parseInt(this.value) || 1));
            this.value = value;
            updateCartItem(cartItem);
        });
    });
    
    // Remover item
    document.querySelectorAll('.remove-item').forEach(btn => {
        btn.addEventListener('click', function() {
            const cartItem = this.closest('.cart-item');
            removeCartItem(cartItem);
        });
    });
    
    // Limpar carrinho
    document.getElementById('limparCarrinho')?.addEventListener('click', function() {
        if (confirm('Tem certeza que deseja limpar todo o carrinho?')) {
            clearCart();
        }
    });
    
    // Funções AJAX
    function updateCartItem(cartItem) {
        const itemKey = cartItem.dataset.key;
        const quantidade = cartItem.querySelector('.quantity-input').value;
        
        fetch('<?= BASE_URL ?>/carrinho/atualizar', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `item_key=${encodeURIComponent(itemKey)}&quantidade=${quantidade}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateCartUI(data);
                updateItemSubtotal(cartItem, quantidade);
                showMessage(data.message, 'success');
            } else {
                showMessage(data.message, 'error');
            }
        })
        .catch(error => {
            showMessage('Erro ao atualizar carrinho', 'error');
        });
    }
    
    function removeCartItem(cartItem) {
        const itemKey = cartItem.dataset.key;
        
        fetch('<?= BASE_URL ?>/carrinho/remover', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `item_key=${encodeURIComponent(itemKey)}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                cartItem.style.transition = 'all 0.3s ease';
                cartItem.style.opacity = '0';
                cartItem.style.transform = 'translateX(-100%)';
                
                setTimeout(() => {
                    cartItem.remove();
                    updateCartUI(data);
                    
                    // Verificar se carrinho ficou vazio
                    if (data.totalItens === 0) {
                        location.reload();
                    }
                }, 300);
                
                showMessage(data.message, 'success');
            } else {
                showMessage(data.message, 'error');
            }
        })
        .catch(error => {
            showMessage('Erro ao remover item', 'error');
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
});
</script>