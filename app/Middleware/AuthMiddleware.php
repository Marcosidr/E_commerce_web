<?php

namespace App\Middleware;

class AuthMiddleware
{
    public static function requireAdmin(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $user = $_SESSION['users'] ?? null;
        $role = $user['role'] ?? null;

        if ($role !== 'admin') {
            $_SESSION['error'] = 'Acesso restrito. Faça login como administrador.';
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
    }

    public static function requireLogin(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['users'])) {
            $_SESSION['error'] = 'Faça login para continuar.';
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
    }
}