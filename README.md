# Mini-CMS

Projet fil rouge de l'Atelier Framework Côté Serveur (Laravel 13, PHP 8.3+), 3e année MDW, ISET Sidi Bouzid.

Auteur : Prenom Nom, groupe MDW32.

Mini-CMS évoluera au fil du semestre vers une petite plateforme de publication : pages publiques, back-office authentifié, API JSON et tests Pest. Chaque session se termine par un tag Git (`lab-01`, `lab-01b`, puis un tag par session).

## État actuel (tag lab-06b)

- Middleware `admin` (`EnsureUserIsAdmin`) sur tout `/admin`, enregistré avec `writable` dans un seul appel `alias()` de `bootstrap/app.php`.
- Gate `manage-tags` utilisé dans `TagController`, dans `StoreTagRequest`/`UpdateTagRequest` et dans le menu (`@can`).
- `PostPolicy` complète (`view`, `create`, `update`, `delete`) : un brouillon n'est visible que par son auteur et les admins, sur sa page et dans `/posts` (scope `visibleTo()`).
- `Gate::before` : les admins passent toutes les règles, sauf `changeRole`.
- Back-office des utilisateurs (`/admin/utilisateurs`) : un admin change le rôle des autres comptes, jamais le sien (`UserPolicy`).
- Tests Pest : `AdminAccessTest` (7 tests), `PostVisibilityTest` (5 tests), `UserRoleTest` (2 tests) en plus des tests précédents.
- Section « Comparaison avec le starter kit Livewire » ci-dessous.

## Comptes de test

| Compte | Mot de passe | Rôle |
|---|---|---|
| `test@example.com` | `password` | admin |
| comptes créés par `DatabaseSeeder` | `password` | author |

## Routes disponibles

| Méthode | URI | Nom | Action |
|---|---|---|---|
| GET | `/` | `home` | `PageController@home` |
| GET | `/a-propos` | `about` | `PageController@about` |
| GET | `/heure` | `time` | `PageController@time` |
| GET | `/posts` | `posts.index` | `PostController@index` |
| GET | `/posts/archive/{year}` | `posts.archive` | `PostController@archive` |
| GET | `/posts/categorie/{category:slug}` | `posts.category` | `PostController@byCategory` |
| GET | `/posts/tag/{tag:slug}` | `posts.tag` | `PostController@byTag` |
| GET | `/posts/create` | `posts.create` | `PostController@create` |
| POST | `/posts` | `posts.store` | `PostController@store` |
| GET | `/posts/{post}` | `posts.show` | `PostController@show` |
| GET | `/posts/{post}/edit` | `posts.edit` | `PostController@edit` |
| PUT | `/posts/{post}` | `posts.update` | `PostController@update` |
| DELETE | `/posts/{post}` | `posts.destroy` | `PostController@destroy` |
| PATCH | `/posts/{post}/publication` | `posts.publication` | `PostController@togglePublication` |
| GET | `/admin/categories` | `admin.categories.index` | `CategoryController@index` |
| GET | `/admin/categories/create` | `admin.categories.create` | `CategoryController@create` |
| POST | `/admin/categories` | `admin.categories.store` | `CategoryController@store` |
| GET | `/admin/categories/{category}/edit` | `admin.categories.edit` | `CategoryController@edit` |
| PUT | `/admin/categories/{category}` | `admin.categories.update` | `CategoryController@update` |
| DELETE | `/admin/categories/{category}` | `admin.categories.destroy` | `CategoryController@destroy` |
| GET | `/admin/tags` | `admin.tags.index` | `TagController@index` |
| GET | `/admin/tags/create` | `admin.tags.create` | `TagController@create` |
| POST | `/admin/tags` | `admin.tags.store` | `TagController@store` |
| GET | `/admin/tags/{tag}/edit` | `admin.tags.edit` | `TagController@edit` |
| PUT | `/admin/tags/{tag}` | `admin.tags.update` | `TagController@update` |
| DELETE | `/admin/tags/{tag}` | `admin.tags.destroy` | `TagController@destroy` |
| GET | `/inscription` | `register` | `Auth\RegisterController@create` |
| POST | `/inscription` | `register.store` | `Auth\RegisterController@store` |
| GET | `/connexion` | `login` | `Auth\LoginController@create` |
| POST | `/connexion` | `login.store` | `Auth\LoginController@store` |
| POST | `/deconnexion` | `logout` | `Auth\LoginController@destroy` |
| GET | `/mes-articles` | `my-posts` | `PostController@mine` |
| GET | `/admin/utilisateurs` | `admin.users.index` | `UserController@index` |
| PATCH | `/admin/utilisateurs/{user}/role` | `admin.users.role` | `UserController@updateRole` |

## Comparaison avec le starter kit Livewire

| Critère | Mini-CMS (authentification manuelle) | Starter kit Livewire |
|---|---|---|
| Routes d'authentification | Écrites à la main dans `routes/web.php` (groupes `guest` et `auth`) | Fournies par Laravel Fortify (`/login`, `/register`, `/logout`...), pages en composants Livewire |
| Inscription | `Auth\RegisterController` + `RegisterRequest`, `Hash::make()` puis `Auth::login()` | Action Fortify `CreateNewUser` et page Livewire avec Flux |
| Connexion | `Auth\LoginController@store` : `Auth::attempt()`, `session()->regenerate()`, message neutre | Gérée par Fortify, même principe (tentative, régénération de la session) |
| Déconnexion | `LoginController@destroy` : `Auth::logout()`, `invalidate()`, `regenerateToken()` | Route `logout` de Fortify |
| Limitation des tentatives | Aucune | 5 tentatives par minute par e-mail et adresse IP (`FortifyServiceProvider`) |
| Mot de passe oublié | Absent | `Features::resetPasswords()` : e-mail de réinitialisation (journalisé avec `MAIL_MAILER=log`) |
| Vérification de l'e-mail | Absente | `Features::emailVerification()` et `MustVerifyEmail` sur `User` |
| Double authentification | Absente | `Features::twoFactorAuthentication()` (codes TOTP et codes de secours) |
| Rôles et autorisations | Colonne `role`, `User::isAdmin()`, middleware `EnsureUserIsAdmin`, Gates `manage-categories` et `manage-tags`, `PostPolicy` | Aucun rôle : à ajouter soi-même |
| Tests | `AuthTest`, `AuthorizationTest`, `AdminAccessTest`, `PostVisibilityTest` écrits à la main | Tests Pest fournis pour l'inscription, la connexion, la réinitialisation et la vérification |

Ce que je garderais du kit dans un vrai projet : la limitation des tentatives de connexion et la réinitialisation du mot de passe, parce qu'elles protègent les comptes et qu'elles sont faciles à mal écrire soi-même. Je garderais en revanche mes propres rôles, mon middleware `admin` et ma `PostPolicy`, puisque le kit ne gère pas les autorisations métier.

## Prérequis

- PHP et Composer (PHP 8.3 ou plus depuis https://www.php.net/downloads, avec les extensions curl, fileinfo, mbstring, openssl, pdo_sqlite, sqlite3 et zip activées dans php.ini, et Composer depuis https://getcomposer.org).
- Node.js LTS et npm.
- Git.

## Installation

### Bash (Git Bash, macOS, Linux)

```bash
git clone https://github.com/VOTRE-UTILISATEUR/mini-cms.git
cd mini-cms
composer install
npm install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate
composer run dev
```

### PowerShell (Windows)

```powershell
git clone https://github.com/VOTRE-UTILISATEUR/mini-cms.git
cd mini-cms
composer install
npm install
copy .env.example .env
php artisan key:generate
New-Item database\database.sqlite -ItemType File
php artisan migrate
composer run dev
```

Ouvrir ensuite http://localhost:8000 (adresse de l'application, différente de l'adresse de Vite sur le port 5173).

## Captures d'écran

#### Accueil avec le layout

![Page d'accueil avec le layout](screenshots/s03-accueil.png)

### Liste des articles

![Liste des articles](screenshots/s03-articles.png)

### Détail d'un article

![Détail d'un article](screenshots/s03-article.png)

### Session 05

![Liste paginée des articles](screenshots/s05-articles.png)

![Formulaire avec erreurs de validation](screenshots/s05-formulaire.png)

### Session 06

![Formulaire de modification prérempli](screenshots/s06-modification.png)

![Confirmation de suppression](screenshots/s06-suppression.png)

### Session 07

![Liste des articles avec leur catégorie, leur auteur et leurs tags](screenshots/s07-liste-relations.png)

![Page d'un article avec ses tags et les articles de la même catégorie](screenshots/s07-detail-tags.png)

### Session 08

![Formulaire de modification avec la catégorie et les tags](screenshots/s08-formulaire-relations.png)

![Page des articles d'une catégorie](screenshots/s08-page-categorie.png)

### Session 09

![Message flash après la création d'un article](screenshots/s09-message-flash.png)

![Page Article introuvable avec les articles récents](screenshots/s09-article-introuvable.png)

### Session 10

![Erreurs de validation en français dans le back-office](screenshots/s10-validation.png)

![Page 404 dans le layout du site](screenshots/s10-404.png)

![Back-office des catégories](screenshots/s10-categories.png)

### Session 11

![Connexion refusée avec le message neutre](screenshots/s11-connexion.png)

![Un auteur reçoit 403 sur le back-office des catégories](screenshots/s11-interdit.png)

### Session 12

![Un auteur reçoit 403 sur /admin/tags](screenshots/s12-admin-interdit.png)

![Menu d'un auteur sans Catégories ni Tags](screenshots/s12-menu-auteur.png)

![Back-office des utilisateurs (défi)](screenshots/s12-utilisateurs.png)
