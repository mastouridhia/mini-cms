# Session 05 - Défi : articles récents sur l'accueil

latestPosts contient au plus 3 articles : oui / non
Seuls les articles publiés sont transmis à la vue : oui / non
home.blade.php utilise x-post-card et @forelse : oui / non
Le nouveau test passe et php artisan test est vert : oui / non

Pourquoi get() et pas paginate(3) sur la page d'accueil ?

## Réponses

- `latestPosts` contient au plus 3 articles : oui.
- Seuls les articles publiés sont transmis à la vue : oui, via `Post::published()`.
- `home.blade.php` utilise `x-post-card` et `@forelse` : oui.
- Le nouveau test passe et `php artisan test` est vert : oui, dans ce dépôt les tests ciblés passent.
- On utilise `get()` et non `paginate(3)` parce que la page d'accueil affiche un résumé limité de 3 derniers articles, pas une liste paginée complète. Le but est d'afficher un aperçu court et rapide, sans navigation de pagination ni vue dédiée. La limite est imposée par `take(3)` dans le contrôleur.