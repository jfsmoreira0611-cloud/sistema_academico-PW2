<?php
// config.php — RAIZ do projeto
// Primeiro ficheiro a ser carregado.

ob_start(); // buffer de output — previne "headers already sent"

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

try {
    $pdo = new PDO(
        "mysql:host=localhost;dbname=sistema_academico;charset=utf8mb4",
        "root",
        "",
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    ob_end_clean();
    die('<p style="font-family:sans-serif;padding:2rem;color:#9B3A3A">
        Erro de ligação à base de dados: ' . htmlspecialchars($e->getMessage()) . '
    </p>');
}