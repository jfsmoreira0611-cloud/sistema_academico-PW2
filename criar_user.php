<?php
// seed_utilizadores.php
// Coloca este ficheiro na RAIZ do projeto e abre no browser UMA VEZ.
// Depois apaga-o ou comenta o código por segurança.

require __DIR__ . '/config.php';

$utilizadores = [
    [
        'email'    => 'aluno@academia.pt',
        'password' => 'aluno123',
        'nome'     => 'Maria Ferreira',
        'role'     => 'aluno',
    ],
    [
        'email'    => 'funcionario@academia.pt',
        'password' => 'func123',
        'nome'     => 'João Santos',
        'role'     => 'funcionario',
    ],
    [
        'email'    => 'gestor@academia.pt',
        'password' => 'gestor123',
        'nome'     => 'Ana Costa',
        'role'     => 'gestor',
    ],
];

$criados  = 0;
$saltados = 0;

foreach ($utilizadores as $u) {
    // Verifica se já existe
    $chk = $pdo->prepare("SELECT id FROM utilizadores WHERE email = ?");
    $chk->execute([$u['email']]);
    if ($chk->fetch()) {
        $saltados++;
        continue;
    }

    $stmt = $pdo->prepare("
        INSERT INTO utilizadores (email, password_hash, nome, role)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->execute([
        $u['email'],
        password_hash($u['password'], PASSWORD_DEFAULT),
        $u['nome'],
        $u['role'],
    ]);
    $criados++;
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Seed Utilizadores</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'DM Sans', sans-serif; background: #F7F5F0; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; }
        .box { background: #fff; border: 1px solid #D4CFC5; border-radius: 12px; padding: 2.5rem; max-width: 520px; width: 90%; }
        h2 { font-size: 1.3rem; margin-bottom: 1.5rem; color: #1A1A18; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 1.5rem; font-size: .88rem; }
        th { text-align: left; font-size: .7rem; text-transform: uppercase; letter-spacing: .08em; color: #6B6860; padding: .5rem .75rem; border-bottom: 1px solid #E8E4DC; }
        td { padding: .6rem .75rem; border-bottom: 1px solid #F0EDE8; }
        .badge { display: inline-block; padding: .2rem .6rem; border-radius: 20px; font-size: .7rem; font-weight: 500; }
        .green { background: #EFF7F2; color: #2D5A3D; }
        .blue  { background: #EFF4FD; color: #2A4A7A; }
        .brown { background: #F7F0EC; color: #7A4A2A; }
        .note { font-size: .82rem; color: #9B3A3A; background: #FDF0F0; border: 1px solid #E8C8C8; border-radius: 7px; padding: .85rem 1rem; margin-bottom: 1rem; }
        .btn { display: inline-block; padding: .7rem 1.4rem; background: #2D5A3D; color: #fff; border-radius: 7px; text-decoration: none; font-size: .88rem; font-weight: 500; }
        .stats { font-size: .83rem; color: #6B6860; margin-bottom: 1.2rem; }
    </style>
</head>
<body>
<div class="box">
    <h2>✅ Utilizadores de teste criados</h2>

    <div class="stats">
        <?= $criados ?> criado(s) · <?= $saltados ?> já existia(m)
    </div>

    <table>
        <thead>
            <tr><th>Nome</th><th>Email</th><th>Password</th><th>Perfil</th></tr>
        </thead>
        <tbody>
            <tr>
                <td>Maria Ferreira</td>
                <td>aluno@academia.pt</td>
                <td><code>aluno123</code></td>
                <td><span class="badge green">Aluno</span></td>
            </tr>
            <tr>
                <td>João Santos</td>
                <td>funcionario@academia.pt</td>
                <td><code>func123</code></td>
                <td><span class="badge blue">Funcionário</span></td>
            </tr>
            <tr>
                <td>Ana Costa</td>
                <td>gestor@academia.pt</td>
                <td><code>gestor123</code></td>
                <td><span class="badge brown">Gestor</span></td>
            </tr>
        </tbody>
    </table>

    <div class="note">
        ⚠️ <strong>Apaga este ficheiro</strong> depois de o executar — não o deixes acessível em produção.
    </div>

    <a href="index.php?url=login" class="btn">Ir para o Login →</a>
</div>
</body>
</html>