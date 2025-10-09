<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Entrar — UrbanStreet</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/urbanstreet.css">
</head>

<body class="login-page">
  <main class="container py-5">
    <div class="row justify-content-center">
      <div class="col-11 col-sm-10 col-md-7 col-lg-5 col-xxl-4">
        <div class="card-form shadow-sm p-4 p-md-5">
          <a href="<?= BASE_URL ?>" class="btn-back"><i class="bi bi-arrow-left"></i> Voltar à loja</a>
          <div class="text-center mb-4">
            <a class="navbar-brand logo fw-bold fs-3 d-inline-flex align-items-center gap-1 text-decoration-none" href="<?= BASE_URL ?>">
              <span class="urban">URBAN</span><span class="street">STREET</span>
            </a>
           
            <h2 class="mt-3 mb-1" style="font-size:1.6rem; font-weight:800;">Bem-vindo de volta</h2>
            <p class="text-muted m-0" style="font-size:.95rem;">Entre para continuar</p>
          </div>

          <form method="POST" action="<?= BASE_URL ?>/login/verify" novalidate>
            <div class="mb-3">
              <label for="email" class="form-label">E-mail</label>
              <div class="position-relative">
                <span class="input-icon" aria-hidden="true">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M20 4H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2Zm0 4-8 5L4 8V6l8 5 8-5v2Z" fill="currentColor"/></svg>
                </span>
                <input type="email" id="email" name="email" class="form-control form-control-lg" placeholder="seu@email.com" required>
              </div>
            </div>

            <div class="mb-2">
              <label for="senha" class="form-label">Senha</label>
              <div class="position-relative">
                <span class="input-icon" aria-hidden="true">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 1a5 5 0 0 0-5 5v3H5a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1V10a1 1 0 0 0-1-1h-2V6a5 5 0 0 0-5-5Zm-3 8V6a3 3 0 1 1 6 0v3H9Z" fill="currentColor"/></svg>
                </span>
                <input type="password" id="senha" name="password" class="form-control form-control-lg" placeholder="••••••••" required>
                <button type="button" class="toggle-password" aria-label="Mostrar senha" aria-pressed="false" onclick="togglePassword(this)" title="Mostrar/ocultar senha">
                  <svg class="icon-eye" width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M12 5C7 5 2.73 8.11 1 12c1.73 3.89 6 7 11 7s9.27-3.11 11-7c-1.73-3.89-6-7-11-7Zm0 11a4 4 0 1 1 0-8 4 4 0 0 1 0 8Z" fill="currentColor"/></svg>
                  <svg class="icon-eye-off" width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" style="display:none"><path d="M2 5.27 3.28 4 20 20.72 18.73 22l-2.4-2.4A11.3 11.3 0 0 1 12 19c-5 0-9.27-3.11-11-7a12.66 12.66 0 0 1 5.06-5.59L2 5.27ZM22.94 12c-.79 1.78-2.1 3.35-3.75 4.54L17.1 15.5A6 6 0 0 0 8.5 6.9l-2-2A12.42 12.42 0 0 1 12 5c5 0 9.27 3.11 11 7Z" fill="currentColor"/></svg>
                </button>
              </div>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-3">
              <div class="form-check">
                <input type="checkbox" id="remember" name="remember" class="form-check-input">
                <label for="remember" class="form-check-label">Lembrar-me</label>
              </div>
              <a href="#" class="text-decoration-none text-link" style="font-size:.9rem;">Esqueci minha senha</a>
            </div>

            <button type="submit" class="btn btn-primary btn-lg w-100">Entrar</button>
          </form>

          <div class="divider">ou</div>

          <p class="text-center m-0" style="font-size:.95rem;">
            Não tem uma conta? <a href="#">Cadastrar</a>
          </p>
        </div>
      </div>
    </div>
  </main>

  <script>
    function togglePassword(btn){
      const input = document.getElementById('senha');
      const show = input.type === 'password';
      input.type = show ? 'text' : 'password';
      btn.setAttribute('aria-pressed', show ? 'true' : 'false');
      btn.setAttribute('aria-label', show ? 'Ocultar senha' : 'Mostrar senha');
      const eye = btn.querySelector('.icon-eye');
      const off = btn.querySelector('.icon-eye-off');
      eye.style.display = show ? 'none' : 'block';
      off.style.display = show ? 'block' : 'none';
    }
  </script>
</body>
</html>
