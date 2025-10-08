<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Entrar — UrbanStreet</title>

  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
 
  <link rel="stylesheet" href="../public/css/urbanstreet.css">

  <style>
    :root {
    --primary-color: #e53e3e;     /* Vermelho principal */
    --primary-gradient: linear-gradient(135deg, #ff4040, #b30000);
    --dark-color: #000000;        /* Fundo preto puro */
    --bg-dark: #0d0d0d;           /* Fundo sutilmente acinzentado */
    --light-color: #ffffff;
    --gray-color: #bdbdbd;
    --secondary-color: #6c757d;
    --success-color: #28a745;
    --warning-color: #ffc107;
    --danger-color: #dc3545;
    --font-primary: 'Inter', sans-serif;
    --glass: rgba(255,255,255,0.05);
    --shadow-soft: 0 8px 24px rgba(0,0,0,0.3);
    --shadow-strong: 0 20px 60px rgba(0,0,0,0.5);
}
   
    body.login-page {
      font-family: var(--font-primary);
      background-color: var(--bg-dark);
      color: var(--light-color);
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }

    .login-page .card-form {
      background-color: var(--light-color);
      color: var(--dark-color);
      padding: 2.5rem;
      border-radius: 1rem;
      box-shadow: var(--shadow-soft);
      max-width: 400px;
      width: 100%;
      position: relative;
    }

    .login-page h2 {
      color: var(--dark-color);
      font-weight: 700;
    }

    .login-page .form-label {
      color: var(--dark-color);
      font-weight: 500;
    }

    .login-page .form-control {
      border: 1px solid var(--gray-color);
      border-radius: 8px;
      padding: 10px 40px 10px 10px; /* espaço para o botão */
    }

    /* ===== BOTÕES ===== */
    .login-page .btn-primary {
      background-color: var(--primary-color) !important;
      border: none !important;
      color: var(--light-color) !important;
      font-weight: 600;
      border-radius: 8px;
      transition: 0.3s;
    }

    .login-page .btn-primary:hover {
      background: var(--primary-gradient) !important;
      box-shadow: var(--shadow-strong);
    }

    .login-page .btn-social {
      background-color: var(--light-color);
      border: 1px solid var(--gray-color);
      border-radius: 8px;
      color: var(--dark-color);
      font-weight: 500;
      transition: 0.3s;
    }

    .login-page .btn-social:hover {
      border-color: var(--primary-color);
      color: var(--primary-color);
    }

    .login-page .divider {
      display: flex;
      align-items: center;
      text-align: center;
      margin: 1.5rem 0;
      color: var(--gray-color);
    }

    .login-page .divider::before,
    .login-page .divider::after {
      content: "";
      flex: 1;
      border-bottom: 1px solid var(--gray-color);
    }

    .login-page .divider:not(:empty)::before {
      margin-right: .75em;
    }

    .login-page .divider:not(:empty)::after {
      margin-left: .75em;
    }

    .login-page a {
      color: var(--primary-color);
      text-decoration: none;
      font-weight: 500;
    }

    .login-page a:hover {
      text-decoration: underline;
    }

    /* ===== ÍCONE DE OLHO ===== */
    .toggle-password {
      position: absolute;
      right: 15px;
      top: 38px;
      cursor: pointer;
      color: var(--gray-color);
      transition: color 0.3s;
    }

    .toggle-password:hover {
      color: var(--primary-color);
    }
  </style>
</head>

<body class="login-page">
  <div class="card-form text-center">
    <h2 class="mb-4">Entre na sua conta</h2>

    <form>
      <div class="mb-3 text-start">
        <label for="email" class="form-label">E-mail</label>
        <input type="email" id="email" class="form-control" placeholder="Digite seu e-mail">
      </div>

      <div class="mb-3 text-start position-relative">
        <label for="senha" class="form-label">Senha</label>
        <input type="password" id="senha" class="form-control" placeholder="Digite sua senha">
        <span class="toggle-password" onclick="mostrarSenha()">
        👁️
        </span>
      </div>

      <div class="form-check text-start mb-3">
        <input type="checkbox" id="remember" class="form-check-input">
        <label for="remember" class="form-check-label">Lembrar-me</label>
      </div>

      <button type="submit" class="btn btn-primary w-100">Entrar</button>
    </form>

    <div class="divider">ou</div>

    <p class="mt-4 mb-0">
      Não tem uma conta? <a href="#">Cadastrar</a>
    </p>
  </div>

  <script>
    function mostrarSenha() {
      var campo = document.getElementById("senha");
      campo.type = campo.type === "password" ? "text" : "password";
    }
  </script>
</body>
</html>
