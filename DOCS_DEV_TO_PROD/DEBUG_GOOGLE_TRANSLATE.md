# 🔧 DEBUG GOOGLE TRANSLATE - TESTS À EFFECTUER

**Date:** 14 Octobre 2025 - 16:35  
**Correctifs appliqués:** API debug + retry logic renforcé

---

## 🧪 TESTS IMMÉDIATS À FAIRE

### **1. Test Console JavaScript**

1. Ouvrir http://localhost/smm/dashboard/
2. **F12** → onglet **Console**
3. Rechercher messages `[SMM Translate]`
4. **Vérifier que vous voyez :**
   ```
   [SMM Translate] 📡 Chargement script Google Translate...
   [SMM Translate] 🚀 Initialisation Google Translate API...
   [SMM Translate] Configuration Google: {...}
   [SMM Translate] ✅ Google Translate Element créé
   [SMM Translate] ✅ Widget Google injecté! Options disponibles: X
   ```

### **2. Test Fonctionnel Traduction**

1. Clic sur bouton traduction (globe bleu)
2. Sélectionner **"English"**
3. **Observer console :**
   ```
   [SMM Translate] 🎯 Déclenchement traduction: en tentative 1
   [SMM Translate] ✅ Sélecteur Google trouvé avec X options
   [SMM Translate] 🔄 Changement: fr → en
   [SMM Translate] ✅ Traduction activée avec succès!
   ```
4. **Vérifier que le texte change** (navigation, titres, boutons)

### **3. Test Erreurs Réseau**

Si aucun message ne s'affiche :

1. **F12** → onglet **Réseau (Network)**
2. Rechercher `translate.google.com`
3. Vérifier statut **200 OK** (pas 404, pas bloqué)
4. Si erreur → vérifier pare-feu/antivirus

---

## 🐛 DÉPANNAGE SI PROBLÈME

### **Cas 1: Script Google non chargé**

**Symptômes :** Message `❌ API Google Translate non disponible`
**Solutions :**

- Vérifier connexion internet
- Désactiver bloqueur de publicité (uBlock, AdBlock)
- Vérifier pare-feu Windows
- Essayer autre navigateur

### **Cas 2: Widget non injecté**

**Symptômes :** Message `❌ Widget Google non injecté après X ms`
**Solutions :**

- Vérifier que `<div id="google_translate_element"></div>` existe
- Inspecter élément → chercher `.goog-te-combo`
- Recharger page (Ctrl+F5)

### **Cas 3: Traduction ne s'applique pas**

**Symptômes :** Console OK mais texte ne change pas
**Solutions :**

- Vérifier attribut `translate="no"` dans HTML
- Vérifier CSP (Content-Security-Policy)
- Tester avec texte simple (pas de JavaScript complexe)

---

## 📊 RÉSULTATS À REPORTER

**Console Messages :**

```
□ Script Google chargé
□ API initialisée
□ Widget injecté
□ Traduction déclenchée
□ Texte changé visuellement
```

**Si Problème :**

- Copier messages d'erreur console
- Screenshot Network tab si échec chargement
- Indiquer navigateur/version utilisé

---

**🔄 PROCHAINE ÉTAPE :** Tester et reporter les résultats pour validation finale
