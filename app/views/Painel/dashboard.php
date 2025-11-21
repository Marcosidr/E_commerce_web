<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$user = $_SESSION['users'] ?? null;
$userRole = $user['role'] ?? null;
if (!$user || $userRole !== 'admin') {
    header('Location: ' . BASE_URL . '/login');
    exit;
}

$sistemaNome = $_SESSION['sistemaNome'] ?? 'URBANSTREET';
$userName = $user['nome'] ?? 'Administrador';
$stats = [
    ['label' => 'Vendas Hoje', 'value' => 'R$ 8.540,90', 'delta' => '+18% vs ontem', 'trend' => 'up'],
    ['label' => 'Pedidos em andamento', 'value' => '32', 'delta' => '+5 novos', 'trend' => 'up'],
    ['label' => 'Ticket Médio', 'value' => 'R$ 267,00', 'delta' => 'estável', 'trend' => 'flat'],
    ['label' => 'Produtos ativos', 'value' => '148', 'delta' => '+2 publicados', 'trend' => 'up'],
];

$recentOrders = [
    ['codigo' => '#1029', 'cliente' => 'Marina Souza', 'valor' => 'R$ 389,90', 'status' => 'Pagamento aprovado'],
    ['codigo' => '#1028', 'cliente' => 'Carlos Mendes', 'valor' => 'R$ 159,00', 'status' => 'Aguardando envio'],
    ['codigo' => '#1027', 'cliente' => 'Bianca Freitas', 'valor' => 'R$ 879,90', 'status' => 'Separando estoque'],
];

$activity = [
    ['titulo' => 'Novo produto cadastrado', 'descricao' => 'Tênis Flux Runner by admin', 'tempo' => 'há 12 min'],
    ['titulo' => 'Pedido #1028 enviado', 'descricao' => 'Enviado para transportadora JadLog', 'tempo' => 'há 40 min'],
    ['titulo' => 'Estoque atualizado', 'descricao' => 'Jaqueta Tech+ recebeu 15 unidades', 'tempo' => 'há 1h 05m'],
];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title><?= $sistemaNome ?> — Painel</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/urbanstreet.css">
  <style>
    .dashboard-shell{min-height:100vh;background:var(--background,#0f1115);color:var(--text,#f6f6f6);font-family:'Poppins',sans-serif;}
    .dashboard-layout{display:flex;min-height:100vh;}
    .dashboard-sidebar{width:280px;background:#12141a;border-right:1px solid rgba(255,255,255,.08);display:flex;flex-direction:column;position:sticky;top:0;height:100vh;}
    .sidebar-header{padding:28px 28px 16px;border-bottom:1px solid rgba(255,255,255,.05);}
    .sidebar-header h2{margin:0;font-size:1.25rem;color:#fff;}
    .sidebar-user{margin-top:12px;font-size:.9rem;color:rgba(255,255,255,.65);}
    .sidebar-nav{padding:20px 0;flex:1;}
    .sidebar-nav a{display:flex;align-items:center;gap:12px;padding:14px 28px;color:rgba(255,255,255,.75);text-decoration:none;font-weight:500;transition:.2s;}
    .sidebar-nav a:hover,.sidebar-nav a.active{background:rgba(255,255,255,.08);color:#fff;}
    .logout-box{padding:24px 28px;border-top:1px solid rgba(255,255,255,.05);}
    .dashboard-main{flex:1;display:flex;flex-direction:column;}
    header.dashboard-top{display:flex;justify-content:space-between;align-items:center;padding:24px 32px;border-bottom:1px solid rgba(255,255,255,.05);background:#0f1115;position:sticky;top:0;z-index:20;gap:24px;flex-wrap:wrap;}
    .header-actions{display:flex;align-items:center;gap:16px;flex-wrap:wrap;justify-content:flex-end;flex:1;}
    .search-bar input{width:320px;max-width:100%;padding:12px 14px;border-radius:14px;border:1px solid rgba(255,255,255,.1);background:rgba(255,255,255,.04);color:#fff;}
    .store-link{display:inline-flex;align-items:center;gap:10px;padding:12px 18px;border-radius:999px;background:linear-gradient(135deg,#ff6b6b,#e11d48);color:#0b0b0b;font-weight:700;border:none;cursor:pointer;text-decoration:none;box-shadow:0 12px 30px rgba(225,29,72,.35);}
    .store-link:hover{transform:translateY(-1px);color:#050505;}
    .user-chip{display:flex;align-items:center;gap:10px;padding:10px 16px;border-radius:999px;background:rgba(255,255,255,.05);}
    main.dashboard-content{flex:1;padding:32px;background:linear-gradient(180deg,#0f1115 0%,#0b0c10 100%);}
    .cards-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:18px;margin-bottom:32px;}
    .stat-card{padding:20px;border-radius:20px;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.04);backdrop-filter:blur(20px);}
    .stat-card h3{margin:0;font-size:.78rem;text-transform:uppercase;color:rgba(255,255,255,.6);letter-spacing:.08em;}
    .stat-card p{margin:8px 0 0;font-size:2rem;font-weight:700;color:#fff;}
    .stat-delta{font-size:.85rem;margin-top:6px;color:#5be49b;}
    .stat-delta.flat{color:#f1c40f;}
    .panel-grid{display:grid;grid-template-columns:2fr 1fr;gap:24px;}
    .panel-card{border-radius:24px;background:rgba(255,255,255,.02);border:1px solid rgba(255,255,255,.05);padding:24px;backdrop-filter:blur(18px);}
    table.orders{width:100%;border-collapse:collapse;color:#fff;font-size:.92rem;}
    table.orders tr{border-bottom:1px solid rgba(255,255,255,.06);}
    table.orders td{padding:12px 0;}
    .status-pill{padding:6px 12px;border-radius:999px;font-size:.78rem;font-weight:600;background:rgba(91,228,155,.15);color:#5be49b;}
    .activity-list{display:flex;flex-direction:column;gap:16px;margin-top:12px;}
    .activity-item{padding:14px;border-radius:16px;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.04);}
    .shortcuts{display:grid;grid-template-columns:1fr;gap:12px;margin-top:12px;}
    .shortcut-btn{padding:14px;border-radius:14px;border:1px solid rgba(255,255,255,.08);background:rgba(255,255,255,.04);color:#fff;text-align:left;font-weight:600;cursor:pointer;transition:.2s;}
    .shortcut-btn:hover{background:rgba(255,255,255,.08);}
    @media(max-width:1024px){
      .dashboard-layout{flex-direction:column;}
      .dashboard-sidebar{width:100%;height:auto;position:relative;}
      .dashboard-main{margin-left:0;}
      header.dashboard-top{flex-direction:column;gap:16px;}
      .panel-grid{grid-template-columns:1fr;}
      .search-bar input{width:100%;}
    }
  </style>
</head>
<body class="dashboard-shell">
  <div class="dashboard-layout">
    <aside class="dashboard-sidebar">
      <div class="sidebar-header">
        <p style="margin:0;font-size:.85rem;color:rgba(255,255,255,.6);letter-spacing:.3em;">URBANSTREET</p>
        <h2>Painel de Operações</h2>
        <div class="sidebar-user">
          Logado como <strong><?= htmlspecialchars($userName) ?></strong><br>
          <span style="color:#5be49b;">Administrador ativo</span>
        </div>
      </div>
      <nav class="sidebar-nav">
        <a href="<?= BASE_URL ?>/dashboard" class="active">📊 Visão geral</a>
        <a href="<?= BASE_URL ?>/dashboard/pedidos">💳 Pedidos</a>
        <a href="<?= BASE_URL ?>/dashboard/produtos">🧾 Produtos</a>
        <a href="<?= BASE_URL ?>/dashboard/clientes">👥 Clientes</a>
        <a href="<?= BASE_URL ?>/dashboard/relatorios">📈 Relatórios</a>
        <a href="<?= BASE_URL ?>/configuracoes">⚙️ Configurações</a>
      </nav>
      <div class="logout-box">
        <form method="POST" action="<?= BASE_URL ?>/logout">
          <button class="button primary" style="width:100%;">Sair do painel</button>
        </form>
      </div>
    </aside>

    <div class="dashboard-main">
      <header class="dashboard-top">
        <div>
          <p style="margin:0;color:rgba(255,255,255,.6);font-size:.9rem;">Bem-vindo de volta</p>
          <h1 style="margin:4px 0 0;font-size:1.8rem;"><?= htmlspecialchars($userName) ?></h1>
        </div>
        <div class="header-actions">
          <div class="search-bar">
            <input type="search" placeholder="Buscar pedidos, clientes, produtos...">
          </div>
          <a class="store-link" href="<?= BASE_URL ?>">
            <span style="font-size:1.2rem;">⬅</span> Voltar para a loja
          </a>
          <div class="user-chip">
            <span style="width:10px;height:10px;border-radius:50%;background:#5be49b;display:inline-block;"></span>
            <span>Online</span>
          </div>
        </div>
      </header>

      <main class="dashboard-content">
        <section class="cards-grid">
          <?php foreach ($stats as $card): ?>
            <article class="stat-card">
              <h3><?= htmlspecialchars($card['label']) ?></h3>
              <p><?= htmlspecialchars($card['value']) ?></p>
              <div class="stat-delta <?= $card['trend'] === 'flat' ? 'flat' : '' ?>">
                <?= htmlspecialchars($card['delta']) ?>
              </div>
            </article>
          <?php endforeach; ?>
        </section>

        <section class="panel-grid" style="margin-bottom:28px;">
          <div class="panel-card">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
              <div>
                <h2 style="margin:0;font-size:1.2rem;">Últimos pedidos</h2>
                <p style="margin:4px 0 0;color:rgba(255,255,255,.6);font-size:.9rem;">Atualizado há 5 minutos</p>
              </div>
              <a href="<?= BASE_URL ?>/dashboard/pedidos" class="button outline sm">Ver todos</a>
            </div>
            <table class="orders">
              <?php foreach ($recentOrders as $order): ?>
                <tr>
                  <td style="width:18%;font-weight:600;"><?= htmlspecialchars($order['codigo']) ?></td>
                  <td style="width:32%;"><?= htmlspecialchars($order['cliente']) ?></td>
                  <td style="width:20%;font-weight:600;"><?= htmlspecialchars($order['valor']) ?></td>
                  <td><span class="status-pill"><?= htmlspecialchars($order['status']) ?></span></td>
                </tr>
              <?php endforeach; ?>
            </table>
          </div>

          <div class="panel-card">
            <h2 style="margin:0;font-size:1.2rem;">Atividades e atalhos</h2>
            <div class="activity-list">
              <?php foreach ($activity as $item): ?>
                <div class="activity-item">
                  <strong><?= htmlspecialchars($item['titulo']) ?></strong>
                  <p style="margin:4px 0 0;color:rgba(255,255,255,.7);font-size:.88rem;">
                    <?= htmlspecialchars($item['descricao']) ?> · <span style="color:rgba(255,255,255,.5);"><?= htmlspecialchars($item['tempo']) ?></span>
                  </p>
                </div>
              <?php endforeach; ?>
            </div>
            <div class="shortcuts">
              <button class="shortcut-btn" onclick="window.location='<?= BASE_URL ?>/dashboard/produtos';">+ Gerenciar produtos</button>
              <button class="shortcut-btn" onclick="window.location='<?= BASE_URL ?>/dashboard/relatorios';">Abrir relatórios</button>
              <button class="shortcut-btn" onclick="window.location='<?= BASE_URL ?>';">Ver vitrine</button>
            </div>
          </div>
        </section>
      </main>
    </div>
  </div>
</body>
</html>