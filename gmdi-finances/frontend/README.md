# GMDI — Module Finances (Angular 21)

Application de gestion financière communale pour la plateforme GMDI.  
Migré depuis le prototype HTML vers **Angular 21** avec architecture moderne.

---

## 🏗️ Architecture

```
src/
├── app/
│   ├── core/
│   │   ├── models/
│   │   │   └── finances.models.ts       ← Interfaces TypeScript typées
│   │   └── services/
│   │       ├── finances.service.ts      ← State réactif (Signals Angular 21)
│   │       └── toast.service.ts         ← Notifications globales
│   ├── features/
│   │   ├── budget/
│   │   │   └── budget.component.ts      ← Élaboration, exécution, révisions
│   │   ├── recettes/
│   │   │   └── recettes.component.ts    ← Saisie, liste, encaissement
│   │   ├── depenses/
│   │   │   └── depenses.component.ts    ← Engagement, liste, salaires
│   │   ├── comptabilite/
│   │   │   └── comptabilite.component.ts← Journal, grand livre, balance, bilan
│   │   ├── tresorerie/
│   │   │   └── tresorerie.component.ts  ← Caisse, banque, rapprochement
│   │   └── rapports/
│   │       └── rapports.component.ts    ← Tableaux de bord & exports
│   ├── shared/
│   │   └── pipes/
│   │       └── fcfa.pipe.ts             ← Formatage monétaire FCFA
│   ├── app.component.ts                 ← Root : topbar + sidebar + router-outlet
│   ├── app.config.ts                    ← Bootstrap standalone (Angular 21)
│   └── app.routes.ts                    ← Lazy-loading routes
├── styles.scss                          ← Styles globaux (design system GMDI)
└── index.html
```

---

## ⚡ Technologies Angular 21

| Fonctionnalité | Utilisation |
|---|---|
| **Signals** (`signal`, `computed`) | État réactif dans `FinancesService` |
| **Standalone components** | Tous les composants sont standalone |
| **`@for` / `@if`** | Nouveau control flow (pas de `*ngFor`/`*ngIf`) |
| **Lazy loading** | `loadComponent()` pour chaque route |
| **`inject()`** | Injection fonctionnelle sans constructeur |
| **`FormsModule`** | Two-way binding avec `[(ngModel)]` |
| **Pipe `fcfa`** | Formatage FCFA via `Intl.NumberFormat` |

---

## 🚀 Installation et démarrage

```bash
# 1. Prérequis : Node.js ≥ 22.22.3 ou ≥ 24.x
node --version

# 2. Installer Angular CLI 21
npm install -g @angular/cli@21

# 3. Cloner / décompresser le projet
cd gmdi-finances-angular

# 4. Installer les dépendances
npm install

# 5. Démarrer le serveur de développement
ng serve
# → http://localhost:4200
```

---

## 📋 Modules fonctionnels

### 💼 Budget (`/budget`)
- **Élaboration** : formulaire de saisie de lignes budgétaires
- **Exécution budgétaire** : KPIs + graphique barres mensuel (recettes vs dépenses)
- **Budget prévisionnel** : tableau avec taux de consommation et barres de progression
- **Révisions** : formulaire de révision budgétaire

### 💰 Recettes (`/recettes`)
- **Nouvelle recette** : taxes foncières, patentes, droits domaniaux, Mobile Money (Orange Money, MTN MoMo, Wave, Moov Money)
- **Liste des recettes** : filtrage par type, statut, recherche textuelle
- **Encaissement** : validation rapide avec génération de reçu

### 💸 Dépenses (`/depenses`)
- **Nouvelle dépense** : engagement avec imputation budgétaire
- **Liste des dépenses** : filtrage par chapitre et statut
- **Salaires** : tableau de bord masse salariale + paiement en lot

### 📒 Comptabilité (`/comptabilite`)
- **Journal comptable** : écritures avec débit/crédit/pièce
- **Grand livre** : soldes par compte
- **Balance** : équilibre débiteur/créditeur
- **Bilan** : Actif/Passif exercice 2025

### 🏦 Trésorerie (`/tresorerie`)
- **Caisse** : mouvements du jour + historique
- **Banque** : relevé Banque du Trésor
- **Rapprochement bancaire** : validation mai 2025

### 📊 Rapports (`/rapports`)
- Tableau de bord financier mensuel/trimestriel/annuel
- Situation financière globale
- Recettes par service avec barres de progression
- Export JSON, rapports imprimables

---

## 🎨 Design system

Couleurs GMDI :
- **Primaire** : `#003366` (bleu marine institutionnel)
- **Accent** : `#009A44` (vert — recettes / validé)
- **Or** : `#C9A84C` (topbar, accents)
- **Orange** : `#F77F00` (dépenses / alertes)
- **Bleu** : `#185FA5` (indicateurs neutres)
- **Rouge** : `#E24B4A` (erreurs / en retard)

---

## 📦 Prochaines étapes

- [ ] Intégration API REST (NestJS / Spring Boot)
- [ ] Authentification JWT + rôles (Directeur Financier, Agent, Maire)
- [ ] Export PDF (rapports officiels)
- [ ] Mode sombre
- [ ] Tests unitaires (Jest) + E2E (Playwright)
