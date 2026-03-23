<?php
// router.php — $pdo e $_SESSION já disponíveis via config.php

$url = $_GET['url'] ?? 'login';
$base = __DIR__ . '/app/controllers/';

function auth() {
    if (!isset($_SESSION['user'])) {
        header("Location: index.php?url=login");
        exit;
    }
}

function authRole(string ...$roles) {
    auth();
    if (!in_array($_SESSION['user']['role'], $roles)) {
        header("Location: index.php?url=dashboard");
        exit;
    }
}

switch ($url) {

    case 'login':
        require $GLOBALS['base'] . 'AuthController.php';
        break;

    case 'logout':
        session_unset();
        session_destroy();
        header("Location: index.php?url=login");
        exit;

    case 'dashboard':
        auth();
        require $GLOBALS['base'] . 'DashboardController.php';
        break;

    case 'ficha':
        authRole('aluno');
        require $GLOBALS['base'] . 'FichaController.php';
        break;

    case 'matricula':
        authRole('aluno');
        require $GLOBALS['base'] . 'MatriculaController.php';
        break;

    case 'pedidos':
        authRole('funcionario');
        require $GLOBALS['base'] . 'PedidoController.php';
        break;

    case 'pautas':
        authRole('funcionario');
        require $GLOBALS['base'] . 'PautaController.php';
        break;

    case 'cursos':
        authRole('gestor');
        require $GLOBALS['base'] . 'CursoController.php';
        break;

    case 'ucs':
        authRole('gestor');
        require $GLOBALS['base'] . 'UCController.php';
        break;

    case 'planos':
        authRole('gestor');
        require $GLOBALS['base'] . 'PlanoController.php';
        break;

    case 'fichas_validar':
        authRole('gestor');
        require $GLOBALS['base'] . 'FichaValidarController.php';
        break;

    default:
        header("Location: index.php?url=login");
        exit;
}