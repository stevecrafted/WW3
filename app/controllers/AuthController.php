<?php

declare(strict_types=1);

namespace app\controllers;

use app\models\User;

class AuthController extends BaseController
{
    public function home(): void
    {
        $this->render('Home', [
            'title' => 'IranWatch - Accueil',
            'metaDescription' => 'Choisissez entre le front office et la connexion administrateur.',
            'currentPage' => '',
        ]);
    }

    public function showLogin(): void
    {
        if ($this->isAuthenticated()) {
            header('Location: /admin/sections');
            exit;
        }

        $error = (string) ($_SESSION['login_error'] ?? '');
        unset($_SESSION['login_error']);

        $oldUserName = (string) ($_SESSION['login_old_username'] ?? '');
        unset($_SESSION['login_old_username']);

        $this->render('Login', [
            'title' => 'Connexion Admin - IranWatch',
            'metaDescription' => 'Connexion au back office IranWatch.',
            'robots' => 'noindex, follow',
            'currentPage' => '',
            'loginError' => $error,
            'oldUserName' => $oldUserName,
        ]);
    }

    public function login(): void
    {
        if ($this->isAuthenticated()) {
            header('Location: /admin/sections');
            exit;
        }

        $userName = trim((string) ($_POST['user_name'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');

        if ($userName === '' || $password === '') {
            $this->redirectToLogin('Le nom d\'utilisateur et le mot de passe sont obligatoires.', $userName);
            return;
        }

        $userModel = new User();
        $user = $userModel->findByUserName($userName);
        if (!$user) {
            $this->redirectToLogin('Nom d\'utilisateur introuvable.', $userName);
            return;
        }

        $storedPassword = (string) ($user->password ?? '');
        $isPasswordValid = password_verify($password, $storedPassword) || hash_equals($storedPassword, $password);

        if (!$isPasswordValid) {
            $this->redirectToLogin('Mot de passe incorrect.', $userName);
            return;
        }

        session_regenerate_id(true);
        $_SESSION['auth_user'] = [
            'id' => (int) $user->id,
            'user_name' => (string) $user->user_name,
        ];

        header('Location: /admin/sections');
        exit;
    }

    public function logout(): void
    {
        unset($_SESSION['auth_user']);
        session_regenerate_id(true);

        header('Location: /login');
        exit;
    }

    private function isAuthenticated(): bool
    {
        return isset($_SESSION['auth_user']) && is_array($_SESSION['auth_user']);
    }

    private function redirectToLogin(string $message, string $oldUserName): void
    {
        $_SESSION['login_error'] = $message;
        $_SESSION['login_old_username'] = $oldUserName;

        header('Location: /login');
        exit;
    }
}
