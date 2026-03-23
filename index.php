<?php
require __DIR__ . '/config.php'; // sessão + BD
 
// Se há ?url= na query, o router trata tudo e sai
if (isset($_GET['url'])) {
    require __DIR__ . '/router.php';
    exit;
}
 
// Se já está logado e acede à homepage, vai ao dashboard
if (isset($_SESSION['user'])) {
    header("Location: index.php?url=dashboard");
    exit;
}
 
// Sem ?url e sem sessão → mostra a página inicial (HTML abaixo)
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Académico</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --cream: #F7F5F0; --ink: #1A1A18; --muted: #6B6860;
            --accent: #2D5A3D; --accent-h: #3D7A52;
            --stone: #E8E4DC; --border: #D4CFC5;
        }
        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--cream);
            min-height: 100vh;
            display: flex; flex-direction: column;
        }
        /* NAV */
        nav {
            padding: 1.5rem 4rem;
            display: flex; align-items: center; justify-content: space-between;
            border-bottom: 1px solid var(--border);
        }
        .logo {
            display: flex; align-items: center; gap: 0.75rem;
            text-decoration: none;
        }
        .logo-icon {
            width: 34px; height: 34px; background: var(--ink);
            border-radius: 8px; display: flex; align-items: center; justify-content: center;
        }
        .logo-icon svg { width: 18px; height: 18px; fill: #fff; }
        .logo-text {
            font-family: 'Playfair Display', serif;
            font-size: 1rem; color: var(--ink);
        }
        .nav-btn {
            padding: 0.55rem 1.4rem;
            background: var(--ink); color: #fff;
            border: none; border-radius: 7px;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.85rem; font-weight: 500;
            text-decoration: none; cursor: pointer;
            transition: background 0.2s;
        }
        .nav-btn:hover { background: #333; }

        /* HERO */
        .hero {
            flex: 1; display: flex; align-items: center; justify-content: center;
            text-align: center; padding: 5rem 2rem;
            position: relative;
        }
        .hero::before {
            content: '';
            position: absolute; top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            width: 600px; height: 600px;
            border-radius: 50%;
            border: 1px solid var(--border);
            opacity: 0.5; pointer-events: none;
        }
        .hero::after {
            content: '';
            position: absolute; top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            width: 380px; height: 380px;
            border-radius: 50%;
            border: 1px solid var(--border);
            opacity: 0.4; pointer-events: none;
        }
        .hero-inner { position: relative; z-index: 1; max-width: 700px; }
        .hero-tag {
            display: inline-block;
            font-size: 0.72rem; letter-spacing: 0.12em;
            text-transform: uppercase; color: var(--accent);
            font-weight: 500; margin-bottom: 1.5rem;
            padding: 0.4rem 1rem;
            border: 1px solid rgba(45,90,61,0.3);
            border-radius: 20px;
        }
        .hero h1 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2.8rem, 6vw, 4.5rem);
            font-weight: 400; color: var(--ink);
            line-height: 1.1; margin-bottom: 1.5rem;
        }
        .hero h1 em { font-style: italic; color: var(--muted); }
        .hero p {
            font-size: 1rem; color: var(--muted);
            line-height: 1.7; max-width: 480px;
            margin: 0 auto 2.5rem; font-weight: 300;
        }
        .hero-btns { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; }
        .btn-primary-hero {
            padding: 0.85rem 2rem;
            background: var(--accent); color: #fff;
            border: none; border-radius: 8px;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.9rem; font-weight: 500;
            text-decoration: none; cursor: pointer;
            transition: background 0.2s, box-shadow 0.2s;
            display: flex; align-items: center; gap: 0.5rem;
        }
        .btn-primary-hero:hover {
            background: var(--accent-h);
            box-shadow: 0 4px 20px rgba(45,90,61,0.25);
        }
        .btn-secondary-hero {
            padding: 0.85rem 2rem;
            background: transparent; color: var(--ink);
            border: 1px solid var(--border); border-radius: 8px;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.9rem; font-weight: 500;
            text-decoration: none; cursor: pointer;
            transition: border-color 0.2s, background 0.2s;
        }
        .btn-secondary-hero:hover { background: var(--stone); border-color: var(--ink); }

        /* ROLES */
        .roles-section {
            padding: 3rem 4rem; border-top: 1px solid var(--border);
        }
        .roles-label {
            font-size: 0.72rem; letter-spacing: 0.1em;
            text-transform: uppercase; color: var(--muted);
            margin-bottom: 1.5rem; display: block;
        }
        .roles-grid { display: flex; gap: 1rem; flex-wrap: wrap; }
        .role-card {
            flex: 1; min-width: 200px;
            padding: 1.5rem;
            border: 1px solid var(--border);
            border-radius: 10px; background: #fff;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .role-card:hover { border-color: var(--accent); box-shadow: 0 4px 16px rgba(0,0,0,0.06); }
        .role-card-dot {
            width: 8px; height: 8px; border-radius: 50%;
            margin-bottom: 0.9rem;
        }
        .dot-aluno { background: #4A7C59; }
        .dot-func  { background: #4A6B8A; }
        .dot-gestor{ background: #8A5A4A; }
        .role-card h4 {
            font-family: 'Playfair Display', serif;
            font-size: 1rem; font-weight: 400;
            color: var(--ink); margin-bottom: 0.4rem;
        }
        .role-card p { font-size: 0.82rem; color: var(--muted); line-height: 1.5; }

        @media (max-width: 768px) {
            nav { padding: 1.2rem 1.5rem; }
            .roles-section { padding: 2rem 1.5rem; }
            .hero::before, .hero::after { display: none; }
        }
    </style>
</head>
<body>
<nav>
    <a href="index.php" class="logo">
        <div class="logo-icon">
            <svg viewBox="0 0 24 24"><path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3zM5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82z"/></svg>
        </div>
        <span class="logo-text">Sistema Académico</span>
    </a>
    <a href="?url=login" class="nav-btn">Entrar</a>
</nav>

<section class="hero">
    <div class="hero-inner">
        <span class="hero-tag">Plataforma Académica</span>
        <h1>Gestão académica<br><em>simplificada.</em></h1>
        <p>Um sistema integrado para alunos, funcionários e gestores pedagógicos — matrículas, fichas, pautas e muito mais.</p>
        <div class="hero-btns">
            <a href="?url=login" class="btn-primary-hero">
                Iniciar sessão
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
                </svg>
            </a>
            <a href="?url=login" class="btn-secondary-hero">Criar conta</a>
        </div>
    </div>
</section>

<section class="roles-section">
    <span class="roles-label">Perfis de acesso</span>
    <div class="roles-grid">
        <div class="role-card">
            <div class="role-card-dot dot-aluno"></div>
            <h4>Aluno</h4>
            <p>Preenche ficha pessoal, submete pedidos de matrícula e acompanha o estado das suas inscrições.</p>
        </div>
        <div class="role-card">
            <div class="role-card-dot dot-func"></div>
            <h4>Funcionário Académico</h4>
            <p>Valida pedidos de matrícula, gere pautas de avaliação e regista notas finais.</p>
        </div>
        <div class="role-card">
            <div class="role-card-dot dot-gestor"></div>
            <h4>Gestor Pedagógico</h4>
            <p>Cria cursos, unidades curriculares, planos de estudo e valida fichas dos alunos.</p>
        </div>
    </div>
</section>
</body>
</html>