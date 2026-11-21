# Session 09 - Défi

Le bandeau « Site en lecture seule » s'affiche quand SITE_READ_ONLY=true : oui / non
La page « Article introuvable » liste au plus 3 articles publiés : oui / non
Les deux nouveaux tests de PostFeedbackTest passent : oui / non

## Réponses

- Bandeau : oui. Le layout lit `config('site.read_only')` et affiche `<x-alert type="warning">`. Le même réglage est lu par le middleware `EnsureSiteIsWritable`, donc le bandeau et le blocage ne peuvent pas se contredire.
- Articles récents : oui. Le callback `render` de `bootstrap/app.php` passe `Post::published()->latest('published_at')->take(3)->get()` à la vue : la vue ne fait aucune requête et les brouillons ne sont jamais proposés.
- Tests : oui, `PostFeedbackTest` compte 6 tests verts.
- Pourquoi lire `config()` et pas `env()` : après `php artisan config:cache`, `env()` renvoie `null` hors des fichiers de configuration. `config/site.php` est donc le seul endroit qui lit `SITE_READ_ONLY`.
- Pourquoi 503 et pas 403 : le refus ne dépend pas de l'utilisateur, c'est le service qui est temporairement indisponible en écriture.
