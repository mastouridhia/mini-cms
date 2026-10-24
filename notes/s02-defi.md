# Session 02 - Défi : page À propos

## Questions

Le contrôleur `PageController@about` envoie-t-il deux variables à la vue : oui / non

La vue affiche-t-elle `Mastouri Dhia` et `MDW33` : oui / non

La route de la page est-elle nommée `about` : oui / non

Le titre de page est-il bien `À propos - Mini-CMS` dans le layout : oui / non

## Réponses

- Le contrôleur `PageController@about` envoie bien deux variables à la vue : oui. On trouve `auteur` et `groupe` dans `app/Http/Controllers/PageController.php`.
- La vue affiche bien `Mastouri Dhia` et `MDW33` : oui. Ces valeurs sont injectées dans `resources/views/a-propos.blade.php`.
- La route de la page est bien nommée `about` : oui. Elle est déclarée dans `routes/web.php` avec `->name('about')`.
- Le titre de page est bien `À propos - Mini-CMS` : oui. La vue utilise `x-slot:title` et le layout affiche ce titre dans la balise `<title>`.
