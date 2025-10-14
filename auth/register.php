<?php
require_once '../config.php';
require_once '../functions.php';

// Si déjà connecté, rediriger vers le dashboard
if (isLoggedIn()) {
    redirect('../dashboard/index.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = clean($_POST['username'] ?? '');
    $email = clean($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Validation
    if (empty($username) || empty($email) || empty($password)) {
        $error = 'Tous les champs sont obligatoires';
    } elseif (strlen($username) < 3) {
        $error = 'Le nom d\'utilisateur doit contenir au moins 3 caractères';
    } elseif (!isValidEmail($email)) {
        $error = 'Email invalide';
    } elseif (!isValidPassword($password)) {
        $error = 'Le mot de passe doit contenir au moins 8 caractères, 1 majuscule et 1 chiffre';
    } elseif ($password !== $confirm_password) {
        $error = 'Les mots de passe ne correspondent pas';
    } else {
        // Vérifier si l'utilisateur existe déjà
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        
        if ($stmt->fetch()) {
            $error = 'Ce nom d\'utilisateur ou cet email est déjà utilisé';
        } else {
            // Créer le compte
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            $stmt = $pdo->prepare("
                INSERT INTO users (username, email, password, balance, role, status, created_at)
                VALUES (?, ?, ?, 1.00, 'user', 'active', NOW())
            ");
            
            if ($stmt->execute([$username, $email, $hashed_password])) {
                $user_id = $pdo->lastInsertId();
                
                // Ajouter bonus de bienvenue
                addTransaction($pdo, $user_id, 'bonus', 1.00, null, 'Bonus de bienvenue');
                
                $success = 'Compte créé avec succès ! Vous recevez 1$ de bonus. Vous pouvez maintenant vous connecter.';
                
                // Envoyer email de bienvenue (optionnel)
                // sendEmail($email, 'Bienvenue sur ' . SITE_NAME, 'Votre compte a été créé...');
            } else {
                $error = 'Erreur lors de la création du compte';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - <?php echo SITE_NAME; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/main.css">
</head>
<body class="auth-page">
    <div class="auth-container">
        <div class="auth-box">
            <div class="auth-logo">
                <h1>🚀 <?php echo SITE_NAME; ?></h1>
                <p>Créez votre compte</p>
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success">
                    <?php echo $success; ?>
                    <a href="login.php" style="margin-top: 10px; display: inline-block;">Se connecter →</a>
                </div>
            <?php else: ?>
                <form method="POST" class="auth-form">
                    <div class="form-group">
                        <label for="username">Nom d'utilisateur</label>
                        <input type="text" id="username" name="username" required 
                               value="<?php echo $_POST['username'] ?? ''; ?>"
                               placeholder="johndoe">
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required 
                               value="<?php echo $_POST['email'] ?? ''; ?>"
                               placeholder="john@example.com">
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <input type="password" id="password" name="password" required 
                               placeholder="Min 8 caractères, 1 majuscule, 1 chiffre">
                        <small>Min 8 caractères, 1 majuscule, 1 chiffre</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password">Confirmer le mot de passe</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-block">
                        Créer mon compte
                    </button>
                </form>
                
                <div class="auth-footer">
                    <p>Déjà un compte ? <a href="login.php">Se connecter</a></p>
                </div>
                
                <div class="welcome-bonus">
                    🎁 Bonus de bienvenue : <strong>1.00$</strong> offert !
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
