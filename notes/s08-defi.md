# Session 08 - Défi

La route posts.tag utilise {tag:slug} : oui / non
La page d'un tag n'affiche ni brouillon ni article d'un autre tag : oui / non
Les tags des cartes sont des liens : oui / non
L'accueil n'affiche que les tags qui ont au moins un article publié : oui / non
Les trois nouveaux tests passent et php artisan test est vert : oui / non

Pourquoi filtrer les tags vides après get() plutôt que d'afficher (0) ?
Combien de requêtes ajoute la liste des tags populaires, et pourquoi pas une par tag ?

## Réponses

- La route posts.tag utilise `{tag:slug}` : oui, `/posts/tag/{tag:slug}` appelle `PostController@byTag`.
- La page d'un tag n'affiche ni brouillon ni article d'un autre tag : oui, la requête part de `$tag->posts()` et passe par `published()`.
- Les tags des cartes sont des liens : oui, chaque tag de `x-post-card` pointe vers `route('posts.tag', $tag)`.
- L'accueil n'affiche que les tags qui ont au moins un article publié : oui, grâce à `filter(fn (Tag $tag) => $tag->posts_count > 0)`. Le titre est caché si la liste est vide.
- Les trois nouveaux tests passent et `php artisan test` est vert : oui.
- Un tag à (0) mènerait vers une page vide : il n'aide pas le visiteur, donc on ne l'affiche pas. Filtrer la collection après `get()` reste simple et évite d'ajouter une condition SQL à la requête.
- La liste ajoute une seule requête : `withCount` place une sous-requête de comptage dans la requête des tags, et la base calcule le nombre pour tous les tags en même temps. Aucun article n'est chargé, donc il n'y a pas une requête par tag (pas de N+1).
