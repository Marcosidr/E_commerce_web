

<section class="py-5">
  <div class="container">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
      <?php 
        $pageTitle = 'Catálogo Completo';
        $selectedCategoryName = '';
        $searchTerm = $filters['search'] ?? '';
        
        // Prioridade: busca > categoria > catálogo geral
        if (!empty($searchTerm)) {
          $pageTitle = 'Busca: "' . htmlspecialchars($searchTerm) . '"';
        } elseif (!empty($filters['category'])) {
          foreach ($categories as $cat) {
            if ($cat['id'] == $filters['category']) {
              $selectedCategoryName = $cat['name'];
              $pageTitle = $selectedCategoryName;
              break;
            }
          }
        }
      ?>
      <div>
        <h1 class="m-0 fw-black text-uppercase"><?= $pageTitle ?></h1>
        <?php if ($selectedCategoryName || !empty($searchTerm)): ?>
          <div class="mt-1">
            <a href="<?= BASE_URL ?>/catalogo" class="text-muted text-decoration-none small">
              <i class="bi bi-arrow-left"></i> Voltar ao catálogo completo
            </a>
          </div>
        <?php endif; ?>
      </div>
      <div class="d-flex align-items-center gap-2 flex-wrap">
        <!-- Ordenação -->
        <form class="d-flex align-items-center gap-2" method="get" action="<?= BASE_URL ?>/catalogo">
          <?php if (!empty($filters['search'])): ?>
            <input type="hidden" name="q" value="<?= htmlspecialchars($filters['search']) ?>">
          <?php endif; ?>
          <?php if (!empty($filters['category'])): ?>
            <input type="hidden" name="category" value="<?= $filters['category'] ?>">
          <?php endif; ?>
          <select name="sort" class="form-select form-select-sm w-auto">
            <?php $sort = $filters['sort'] ?? 'recent'; ?>
            <option value="recent" <?= $sort==='recent'?'selected':''; ?>>Mais recentes</option>
            <option value="price_asc" <?= $sort==='price_asc'?'selected':''; ?>>Menor preço</option>
            <option value="price_desc" <?= $sort==='price_desc'?'selected':''; ?>>Maior preço</option>
          </select>
          <button class="btn btn-primary btn-sm" type="submit">Aplicar</button>
        </form>
      </div>
    </div>

    <div class="row g-4">
      <!-- Filtros -->
      <aside class="col-12 col-md-4 col-lg-3">
        <form class="bg-dark text-white p-3 rounded" method="get" action="<?= BASE_URL ?>/catalogo">
          <h2 class="h5 fw-bold text-uppercase mb-3">Filtros</h2>
          <input type="hidden" name="sort" value="<?= htmlspecialchars($filters['sort'] ?? 'recent') ?>">
          <?php if (!empty($filters['search'])): ?>
            <input type="hidden" name="q" value="<?= htmlspecialchars($filters['search']) ?>">
          <?php endif; ?>

          <div class="mb-3">
            <label class="form-label small text-uppercase">Categoria</label>
            <div class="d-flex flex-column gap-2">
              <?php foreach ($categories as $cat): ?>
                <label class="d-flex align-items-center gap-2">
                  <input type="radio" name="category" value="<?= $cat['id'] ?>" class="form-check-input" <?= ($filters['category'] ?? '') == $cat['id'] ? 'checked' : '' ?>>
                  <span><?= htmlspecialchars($cat['name']) ?></span>
                </label>
              <?php endforeach; ?>
              <label class="d-flex align-items-center gap-2">
                <input type="radio" name="category" value="" class="form-check-input" <?= empty($filters['category']) ? 'checked' : '' ?>>
                <span>Todas</span>
              </label>
            </div>
          </div>

          <?php if (!empty($brands)): ?>
          <div class="mb-3">
            <label class="form-label small text-uppercase">Marca</label>
            <div class="d-flex flex-column gap-2" style="max-height: 220px; overflow:auto;">
              <?php foreach ($brands as $brand): ?>
                <label class="d-flex align-items-center gap-2">
                  <input type="radio" name="brand" value="<?= htmlspecialchars($brand) ?>" class="form-check-input" <?= ($filters['brand'] ?? '') === $brand ? 'checked' : '' ?>>
                  <span><?= htmlspecialchars($brand) ?></span>
                </label>
              <?php endforeach; ?>
              <label class="d-flex align-items-center gap-2">
                <input type="radio" name="brand" value="" class="form-check-input" <?= empty($filters['brand']) ? 'checked' : '' ?>>
                <span>Todas</span>
              </label>
            </div>
          </div>
          <?php endif; ?>

          <div class="mb-3">
            <label class="form-label small text-uppercase">Preço (R$)</label>
            <div class="d-flex align-items-center gap-2">
              <input type="number" step="0.01" name="min" class="form-control form-control-sm" placeholder="Mín" value="<?= htmlspecialchars($filters['price_min'] ?? '') ?>">
              <span>-</span>
              <input type="number" step="0.01" name="max" class="form-control form-control-sm" placeholder="Máx" value="<?= htmlspecialchars($filters['price_max'] ?? '') ?>">
            </div>
          </div>

          <div class="d-flex gap-2">
            <button class="btn btn-primary w-100" type="submit">Aplicar</button>
            <a class="btn btn-outline-light w-100" href="<?= BASE_URL ?>/catalogo">Limpar</a>
          </div>
        </form>
      </aside>

      <!-- Grid de produtos -->
      <div class="col-12 col-md-8 col-lg-9">
        <?php if (empty($products)): ?>
          <div class="text-center py-5">
            <h4 class="mb-3">Nenhum produto encontrado</h4>
            <a class="btn btn-outline-light" href="<?= BASE_URL ?>/catalogo">Limpar filtros</a>
          </div>
        <?php else: ?>
          <p class="text-muted mb-3 small">
            <?= count($products) ?> <?= count($products) === 1 ? 'produto' : 'produtos' ?> encontrados
          </p>
          <div class="row g-4">
            <?php foreach ($products as $product): ?>
              <div class="col-12 col-sm-6 col-lg-4">
                <div class="product-card h-100">
                  <div class="product-image position-relative">
                    <img src="<?= getProductImageUrl($product['id']) ?>" alt="<?= sanitizeString($product['name']) ?>" class="img-fluid">
                    <div class="product-badge">
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
                    <h5 class="product-title mb-2"><?= sanitizeString($product['name']) ?></h5>
                    <p class="product-price h5 text-primary mb-0"><?= formatPrice($product['price']) ?></p>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<script>
// JavaScript para adicionar produtos ao carrinho
document.addEventListener('DOMContentLoaded', function() {
    // Botões de adicionar ao carrinho
    document.querySelectorAll('.add-to-cart').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const productId = this.dataset.productId;
            const originalHtml = this.innerHTML;
            
            // Mostrar loading
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            this.disabled = true;
            
            // Adicionar ao carrinho
            addToCart(productId, 1).then(success => {
                if (success) {
                    // Animação de sucesso
                    this.innerHTML = '<i class="fas fa-check"></i>';
                    this.classList.remove('btn-secondary');
                    this.classList.add('btn-success');
                    
                    setTimeout(() => {
                        this.innerHTML = originalHtml;
                        this.classList.remove('btn-success');
                        this.classList.add('btn-secondary');
                        this.disabled = false;
                    }, 1500);
                } else {
                    // Voltar ao estado original
                    this.innerHTML = originalHtml;
                    this.disabled = false;
                }
            });
        });
    });
    
    // Função para adicionar ao carrinho
    async function addToCart(productId, quantidade = 1, tamanho = null) {
        try {
            const response = await fetch('<?= BASE_URL ?>/carrinho/adicionar', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `produto_id=${productId}&quantidade=${quantidade}${tamanho ? `&tamanho=${tamanho}` : ''}`
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Atualizar contador do carrinho no header
                updateCartBadge(data.totalItens);
                
                // Mostrar toast de sucesso
                showToast(data.message, 'success');
                
                return true;
            } else {
                showToast(data.message, 'error');
                return false;
            }
        } catch (error) {
            console.error('Erro ao adicionar ao carrinho:', error);
            showToast('Erro ao adicionar produto ao carrinho', 'error');
            return false;
        }
    }
    
    // Função para atualizar contador do carrinho
    function updateCartBadge(count) {
        const cartBadge = document.getElementById('cartBadge');
        if (cartBadge) {
            cartBadge.textContent = count;
            
            // Animação de pulso
            cartBadge.style.transform = 'scale(1.3)';
            setTimeout(() => {
                cartBadge.style.transform = 'scale(1)';
            }, 200);
        }
    }
    
    // Função para mostrar toast
    function showToast(message, type = 'info') {
        const alertClass = type === 'success' ? 'alert-success' : 
                          type === 'error' ? 'alert-danger' : 'alert-info';
        
        const toast = document.createElement('div');
        toast.className = `alert ${alertClass} alert-dismissible fade show position-fixed`;
        toast.style.cssText = `
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        `;
        
        toast.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(toast);
        
        // Auto remove após 4 segundos
        setTimeout(() => {
            if (toast.parentNode) {
                toast.remove();
            }
        }, 4000);
    }
});
</script>


