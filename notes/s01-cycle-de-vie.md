# Session 01 - Cycle de vie d'une requête

## Réponses

1. Une requête HTTP arrivant sur `/a-propos` est d'abord traitée par `routes/web.php`.
2. Laravel retrouve la route nommée `about` et exécute `PageController@about`.
3. Le contrôleur retourne une vue Blade avec des variables PHP : `auteur => 'Mastouri Dhia'` et `groupe => 'MDW33'`.
4. La vue `resources/views/a-propos.blade.php` reçoit ces variables, affiche le contenu HTML et s'appuie sur le layout global `<x-layout>`.
5. La réponse finale est renvoyée au navigateur : page HTML complète, avec le titre "À propos - Mini-CMS" et les infos du projet.

En résumé, le flux est : route -> contrôleur -> vue Blade -> réponse HTTP.
