# GMDI Patrimoine — Guide d'installation backend Laravel

## Prérequis

- PHP 8.2+ / Composer / MySQL 8+ ou MariaDB 10.6+
- Node.js 20+ (pour Angular)

---

## 1. Créer et configurer le projet Laravel

```bash
composer create-project laravel/laravel gmdi-backend-patrimoine
cd gmdi-backend-patrimoine
composer require laravel/sanctum spatie/laravel-permission
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"

cp .env.example .env
php artisan key:generate
```

Éditer `.env` :
```
DB_DATABASE=gmdi_patrimoine
DB_USERNAME=root
DB_PASSWORD=votre_mdp
```

## 2. Créer la base de données

```sql
CREATE DATABASE gmdi_patrimoine CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

## 3. Migrations (11 tables)

Décommenter chaque bloc de `database/migrations/all_migrations.php` et copier dans des fichiers numérotés, puis :

```bash
php artisan migrate
```

Tables créées :
- `biens` — inventaire général (7 catégories)
- `vehicules` — parc automobile avec assurance/visite technique
- `terrains` — foncier communal
- `mobiliers` — bureaux, chaises, armoires…
- `informatiques` — PC, imprimantes, réseaux…
- `equipements` — groupes électrogènes, climatiseurs…
- `affectations` — historique mouvements
- `entretiens` — programme préventif
- `reparations` — interventions correctives
- `amortissements` — tableau comptable par bien

## 4. Copier les fichiers

```
app/Http/Controllers/Api/AuthController.php
app/Http/Controllers/Api/Patrimoine/BienController.php
app/Http/Controllers/Api/Patrimoine/VehiculeController.php
app/Http/Controllers/Api/Patrimoine/TerrainController.php   (+ Mobilier/Informatique/Equipement)
app/Http/Controllers/Api/Patrimoine/AffectationController.php
app/Http/Controllers/Api/Patrimoine/EntretienController.php
app/Http/Controllers/Api/Patrimoine/ReparationController.php
app/Http/Controllers/Api/Patrimoine/AmortissementController.php
app/Http/Controllers/Api/Patrimoine/StatsPatrimoineController.php
app/Models/PatrimoineModels.php   (tous les models)
app/Models/User.php
routes/api.php
config/cors.php
```

## 5. Seeders

```bash
php artisan db:seed --class=RolesPermissionsSeeder
php artisan db:seed --class=AdminUserSeeder
php artisan db:seed --class=PatrimoineSeeder
```

Comptes :

| Email                    | Mot de passe        | Rôle              |
|--------------------------|---------------------|-------------------|
| patrimoine@mairie.ci     | Patrimoine@2025!    | chef_patrimoine   |
| agent.pat@mairie.ci      | Agent@2025!         | agent_patrimoine  |
| admin@mairie.ci          | Admin@2025!         | admin             |

## 6. Lancer

```bash
php artisan serve              # → http://localhost:8000
# Frontend
cd gmdi-patrimoine && npm install && ng serve   # → http://localhost:4200
```

## 7. Test rapide

```bash
# Login
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"patrimoine@mairie.ci","password":"Patrimoine@2025!"}'

# Stats
curl http://localhost:8000/api/patrimoine/statistiques \
  -H "Authorization: Bearer <TOKEN>"
```

---

## Endpoints complets

```
POST   /api/auth/login          refresh  logout  me

# Inventaire
GET    /api/patrimoine/biens                  ?search=&categorie=&statut=&page=
POST   /api/patrimoine/biens
GET    /api/patrimoine/biens/reference/{ref}
GET    /api/patrimoine/biens/{id}
PUT    /api/patrimoine/biens/{id}
DELETE /api/patrimoine/biens/{id}
PATCH  /api/patrimoine/biens/{id}/statut
GET    /api/patrimoine/biens/{id}/qr
GET    /api/patrimoine/biens/{id}/fiche

# Sous-catégories spécialisées
GET|POST|PUT  /api/patrimoine/vehicules/{id?}
GET|POST|PUT  /api/patrimoine/terrains/{id?}
GET|POST|PUT  /api/patrimoine/mobilier/{id?}
GET|POST|PUT  /api/patrimoine/informatique/{id?}
GET|POST|PUT  /api/patrimoine/equipements/{id?}

# Mouvements et maintenance
GET|POST      /api/patrimoine/affectations
POST          /api/patrimoine/entretiens
PATCH         /api/patrimoine/entretiens/{id}/valider
POST          /api/patrimoine/reparations
PATCH         /api/patrimoine/reparations/{id}/resoudre

# Amortissement
GET    /api/patrimoine/amortissements
GET    /api/patrimoine/amortissements/simuler?valeur=&taux=&annees=

# Rapports
GET    /api/patrimoine/statistiques
GET    /api/patrimoine/export?type=biens|terrains|entretiens|reparations|amortissements|all
```
