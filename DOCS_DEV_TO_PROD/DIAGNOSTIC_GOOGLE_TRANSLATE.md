# 🚨 DIAGNOSTIC GOOGLE TRANSLATE - ÉTAPES DE TEST

**Problème identifié :** Le sélecteur `.goog-te-combo` n'existe pas  
**Cause probable :** L'API Google Translate ne s'initialise pas correctement

---

## 📋 TESTS À EFFECTUER DANS L'ORDRE

### **1. Test Page Simple**

- Ouvrir : http://localhost/smm/test-google-translate-simple.html
- **F12 → Console** → Observer les messages
- **Vérifier :**
  - ✅ "Google accessible"
  - ✅ "Callback googleTranslateElementInit appelé"
  - ✅ "Widget Google créé avec succès"
  - ✅ "Sélecteur .goog-te-combo trouvé? true"

### **2. Test Dashboard avec Diagnostic**

- Ouvrir : http://localhost/smm/dashboard/
- **F12 → Console** → Observer nouveaux messages :
  - `[SMM Translate] 🚀 Script Google ajouté au DOM`
  - `[SMM Translate] ✅ Script Google chargé avec succès`
  - `[SMM Translate] ✅ API Google disponible`
  - `[SMM Translate] ✅ Élément cible trouvé`

### **3. Test Réseau (Si échec)**

- **F12 → Network** → Recharger page
- Chercher `translate_a/element.js`
- **Vérifier statut :** 200 OK (pas 404, pas bloqué)
- **Si bloqué :** désactiver bloqueur pub, antivirus, VPN

---

## 🔍 DIAGNOSTICS POSSIBLES

### **Cas A: Script ne se charge pas**

**Symptoms:** Pas de message "Script Google chargé"
**Solutions:**

- Vérifier connexion internet
- Désactiver AdBlock/uBlock Origin
- Tester autre navigateur (Edge, Firefox)
- Vérifier pare-feu Windows

### **Cas B: Script chargé mais API indisponible**

**Symptoms:** "Script chargé" mais "API non disponible"
**Solutions:**

- Problème DNS → Tester: `nslookup translate.google.com`
- Proxy/VPN → Désactiver temporairement
- CSP restrictif → Vérifier headers HTTP

### **Cas C: API OK mais widget ne s'injecte pas**

**Symptoms:** API disponible mais `.goog-te-combo` absent
**Solutions:**

- Element #google_translate_element mal placé
- Conflit CSS (display:none, visibility:hidden)
- JavaScript qui interfère

---

## 🛠️ ACTIONS CORRECTIVES

### **Si Test Simple FONCTIONNE mais Dashboard NON**

→ Problème dans notre widget personnalisé  
→ Conflit avec CSS/JS existant  
→ Timing d'initialisation

### **Si AUCUN des deux ne fonctionne**

→ Problème environnemental (réseau, bloqueur, proxy)  
→ Essayer depuis autre machine/connexion  
→ Vérifier date/heure système (certificats SSL)

### **Solution de Secours Ajoutée**

- Fallback automatique après 2s si erreur
- Configuration minimale pour réduire points de défaillance
- Messages d'erreur détaillés pour diagnostic

---

## 📊 RAPPORT DEMANDÉ

**Test 1 - Page Simple :**

```
Console messages : [copier ici]
Widget visible ? : OUI/NON
Dropdown fonctionne ? : OUI/NON
```

**Test 2 - Dashboard :**

```
Messages diagnostic : [copier ici]
Erreurs Network ? : OUI/NON
Status translate_a/element.js : [200/404/blocked]
```

---

**🎯 Objectif :** Déterminer si le problème vient de notre code ou de l'environnement Google Translate
