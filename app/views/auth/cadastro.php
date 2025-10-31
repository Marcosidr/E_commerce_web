<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Conta — UrbanStreet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/urbanstreet.css">
    
    <style>
    .register-page {
        background: radial-gradient(circle at top right, #111 0%, #000 70%);
        color: #fff;
        font-family: 'Poppins', sans-serif;
        min-height: 100vh;
    }
    
    .card-form {
        background: rgba(255,255,255,0.05);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 20px;
        max-width: 600px;
        margin: 0 auto;
    }
    
    .form-control {
        background: rgba(255,255,255,0.08);
        border: 1px solid rgba(255,255,255,0.2);
        color: #fff;
        border-radius: 12px;
        padding: 12px 15px;
        padding-left: 50px;
    }
    
    .form-control:focus {
        background: rgba(255,255,255,0.1);
        border-color: #e53e3e;
        color: #fff;
        box-shadow: 0 0 0 0.2rem rgba(229, 62, 62, 0.25);
    }
    
    .form-control::placeholder {
        color: rgba(255,255,255,0.6);
    }
    
    .input-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: rgba(255,255,255,0.6);
        z-index: 5;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #e53e3e, #b30000);
        border: none;
        border-radius: 12px;
        padding: 12px 24px;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(229, 62, 62, 0.3);
    }
    
    .form-check-input:checked {
        background-color: #e53e3e;
        border-color: #e53e3e;
    }
    
    .form-section {
        margin-bottom: 2rem;
    }
    
    .section-title {
        color: #e53e3e;
        font-weight: 600;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 1rem;
        border-bottom: 1px solid rgba(229, 62, 62, 0.3);
        padding-bottom: 0.5rem;
    }
    
    .row-gap {
        row-gap: 1rem;
    }
    
    .alert {
        border-radius: 12px;
        border: none;
    }
    
    .alert-danger {
        background: rgba(220, 53, 69, 0.1);
        color: #ff6b7a;
        border-left: 4px solid #dc3545;
    }
    
    .alert-success {
        background: rgba(40, 167, 69, 0.1);
        color: #6bcf7e;
        border-left: 4px solid #28a745;
    }
    </style>
</head>

<body class="register-page">
    <main class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="card-form shadow-sm p-4 p-md-5">
                    <a href="<?= BASE_URL ?>" class="btn btn-outline-light btn-sm mb-4">
                        <i class="bi bi-arrow-left"></i> Voltar à loja
                    </a>
                    
                    <div class="text-center mb-4">
                        <a class="navbar-brand logo fw-bold fs-3 d-inline-flex align-items-center gap-1 text-decoration-none" href="<?= BASE_URL ?>">
                            <span class="urban text-white">URBAN</span><span class="street text-danger">STREET</span>
                        </a>
                        <h2 class="mt-3 mb-1 text-white" style="font-size:1.8rem; font-weight:800;">Criar sua conta</h2>
                        <p class="text-muted m-0" style="font-size:.95rem;">Junte-se à nossa comunidade streetwear</p>
                    </div>

                    <!-- Mensagens de erro/sucesso -->
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger" role="alert">
                            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success" role="alert">
                            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="<?= BASE_URL ?>/cadastro/create" novalidate id="registerForm">
                        
                        <!-- Dados Pessoais -->
                        <div class="form-section">
                            <h3 class="section-title">
                                <i class="bi bi-person-fill me-2"></i>Dados Pessoais
                            </h3>
                            
                            <div class="row row-gap">
                                <div class="col-md-6">
                                    <label for="nome" class="form-label text-white">Nome *</label>
                                    <div class="position-relative">
                                        <span class="input-icon">
                                            <i class="bi bi-person"></i>
                                        </span>
                                        <input type="text" id="nome" name="nome" class="form-control" 
                                               placeholder="Seu nome" required minlength="2" maxlength="50">
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="sobrenome" class="form-label text-white">Sobrenome *</label>
                                    <div class="position-relative">
                                        <span class="input-icon">
                                            <i class="bi bi-person-plus"></i>
                                        </span>
                                        <input type="text" id="sobrenome" name="sobrenome" class="form-control" 
                                               placeholder="Seu sobrenome" required minlength="2" maxlength="50">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row row-gap mt-3">
                                <div class="col-md-6">
                                    <label for="data_nascimento" class="form-label text-white">Data de Nascimento *</label>
                                    <div class="position-relative">
                                        <span class="input-icon">
                                            <i class="bi bi-calendar"></i>
                                        </span>
                                        <input type="date" id="data_nascimento" name="data_nascimento" class="form-control" required>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="sexo" class="form-label text-white">Sexo</label>
                                    <div class="position-relative">
                                        <span class="input-icon">
                                            <i class="bi bi-gender-ambiguous"></i>
                                        </span>
                                        <select id="sexo" name="sexo" class="form-control">
                                            <option value="">Prefiro não informar</option>
                                            <option value="M">Masculino</option>
                                            <option value="F">Feminino</option>
                                            <option value="O">Outro</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Dados de Contato -->
                        <div class="form-section">
                            <h3 class="section-title">
                                <i class="bi bi-envelope-fill me-2"></i>Dados de Contato
                            </h3>
                            
                            <div class="row row-gap">
                                <div class="col-md-6">
                                    <label for="email" class="form-label text-white">E-mail *</label>
                                    <div class="position-relative">
                                        <span class="input-icon">
                                            <i class="bi bi-envelope"></i>
                                        </span>
                                        <input type="email" id="email" name="email" class="form-control" 
                                               placeholder="seu@email.com" required>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="telefone" class="form-label text-white">Telefone/WhatsApp *</label>
                                    <div class="position-relative">
                                        <span class="input-icon">
                                            <i class="bi bi-phone"></i>
                                        </span>
                                        <input type="tel" id="telefone" name="telefone" class="form-control" 
                                               placeholder="(11) 99999-9999" required 
                                               pattern="\([0-9]{2}\) [0-9]{4,5}-[0-9]{4}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Senha -->
                        <div class="form-section">
                            <h3 class="section-title">
                                <i class="bi bi-shield-lock-fill me-2"></i>Segurança
                            </h3>
                            
                            <div class="row row-gap">
                                <div class="col-md-6">
                                    <label for="password" class="form-label text-white">Senha *</label>
                                    <div class="position-relative">
                                        <span class="input-icon">
                                            <i class="bi bi-lock"></i>
                                        </span>
                                        <input type="password" id="password" name="password" class="form-control" 
                                               placeholder="Mínimo 6 caracteres" required minlength="6">
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="confirm_password" class="form-label text-white">Confirmar Senha *</label>
                                    <div class="position-relative">
                                        <span class="input-icon">
                                            <i class="bi bi-lock-fill"></i>
                                        </span>
                                        <input type="password" id="confirm_password" name="confirm_password" class="form-control" 
                                               placeholder="Repita sua senha" required minlength="6">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Preferências -->
                        <div class="form-section">
                            <h3 class="section-title">
                                <i class="bi bi-gear-fill me-2"></i>Preferências de Comunicação
                            </h3>
                            
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="newsletter" name="newsletter" value="1" checked>
                                        <label class="form-check-label text-white" for="newsletter">
                                            <strong>Newsletter por E-mail</strong>
                                            <small class="d-block text-muted">Receba ofertas exclusivas, lançamentos e novidades por e-mail</small>
                                        </label>
                                    </div>
                                    
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="sms_marketing" name="sms_marketing" value="1">
                                        <label class="form-check-label text-white" for="sms_marketing">
                                            <strong>SMS Marketing</strong>
                                            <small class="d-block text-muted">Receba promoções e alertas de estoque via SMS/WhatsApp</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Termos -->
                        <div class="form-section">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="termos" name="termos" value="1" required>
                                <label class="form-check-label text-white" for="termos">
                                    Eu li e aceito os <a href="#" class="text-danger">Termos de Uso</a> e a <a href="#" class="text-danger">Política de Privacidade</a> *
                                </label>
                            </div>
                            
                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" id="maior_idade" name="maior_idade" value="1" required>
                                <label class="form-check-label text-white" for="maior_idade">
                                    Confirmo que sou maior de 18 anos *
                                </label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-lg w-100 mb-3">
                                    <i class="bi bi-person-plus me-2"></i>Criar Minha Conta
                                </button>
                            </div>
                        </div>

                        <div class="text-center">
                            <p class="text-muted mb-0">
                                Já tem uma conta? 
                                <a href="<?= BASE_URL ?>/login" class="text-danger text-decoration-none fw-semibold">Faça login</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    // Máscara para telefone
    document.getElementById('telefone').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 0) {
            if (value.length <= 10) {
                value = value.replace(/(\d{2})(\d{4})(\d{0,4})/, '($1) $2-$3');
            } else {
                value = value.replace(/(\d{2})(\d{5})(\d{0,4})/, '($1) $2-$3');
            }
        }
        e.target.value = value;
    });
    
    // Validação de senha
    document.getElementById('registerForm').addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm_password').value;
        
        if (password !== confirmPassword) {
            e.preventDefault();
            alert('As senhas não coincidem!');
            return false;
        }
        
        // Validar idade mínima (18 anos)
        const birthDate = new Date(document.getElementById('data_nascimento').value);
        const today = new Date();
        const age = today.getFullYear() - birthDate.getFullYear();
        const monthDiff = today.getMonth() - birthDate.getMonth();
        
        if (age < 18 || (age === 18 && monthDiff < 0) || 
            (age === 18 && monthDiff === 0 && today.getDate() < birthDate.getDate())) {
            e.preventDefault();
            alert('Você deve ser maior de 18 anos para se cadastrar.');
            return false;
        }
    });
    
    // Validação em tempo real
    document.getElementById('confirm_password').addEventListener('input', function() {
        const password = document.getElementById('password').value;
        const confirmPassword = this.value;
        
        if (confirmPassword && password !== confirmPassword) {
            this.setCustomValidity('As senhas não coincidem');
        } else {
            this.setCustomValidity('');
        }
    });
    </script>
</body>
</html>