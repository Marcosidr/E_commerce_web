// URBANSTREET - Custom JavaScript

// Inicialização quando DOM carregado
document.addEventListener('DOMContentLoaded', function() {
    initializeApp();
});

function initializeApp() {
    // Inicializar componentes
    initNavbar();
    initProductCards();
    initNewsletter();
    initCart();
    initSearch();
    initAnimations();
    
    console.log('URBANSTREET App initialized');
}

// Navbar functionality
function initNavbar() {
    const navbar = document.querySelector('.navbar');
    
    // Navbar scroll effect
    window.addEventListener('scroll', function() {
        if (window.scrollY > 100) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });
}

// Product cards interactions
function initProductCards() {
    const productCards = document.querySelectorAll('.product-card');
    
    productCards.forEach(card => {
        // Hover effects
        card.addEventListener('mouseenter', function() {
            this.classList.add('hovered');
        });
        
        card.addEventListener('mouseleave', function() {
            this.classList.remove('hovered');
        });
    });
    
    // Add to cart buttons
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

// Newsletter functionality
function initNewsletter() {
    const forms = document.querySelectorAll('.newsletter-form, .newsletter-form-home, #newsletterForm, #newsletterFormHome');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            handleNewsletterSubmit(this);
        });
    });
}

function handleNewsletterSubmit(form) {
    const email = form.querySelector('input[name="email"]').value;
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    
    // Validação básica
    if (!email || !isValidEmail(email)) {
        showNotification('Por favor, insira um e-mail válido', 'error');
        return;
    }
    
    // Loading state
    submitBtn.innerHTML = '<span class="spinner"></span> ENVIANDO...';
    submitBtn.disabled = true;
    
    // Simular envio (substituir por chamada real à API)
    setTimeout(() => {
        // Reset button
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
        
        // Clear form
        form.reset();
        
        // Show success message
        showNotification('Obrigado! Você receberá nossas novidades em primeira mão.', 'success');
    }, 1500);
}

// Cart functionality
function initCart() {
    // Carregar contador do localStorage
    updateCartCount(getCartItemCount());
}

function addToCart(productId, button) {
    const originalContent = button.innerHTML;
    
    // Loading state
    button.innerHTML = '<span class="spinner"></span>';
    button.disabled = true;
    
    // Simular adição ao carrinho
    setTimeout(() => {
        // Reset button
        button.innerHTML = originalContent;
        button.disabled = false;
        
        // Atualizar carrinho
        const currentCount = getCartItemCount();
        const newCount = currentCount + 1;
        localStorage.setItem('cart_count', newCount);
        updateCartCount(newCount);
        
        // Mostrar notificação
        showNotification('Produto adicionado ao carrinho!', 'success');
        
        // Animar o carrinho
        animateCartIcon();
        
    }, 800);
}

function updateCartCount(count) {
    const cartBadges = document.querySelectorAll('.badge');
    const cartNotification = document.querySelector('.cart-notification span');
    
    cartBadges.forEach(badge => {
        if (badge.closest('.btn')) {
            badge.textContent = count;
        }
    });
    
    if (cartNotification) {
        cartNotification.textContent = count;
    }
}

function getCartItemCount() {
    return parseInt(localStorage.getItem('cart_count') || '0');
}

function animateCartIcon() {
    const cartButton = document.querySelector('.btn .fas.fa-shopping-cart');
    if (cartButton) {
        cartButton.classList.add('animate__animated', 'animate__bounceIn');
        setTimeout(() => {
            cartButton.classList.remove('animate__animated', 'animate__bounceIn');
        }, 1000);
    }
}

// Search functionality
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

// Animations
function initAnimations() {
    // Intersection Observer for fade-in animations
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
    
    // Observe elements
    const elementsToAnimate = document.querySelectorAll('.product-card, .category-card, .hero-content');
    elementsToAnimate.forEach(el => observer.observe(el));
}

// Utility functions
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function showNotification(message, type = 'info') {
    // Remove existing notifications
    const existing = document.querySelectorAll('.notification');
    existing.forEach(n => n.remove());
    
    // Create notification
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
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}

function formatCurrency(amount) {
    return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL'
    }).format(amount);
}

// Smooth scrolling for anchor links
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

// Lazy loading for images
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

// Console styling
console.log(
    '%c🏷️ URBANSTREET %c- Estilo Urbano Autêntico', 
    'background: #007bff; color: white; padding: 4px 8px; border-radius: 4px; font-weight: bold;',
    'color: #666; font-style: italic;'
);