<?php
/**
 * PAGE: Reset Password
 * URL: /auth/reset-password.php?token=...
 */

require_once __DIR__ . '/../config.php';

$token = isset($_GET['token']) ? $_GET['token'] : (isset($_POST['token']) ? $_POST['token'] : '');
$message = '';
$error = '';

if (!$token) {
    $error = 'Token invalide.';
} else {
    // Chercher le token
    $stmt = $pdo->prepare("SELECT * FROM password_resets WHERE token = ? LIMIT 1");
    $stmt->execute([$token]);
    $row = $stmt->fetch();

    if (!$row) {
        $error = 'Token introuvable ou expiré.';
    } else {
        // Vérifier expiration
        if (strtotime($row['expires_at']) < time()) {
            // Supprimer
            $del = $pdo->prepare("DELETE FROM password_resets WHERE token = ?");
            $del->execute([$token]);
            $error = 'Le lien a expiré.';
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$error) {
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $password2 = isset($_POST['password2']) ? $_POST['password2'] : '';

    if ($password !== $password2) {
        $error = 'Les mots de passe ne correspondent pas.';
    } elseif (!isValidPassword($password)) {
        $error = 'Mot de passe invalide (min 8, 1 majuscule, 1 chiffre).';
    } else {
        // Mettre à jour le mot de passe si l'email correspond
        $email = $row['email'];
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $upd = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
        $upd->execute([$hash, $email]);

        // Supprimer le token
        $del = $pdo->prepare("DELETE FROM password_resets WHERE token = ?");
        $del->execute([$token]);

        // Envoyer un email de confirmation
        $subject = 'Votre mot de passe a été réinitialisé';
        $html = '<p>Bonjour,</p><p>Votre mot de passe a bien été réinitialisé. Si ce n\'est pas vous, contactez le support.</p><p>Cordialement,<br/>' . SITE_NAME . '</p>';
        sendEmail($email, $subject, $html);

        $message = 'Mot de passe réinitialisé avec succès. Vous pouvez maintenant vous connecter.';
    }
}

?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Réinitialiser le mot de passe - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/main.css">
    <style> .fp-wrap{max-width:480px;margin:40px auto;padding:18px;background:#fff;border-radius:8px;} </style>
</head>
<body>
<div class="fp-wrap">
    <h2>Réinitialiser le mot de passe</h2>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <?php if ($message): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
        <p><a href="../auth/login.php">Se connecter</a></p>
    <?php else: ?>
        <form method="post" action="">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
            <label for="password">Nouveau mot de passe</label><br>
            <input type="password" name="password" id="password" required style="width:100%;padding:8px;margin:8px 0;border-radius:6px;border:1px solid #ddd;">
            <label for="password2">Confirmer le mot de passe</label><br>
            <input type="password" name="password2" id="password2" required style="width:100%;padding:8px;margin:8px 0;border-radius:6px;border:1px solid #ddd;">
            <button type="submit" style="padding:10px 14px;border-radius:8px;background:#4a76ff;color:#fff;border:none;">Réinitialiser</button>
        </form>
    <?php endif; ?>
</div>
</body>
</html>
