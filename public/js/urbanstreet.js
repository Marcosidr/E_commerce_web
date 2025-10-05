// 🏷️ URBANSTREET - JavaScript Personalizado

// Quando o DOM for completamente carregado, inicializa a aplicação
document.addEventListener('DOMContentLoaded', function() {
    initializeApp();
});

function initializeApp() {
    // Inicializar todos os componentes da página
    initNavbar();        // Menu de navegação
    initProductCards();  // Cartões de produtos
    initNewsletter();    // Newsletter
    initCart();          // Carrinho
    initSearch();        // Busca
    initAnimations();    // Animações de entrada
    
    console.log('URBANSTREET App inicializado');
}

// =============================
// NAVBAR (menu de navegação)
// =============================
function initNavbar() {
    const navbar = document.querySelector('.navbar');
    
    // Adiciona efeito quando o usuário rola a página
    window.addEventListener('scroll', function() {
        if (window.scrollY > 100) {
            navbar.classList.add('scrolled'); // Adiciona classe ao rolar
        } else {
            navbar.classList.remove('scrolled'); // Remove ao voltar ao topo
        }
    });
}

// =============================
// PRODUTOS (cartões e botões)
// =============================
function initProductCards() {
    const productCards = document.querySelectorAll('.product-card');
    
    productCards.forEach(card => {
        // Efeito de hover (passar o mouse)
        card.addEventListener('mouseenter', function() {
            this.classList.add('hovered');
        });
        
        card.addEventListener('mouseleave', function() {
            this.classList.remove('hovered');
        });
    });
    
    // Botões "Adicionar ao carrinho"
    const addToCartButtons = document.querySelectorAll('.add-to-cart');
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const productId = this.dataset.productId;
            addToCart(productId, this);
        });
    });
}

// =============================
// NEWSLETTER (formulário de e-mail)
// =============================
function initNewsletter() {
    const forms = document.querySelectorAll('.newsletter-form, .newsletter-form-home, #newsletterForm, #newsletterFormHome');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            handleNewsletterSubmit(this);
        });
    });
}

// Envia o formulário de newsletter
function handleNewsletterSubmit(form) {
    const email = form.querySelector('input[name="email"]').value;
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    
    // Validação básica do e-mail
    if (!email || !isValidEmail(email)) {
        showNotification('Por favor, insira um e-mail válido', 'error');
        return;
    }
    
    // Estado de carregamento
    submitBtn.innerHTML = '<span class="spinner"></span> ENVIANDO...';
    submitBtn.disabled = true;
    
    // Simulação de envio (substituir depois por integração real com backend)
    setTimeout(() => {
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
        form.reset();
        showNotification('Obrigado! Você receberá nossas novidades em primeira mão.', 'success');
    }, 1500);
}

// =============================
// CARRINHO
// =============================
function initCart() {
    // Carrega contador salvo no localStorage
    updateCartCount(getCartItemCount());
}

// Adiciona um produto ao carrinho
function addToCart(productId, button) {
    const originalContent = button.innerHTML;
    
    // Estado de carregamento no botão
    button.innerHTML = '<span class="spinner"></span>';
    button.disabled = true;
    
    // Simula a adição ao carrinho
    setTimeout(() => {
        button.innerHTML = originalContent;
        button.disabled = false;
        
        const currentCount = getCartItemCount();
        const newCount = currentCount + 1;
        localStorage.setItem('cart_count', newCount);
        updateCartCount(newCount);
        
        showNotification('Produto adicionado ao carrinho!', 'success');
        animateCartIcon();
    }, 800);
}

// Atualiza o contador do carrinho no topo
function updateCartCount(count) {
    const cartBadges = document.querySelectorAll('.badge');
    
    cartBadges.forEach(badge => {
        if (badge.closest('.btn')) {
            badge.textContent = count;
        }
    });
}

// Retorna o total de itens do carrinho
function getCartItemCount() {
    return parseInt(localStorage.getItem('cart_count') || '0');
}

// Anima o ícone do carrinho quando um produto é adicionado
function animateCartIcon() {
    const cartButton = document.querySelector('.btn .fas.fa-shopping-cart');
    if (cartButton) {
        cartButton.classList.add('animate__animated', 'animate__bounceIn');
        setTimeout(() => {
            cartButton.classList.remove('animate__animated', 'animate__bounceIn');
        }, 1000);
    }
}

// =============================
// BUSCA
// =============================
function initSearch() {
    const searchForms = document.querySelectorAll('form[action*="buscar"]');
    
    searchForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const searchInput = this.querySelector('input[name="q"]');
            const query = searchInput.value.trim();
            
            if (!query) {
                e.preventDefault();
                showNotification('Digite algo para buscar', 'warning');
                searchInput.focus();
            }
        });
    });
}

// =============================
// ANIMAÇÕES (entrada dos elementos)
// =============================
function initAnimations() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in-up');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);
    
    // Seleciona os elementos que terão animação ao aparecer na tela
    const elementsToAnimate = document.querySelectorAll('.product-card, .category-card, .hero-content');
    elementsToAnimate.forEach(el => observer.observe(el));
}

// =============================
// FUNÇÕES AUXILIARES
// =============================

// Validação de e-mail
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Exibe uma notificação na tela (alerta Bootstrap)
function showNotification(message, type = 'info') {
    // Remove notificações anteriores
    const existing = document.querySelectorAll('.notification');
    existing.forEach(n => n.remove());
    
    // Cria o elemento da notificação
    const notification = document.createElement('div');
    notification.className = `notification alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
    notification.style.cssText = `
        top: 20px;
        right: 20px;
        z-index: 1060;
        min-width: 300px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    `;
    
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    // Remove automaticamente após 5 segundos
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}

// Formata valores em reais (R$)
function formatCurrency(amount) {
    return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL'
    }).format(amount);
}

// =============================
// SCROLL SUAVE (ancoras)
// =============================
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// =============================
// LAZY LOADING (carregamento de imagens)
// =============================
if ('IntersectionObserver' in window) {
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                if (img.dataset.src) {
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                    imageObserver.unobserve(img);
                }
            }
        });
    });
    
    document.querySelectorAll('img[data-src]').forEach(img => {
        imageObserver.observe(img);
    });
}

// =============================
// LOG NO CONSOLE
// =============================
console.log(
    '%c🏷️ URBANSTREET %c- Estilo Urbano Autêntico', 
    'background: #e53e3e; color: white; padding: 4px 8px; border-radius: 4px; font-weight: bold;',
    'color: #666; font-style: italic;'
);
