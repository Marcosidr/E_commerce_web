<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
$flash = $_SESSION['flash_message'] ?? null;
if ($flash) {
  unset($_SESSION['flash_message']);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Entrar — UrbanStreet</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

  <style>
    body {
      background: radial-gradient(circle at top right, #111 0%, #000 80%);
      font-family: "Poppins", sans-serif;
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 20px;
      color: #fff;
    }

    .card-login {
      width: 100%;
      max-width: 420px;
      padding: 40px 35px;
      background: rgba(255, 255, 255, 0.06);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 22px;
      backdrop-filter: blur(10px);
    }

    .back-btn {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      font-size: .9rem;
      padding: 8px 14px;
      border-radius: 10px;
      border: 1px solid rgba(255,255,255,0.35) !important;
      color: #fff !important;
      transition: .2s;
      text-decoration: none;
    }
    .back-btn:hover {
      background: rgba(255,255,255,0.12);
    }

    .form-control {
      background: rgba(255,255,255,0.08);
      border: 1px solid rgba(255,255,255,0.2);
      color: #fff;
      padding-left: 45px;
      padding-right: 45px;
      height: 48px;
      border-radius: 12px;
    }
    .form-control::placeholder {
      color: rgba(255,255,255,0.55);
    }
    .form-control:focus {
      background: rgba(255,255,255,0.12);
      border-color: #e53e3e;
      color: #fff;
      box-shadow: 0 0 0 0.2rem rgba(229, 62, 62, 0.25);
    }

    .input-icon {
      position: absolute !important;
      left: 15px !important;
      top: 50% !important;
      transform: translateY(-50%) !important;
      color: rgba(255, 255, 255, 0.7) !important;
      z-index: 5 !important;
      pointer-events: none !important;
      font-size: 18px !important;
      display: block !important;
    }

    .btn-login {
      background: linear-gradient(135deg, #ff3b3b, #b30000);
      border: none;
      border-radius: 12px;
      height: 48px;
      font-weight: 600;
      transition: .25s;
      color: #fff;
    }
    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(255, 60, 60, 0.35);
    }

    .toggle-password {
      position: absolute !important;
      right: 15px !important;
      top: 50% !important;
      transform: translateY(-50%) !important;
      background: none !important;
      border: none !important;
      color: rgba(255,255,255,0.7) !important;
      cursor: pointer !important;
      transition: color 0.2s;
      z-index: 10 !important;
      padding: 5px !important;
      display: flex !important;
      align-items: center !important;
      justify-content: center !important;
    }
    .toggle-password:hover {
      color: rgba(255,255,255,1) !important;
    }
    .toggle-password i {
      font-size: 18px !important;
      display: block !important;
    }

    .divider {
      margin: 24px 0;
      text-align: center;
      color: rgba(255,255,255,0.5);
      position: relative;
    }
    .divider::before,
    .divider::after {
      content: '';
      position: absolute;
      top: 50%;
      width: 40%;
      height: 1px;
      background: rgba(255,255,255,0.2);
    }
    .divider::before { left: 0; }
    .divider::after { right: 0; }

    .form-check-input:checked {
      background-color: #e53e3e;
      border-color: #e53e3e;
    }

    .alert {
      border-radius: 12px;
      border: none;
      margin-top: 8px;
      margin-bottom: 12px;
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

<body>

  <div class="card-login shadow-lg">

    <a href="<?= BASE_URL ?>" class="back-btn mb-4">
      <i class="bi bi-arrow-left"></i> Voltar à loja
    </a>

    <div class="text-center mb-4">
      <span class="fw-bold fs-3">
        <span style="color:#fff;">URBAN</span>
        <span style="color:#e53e3e;">STREET</span>
      </span>
      <h2 class="mt-3 fw-bold">Bem-vindo de volta</h2>
      <p class="text-secondary m-0">Entre para continuar</p>
    </div>

    <?php if (!empty($flash)) : ?>
      <div class="alert alert-<?= htmlspecialchars($flash['type']) ?>">
        <?= htmlspecialchars($flash['text']) ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_URL ?>/login/verify">

      <label class="mb-1">E-mail</label>
      <div class="position-relative mb-3">
        <i class="bi bi-envelope input-icon"></i>
        <input type="email" class="form-control" name="email" required>
      </div>

      <label class="mb-1">Senha</label>
      <div class="position-relative mb-2">
        <i class="bi bi-lock input-icon"></i>
        <input type="password" class="form-control" id="senha" name="password" required>
        <button type="button" class="toggle-password" onclick="togglePassword()">
          <i class="bi bi-eye"></i>
        </button>
      </div>

      <div class="d-flex justify-content-between mb-3">
        <div class="form-check">
          <input type="checkbox" class="form-check-input" id="remember" name="remember">
          <label for="remember" class="form-check-label">Lembrar-me</label>
        </div>
        <a href="#" class="text-danger text-decoration-none">Esqueci minha senha</a>
      </div>

      <button class="btn btn-login w-100">
        <i class="bi bi-box-arrow-in-right me-1"></i> Entrar
      </button>

    </form>

    <div class="divider">ou</div>

    <p class="text-center">
      Não tem conta?
      <a href="<?= BASE_URL ?>/cadastro" class="text-danger fw-semibold text-decoration-none">Cadastrar</a>
    </p>

  </div>

<script>
  function togglePassword() {
    const field = document.getElementById("senha");
    const btn = document.querySelector(".toggle-password");
    const isPassword = field.type === "password";
    
    field.type = isPassword ? "text" : "password";
    btn.innerHTML = isPassword ? '<i class="bi bi-eye-slash"></i>' : '<i class="bi bi-eye"></i>';
  }
</script>

</body>
</html>
