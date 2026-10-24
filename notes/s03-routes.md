# Session 03 - Routes, contrôleurs et layout

## Défi

URL produite par route('about') :

URL produite par route('posts.show', 'routes-et-controleurs') :

URL produite par route('posts.archive', 2025) :

Pourquoi /posts/archive/abc et /posts/article-inconnu répondent toutes deux 404, et quelle ligne de code en est responsable dans chaque cas ?

Que se passe-t-il si une vue appelle route('posts.show') sans paramètre ?

## Réponses

- `route('about')` donne `/a-propos`.
- `route('posts.show', 'routes-et-controleurs')` donne `/posts/routes-et-controleurs`.
- `route('posts.archive', 2025)` donne `/posts/archive/2025`.
- `/posts/archive/abc` 404 car la route est déclarée avec `->whereNumber('year')` dans `routes/web.php`. Laravel refuse toute valeur non numérique pour le paramètre `year` avant même d'entrer dans le contrôleur.
- `/posts/article-inconnu` 404 car la ressource `posts.show` utilise le binding de modèle Eloquent (`public function show(Post $post): View` dans `PostController`) : si aucun article correspondant n'est trouvé, Laravel renvoie automatiquement une réponse 404.
- Si une vue appelle `route('posts.show')` sans paramètre, Laravel lève une exception `Missing required parameter` car la route attend un article.