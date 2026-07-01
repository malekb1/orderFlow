<?php
session_start();
require_once __DIR__ . '/includes/connexion.php';
require_once __DIR__ . '/includes/auth.php';

if (isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . ($_SESSION['user_role'] === 'admin' ? '/admin/dashboard.php' : '/user/dashboard.php'));
    exit();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $pass  = $_POST['password'] ?? '';

    if ($login === '' || $pass === '') {
        $error = 'Veuillez remplir tous les champs.';
    } else {
        $stmt = $conn->prepare("SELECT * FROM utilisateur WHERE login = ? AND actif = 1");
        $stmt->bind_param("s", $login);
        $stmt->execute();
        $result = $stmt->get_result();
        $row    = $result->fetch_assoc();
        $stmt->close();

        if ($row && (password_verify($pass, $row['motDePasse']) || $pass === $row['motDePasse'])) {
            $_SESSION['user_id']     = $row['idUtilisateur'];
            $_SESSION['user_nom']    = $row['nom'];
            $_SESSION['user_prenom'] = $row['prenom'];
            $_SESSION['user_role']   = $row['role'];
            $_SESSION['user_email']  = $row['email'];

            $id = $row['idUtilisateur'];
            $conn->query("UPDATE utilisateur SET dernierConnexion = NOW() WHERE idUtilisateur = $id");

            header('Location: ' . BASE_URL . ($row['role'] === 'admin' ? '/admin/dashboard.php' : '/user/dashboard.php'));
            exit();
        } else {
            $error = 'Identifiants incorrects. Vérifiez votre login et mot de passe.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion – OrderFlow CPT</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lucide/0.383.0/umd/lucide.min.js"></script>
    <style>
        .login-bg-deco {
            position: absolute; inset: 0; overflow: hidden; pointer-events: none;
        }
        .login-bg-deco span {
            position: absolute; border-radius: 50%;
            background: rgba(255,255,255,.05);
        }
    </style>
</head>
<body>
<div class="login-page" style="position:relative">
    <div class="login-bg-deco">
        <span style="width:400px;height:400px;top:-100px;left:-100px"></span>
        <span style="width:300px;height:300px;bottom:-80px;right:-80px"></span>
    </div>

    <div class="login-card" style="position:relative;z-index:1">
        <div class="login-logo">
            <div style="margin-bottom:10px"><i data-lucide="cog" style="width:44px;height:44px;stroke:#1a3558"></i></div>
            <div class="brand">OrderFlow</div>
            <div class="sub">Continuus Properzi Tunisie – Bizerte</div>
            <div style="margin-top:6px;font-size:12px;color:#aaa">Système de gestion des commandes & facturation</div>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label class="form-label">Identifiant</label>
                <input type="text" name="login" class="form-control"
                       placeholder="Votre login"
                       value="<?= htmlspecialchars($_POST['login'] ?? '') ?>"
                       required autofocus>
            </div>
            <div class="form-group">
                <label class="form-label">Mot de passe</label>
                <input type="password" name="password" class="form-control"
                       placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn btn-primary"
                    style="width:100%;justify-content:center;padding:12px;font-size:15px;margin-top:8px">
                Se connecter →
            </button>
        </form>

<p style="text-align:center;margin-top:12px">
    <a href="<?= BASE_URL ?>/includes/reset_password.php">
        🔑 Mot de passe oublié ?
    </a>
</p>

    </div>
</div>
<script>if(window.lucide){lucide.createIcons();}</script>
</body>
</html>