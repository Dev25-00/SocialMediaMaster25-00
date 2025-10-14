# 🖥️ INSTALLATION LOCALE AVEC WAMP - GUIDE RAPIDE

## ⚡ INSTALLATION EN 5 MINUTES

### Étape 1 : Vérifier WAMP (1 min)

1. **Démarrez WAMP**

   - L'icône doit être **VERTE** (pas orange ou rouge)
   - Si orange/rouge : cliquez → "Restart All Services"

2. **Vérifiez les versions**
   - Clic droit sur l'icône WAMP
   - PHP Version : **8.0+** requis ✅
   - MySQL Version : **8.0+** requis ✅

### Étape 2 : Placer les fichiers (2 min)

```
1. Ouvrez le dossier WAMP :
   C:\wamp64\www\

2. Copiez le dossier smm-Mastery dedans :
   C:\wamp64\www\smm-Mastery\

3. C'est tout ! ✅
```

### Étape 3 : Créer la base de données (2 min)

**Option A - Via phpMyAdmin (Recommandé) :**

```
1. Ouvrez : http://localhost/phpmyadmin
2. Cliquez sur "Nouvelle base de données"
3. Nom : smm_master
4. Interclassement : utf8mb4_unicode_ci
5. Cliquez "Créer"
```

**Option B - Via SQL :**

```sql
CREATE DATABASE IF NOT EXISTS smm_master
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;
```

### Étape 4 : Lancer l'installation (1 min)

```
1. Ouvrez votre navigateur
2. Allez sur : http://localhost/smm-Mastery/install.php
3. L'installation démarre automatiquement !
```

---

## 🔧 IDENTIFIANTS MYSQL POUR WAMP

Lors de l'installation (Étape 2), entrez :

```
Hôte : localhost
Nom de la base : smm_master
Utilisateur : root
Mot de passe : root
```

**Note :** Certaines versions de WAMP ont le mot de passe **VIDE**.
Si "root" ne marche pas, essayez avec un **champ vide**.

---

## ✅ APRÈS L'INSTALLATION

### URLs à retenir :

- **Site web** : http://localhost/smm-Mastery/
- **Dashboard** : http://localhost/smm-Mastery/dashboard/
- **Login** : http://localhost/smm-Mastery/auth/login.php
- **phpMyAdmin** : http://localhost/phpmyadmin/

### Compte Admin par défaut :

```
Username : admin
Password : Admin@123
```

⚠️ **IMPORTANT** : Changez ce mot de passe immédiatement !

---

## 🐛 PROBLÈMES COURANTS WAMP

### 1. Icône WAMP Orange/Rouge

**Problème** : Apache ou MySQL ne démarre pas

**Solutions :**

```
1. Port 80 déjà utilisé (Skype, IIS, etc.) :
   - Fermez Skype
   - Ou changez le port Apache (80 → 8080)

2. Port 3306 déjà utilisé :
   - Fermez d'autres MySQL en cours
   - Redémarrez WAMP

3. Antivirus bloque :
   - Ajoutez WAMP aux exceptions
```

### 2. "Forbidden - You don't have permission"

**Solution :**

```
1. Clic droit icône WAMP
2. Apache → httpd.conf
3. Cherchez : "Require local"
4. Remplacez par : "Require all granted"
5. Sauvegardez et redémarrez Apache
```

### 3. PHP ou MySQL trop ancien

**Solution :**

```
1. Téléchargez la dernière version WAMP :
   https://www.wampserver.com/

2. Ou mettez à jour PHP/MySQL depuis WAMP :
   - Clic gauche icône → PHP → Version → Choisir 8.2
   - Clic gauche icône → MySQL → Version → Choisir 8.0
```

### 4. "Error establishing database connection"

**Solutions :**

```
1. Vérifiez que MySQL est démarré (icône verte)

2. Testez la connexion phpMyAdmin :
   http://localhost/phpmyadmin
   User: root
   Pass: root (ou vide)

3. Si ça marche dans phpMyAdmin, le problème vient du config.php

4. Vérifiez config.php :
   DB_HOST = 'localhost'
   DB_USER = 'root'
   DB_PASS = 'root'
```

### 5. Extensions PHP manquantes

**Solution :**

```
1. Clic gauche icône WAMP
2. PHP → PHP Extensions
3. Activez (cochez) :
   - php_curl
   - php_pdo_mysql
   - php_mbstring
   - php_openssl

4. Redémarrez Apache
```

---

## 🔍 VÉRIFIER QUE TOUT MARCHE

### Test 1 : Apache fonctionne

```
Ouvrez : http://localhost/
Vous devez voir la page d'accueil WAMP ✅
```

### Test 2 : MySQL fonctionne

```
Ouvrez : http://localhost/phpmyadmin/
Vous devez voir l'interface phpMyAdmin ✅
```

### Test 3 : PHP fonctionne

```
Créez un fichier test.php dans C:\wamp64\www\
Contenu : <?php phpinfo(); ?>
Ouvrez : http://localhost/test.php
Vous devez voir les infos PHP ✅
```

### Test 4 : Votre site fonctionne

```
Ouvrez : http://localhost/smm-Mastery/
Vous devez voir la page d'accueil SMM Mastery ✅
```

---

## 🎯 CONSEIL PRO

### Créer un Virtual Host (Optionnel)

Pour accéder à votre site via : **http://smmmaster.local** au lieu de **http://localhost/smm-Mastery/**

**Étapes :**

1. **Éditez httpd-vhosts.conf**

```
Clic gauche WAMP → Apache → httpd-vhosts.conf

Ajoutez :
<VirtualHost *:80>
    ServerName smmmaster.local
    DocumentRoot "C:/wamp64/www/smm-Mastery"
    <Directory "C:/wamp64/www/smm-Mastery">
        Options +Indexes +FollowSymLinks +MultiViews
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

2. **Éditez le fichier hosts**

```
Ouvrez (en tant qu'administrateur) :
C:\Windows\System32\drivers\etc\hosts

Ajoutez :
127.0.0.1 smmmaster.local
```

3. **Redémarrez WAMP**

4. **Accédez à : http://smmmaster.local** ✅

---

## 📊 CONFIGURATION WAMP OPTIMALE

### Pour le développement :

**php.ini** (Clic gauche WAMP → PHP → php.ini)

```ini
upload_max_filesize = 64M
post_max_size = 64M
memory_limit = 256M
max_execution_time = 300
display_errors = On
error_reporting = E_ALL
```

**my.ini** (Clic gauche WAMP → MySQL → my.ini)

```ini
max_allowed_packet = 64M
```

Redémarrez WAMP après modifications ✅

---

## 🚀 PASSER EN PRODUCTION

Quand vous êtes prêt à mettre en ligne :

1. **Exportez la base de données**

```
phpMyAdmin → smm_master → Exporter → GO
Téléchargez le fichier .sql
```

2. **Compressez les fichiers**

```
Sélectionnez le dossier smm-Mastery
Clic droit → Envoyer vers → Dossier compressé
```

3. **Uploadez sur votre serveur**

```
Via FTP ou cPanel
```

4. **Importez la BDD sur le serveur**

```
phpMyAdmin serveur → Importer → Choisir .sql
```

5. **Modifiez config.php**

```
Changez les identifiants BDD pour ceux du serveur
```

---

## 💡 ASTUCES WAMP

### Accès depuis d'autres appareils (téléphone, tablette)

1. **Trouvez votre IP locale**

```
cmd → ipconfig
Cherchez : IPv4 Address (ex: 192.168.1.100)
```

2. **Autorisez l'accès externe**

```
httpd.conf → Require local → Require all granted
```

3. **Accédez depuis autre appareil**

```
http://192.168.1.100/smm-Mastery/
```

### Gérer plusieurs projets

```
C:\wamp64\www\
├── smm-Mastery/          → http://localhost/smm-Mastery/
├── autre-projet/        → http://localhost/autre-projet/
└── test-site/          → http://localhost/test-site/
```

---

## 🆘 SUPPORT WAMP

**Forum officiel :** http://forum.wampserver.com/
**Documentation :** http://www.wampserver.com/en/
**YouTube :** Tutoriels WAMP (nombreuses vidéos)

---

## ✅ CHECKLIST WAMP

Avant de commencer le développement :

- [ ] WAMP installé et icône VERTE
- [ ] PHP 8.0+ actif
- [ ] MySQL 8.0+ actif
- [ ] Extensions PHP activées
- [ ] Base de données créée
- [ ] Site accessible sur http://localhost/smm-Mastery/
- [ ] phpMyAdmin fonctionne
- [ ] Compte admin créé

---

**🎉 PRÊT À DÉVELOPPER AVEC WAMP !**

Votre environnement local est maintenant configuré.
Vous pouvez développer et tester en toute tranquillité ! 💻

---

**Date** : 11 Octobre 2025
**Version** : WAMP 3.3.0+ recommandée
**Compatibilité** : Windows 7, 8, 10, 11
