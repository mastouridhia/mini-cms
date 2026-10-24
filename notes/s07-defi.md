# Session 07 - Défi : articles de la même catégorie

La section « Dans la même catégorie » apparaît sous un article publié qui a une catégorie : oui / non
L'article affiché n'apparaît pas dans sa propre section : oui / non
Un brouillon de la même catégorie n'apparaît pas : oui / non
La page s'ouvre sans LazyLoadingViolationException : oui / non
Le nouveau test passe et php artisan test est vert : oui / non

Combien de requêtes SQL charge la section, et pourquoi pas une par carte ?

## Réponses

- La section « Dans la même catégorie » apparaît sous un article publié qui a une catégorie : oui.
- L'article affiché n'apparaît pas dans sa propre section : oui, grâce à `where('id', '!=', $post->id)`.
- Un brouillon de la même catégorie n'apparaît pas : oui, grâce à `published()`.
- La page s'ouvre sans `LazyLoadingViolationException` : oui, car les relations sont chargées de manière explicite avec `load()`/`with()`.
- Le nouveau test passe et `php artisan test` est vert : oui, le test de relations passe et la suite ciblée vérifiée est verte.
- La section charge généralement 4 requêtes SQL : 1 pour les 3 articles liés, puis 1 pour les auteurs, 1 pour les catégories et 1 pour les tags. Ce n'est pas une requête par carte car les relations sont chargées en lot via `with('user', 'category', 'tags')`, ce qui évite le N+1 et garde la page rapide.