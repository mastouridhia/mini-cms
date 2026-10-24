# Session 06 - Défi : modification et suppression d'un article

## Questions

Le formulaire d'édition est-il prérempli avec les données actuelles : oui / non

Le slug reste-t-il inchangé lorsqu'on met à jour un article sans le modifier : oui / non

La suppression affiche-t-elle une confirmation claire avant la destruction : oui / non

Le nouveau test passe-t-il et `php artisan test` est-il vert : oui / non

Quel est le rôle de `route('posts.update', $post)` dans le formulaire d'édition ?

## Réponses

- Le formulaire d'édition est bien prérempli avec les données actuelles : oui. Le composant `x-post-form` reçoit `$post`, et la vue `resources/views/posts/edit.blade.php` affiche ses valeurs dans les champs.
- Le slug reste inchangé si on modifie uniquement le titre : oui. La logique de validation conserve le slug actuel grâce à `Rule::unique('posts', 'slug')->ignore($post)` dans `PostController@update()`.
- La suppression affiche bien une confirmation claire avant la destruction : oui. Dans `resources/views/posts/show.blade.php`, le bouton est inclus dans un `<details>` avec le message `Cette action est définitive : l'article sera effacé de la base.`
- Le nouveau test passe et la suite est verte : oui. Le test de modification/suppression valide le comportement attendu dans le dépôt.
- `route('posts.update', $post)` génère l'URL de soumission du formulaire de mise à jour : elle pointe vers la route `posts.update` avec l'article courant comme paramètre. `route('posts.destroy', $post)` fait de même pour la suppression, mais avec la méthode HTTP `DELETE` et le formulaire de confirmation associé.
