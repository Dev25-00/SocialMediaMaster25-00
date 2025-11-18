<?php
/**
 * PAGE: Forgot Password
 * URL: /auth/forgot-password.php
 *
 * FONCTIONNALITÉS:
 * - Formulaire pour demander la réinitialisation du mot de passe
 * - Génération d'un token sécurisé et enregistrement en base (password_resets)
 * - Envoi d'un email avec un lien de réinitialisation via sendEmail() (PHP mail)
 *
 * DÉPENDANCES:
 * - config.php (connexion PDO + helpers)
 * - functions.php (sendEmail(), isValidEmail())
 */

require_once __DIR__ . '/../config.php';

// Créer la table password_resets si absente
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS password_resets (
        id INT AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(255) NOT NULL,
        token VARCHAR(128) NOT NULL,
        expires_at DATETIME NOT NULL,
        created_at DATETIME NOT NULL,
        INDEX (email),
        INDEX (token)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
} catch (PDOException $e) {
    // ignore if fail
}

$message = '';
$previewLink = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $action = isset($_POST['action']) ? $_POST['action'] : 'send'; // 'send' or 'preview'

    if (!isValidEmail($email)) {
        $message = 'Adresse email invalide.';
    } else {
        // Chercher l'utilisateur (si existe)
        $stmt = $pdo->prepare("SELECT id, email FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        // Générer token et insérer (même si user absent on simule succès pour ne pas divulguer)
        $token = bin2hex(random_bytes(32));
        $expires_at = date('Y-m-d H:i:s', time() + 3600); // 1 heure
        $created_at = date('Y-m-d H:i:s');

        // Supprimer anciens tokens pour cet email
        $del = $pdo->prepare("DELETE FROM password_resets WHERE email = ?");
        $del->execute([$email]);

        $ins = $pdo->prepare("INSERT INTO password_resets (email, token, expires_at, created_at) VALUES (?, ?, ?, ?)");
        $ins->execute([$email, $token, $expires_at, $created_at]);

        // Préparer le lien de réinitialisation
        $resetUrl = rtrim(SITE_URL, '/') . '/auth/reset-password.php?token=' . $token;

        $subject = 'Réinitialisation de votre mot de passe - ' . SITE_NAME;
        $html = '<p>Bonjour,</p>';
        $html .= '<p>Vous avez demandé la réinitialisation de votre mot de passe. Cliquez sur le lien ci-dessous pour choisir un nouveau mot de passe (valable 1 heure):</p>';
        $html .= '<p><a href="' . htmlspecialchars($resetUrl) . '">' . htmlspecialchars($resetUrl) . '</a></p>';
        $html .= '<p>Si vous n\'avez pas demandé cette action, ignorez ce message.</p>';
        $html .= '<p>Cordialement,<br/>' . SITE_NAME . '</p>';

        // Action handling
        if ($action === 'send') {
            // N'envoyer l'email que si l'adresse existe réellement pour limiter le risque d'abus
            if ($user) {
                $sent = sendEmail($user['email'], $subject, $html);
                if ($sent) {
                    $message = 'Si un compte existe pour cette adresse, un lien de réinitialisation a été envoyé.';
                } else {
                    $message = 'Tentative d\'envoi échouée (mail()). Si vous êtes en local, utilisez le bouton "Afficher le lien" pour tester.';
                }
            } else {
                // Anti-enumeration message
                $message = 'Si un compte existe pour cette adresse, un lien de réinitialisation a été envoyé.';
            }
        } else {
            // preview mode: show the reset link on-screen for local testing
            // only allowed when DEBUG_MODE or admin setting allow_password_preview == '1'
            $siteSettings = getSiteSettings($pdo);
            $allowPreview = DEBUG_MODE || (!empty($siteSettings['allow_password_preview']) && $siteSettings['allow_password_preview'] === '1');

            if ($allowPreview) {
                $previewLink = $resetUrl;
                $message = 'Mode test: lien de réinitialisation généré ci-dessous.';
                if (!DEBUG_MODE) {
                    $message .= ' (Activé par un administrateur)';
                }
            } else {
                $message = 'Le mode aperçu n\'est pas autorisé. Contactez un administrateur.';
            }
        }
    }
}

?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Mot de passe oublié - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/global/main.css">
    <style> .fp-wrap{max-width:480px;margin:40px auto;padding:18px;background:#fff;border-radius:8px;} </style>
</head>
<body>
<div class="fp-wrap">
    <h2>Mot de passe oublié</h2>
    <?php if ($message): ?>
        <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <form method="post" action="">
        <label for="email">Votre adresse email</label><br>
        <input type="email" name="email" id="email" required value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" style="width:100%;padding:8px;margin:8px 0;border-radius:6px;border:1px solid #ddd;">
        <div style="display:flex;gap:8px;">
            <button type="submit" name="action" value="send" style="flex:1;padding:10px 14px;border-radius:8px;background:#2f8bfd;color:#fff;border:none;">Envoyer le lien (serveur)</button>
            <button type="submit" name="action" value="preview" style="flex:1;padding:10px 14px;border-radius:8px;background:#6b46ff;color:#fff;border:none;">Afficher le lien (test local)</button>
        </div>
    </form>

    <?php if (!empty($previewLink)): ?>
        <div style="margin-top:12px;padding:10px;background:#f5f7ff;border-radius:6px;word-break:break-all;">
            <strong>Lien de réinitialisation (preview):</strong>
            <div style="margin-top:6px;color:#333;"><a href="<?php echo htmlspecialchars($previewLink); ?>"><?php echo htmlspecialchars($previewLink); ?></a></div>
        </div>
    <?php endif; ?>

    <p style="margin-top:12px;"><a href="../auth/login.php">Retour à la connexion</a></p>
</div>
</body>
</html>
