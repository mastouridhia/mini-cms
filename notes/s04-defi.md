# Session 04 - Défi x-page-header

@props déclare title (obligatoire) et subtitle (par défaut null) : oui / non

Le paragraphe ne s'affiche que si subtitle est fourni : oui / non

Le composant remplace le h1 de a-propos.blade.php et de posts/index.blade.php : oui / non

Aucune URL écrite à la main : oui / non

Sur /posts/archive/2025, quel titre affiche x-page-header, et d'où vient-il ?

## Réponses

- `@props déclare title (obligatoire) et subtitle (par défaut null) : oui`.
- Le paragraphe n'apparaît que si `subtitle` est fourni : oui.
- Le composant remplace le h1 de `a-propos.blade.php` et de `posts/index.blade.php` : non. Dans le code actuel, `a-propos.blade.php` garde son propre `<h1>`, et `posts/index.blade.php` utilise bien `x-page-header`, mais pas les deux à la fois.
- Aucune URL écrite à la main : oui, toutes les URLs sont générées avec `route()` et les routes nommées.
- Sur `/posts/archive/2025`, le titre affiché est `Articles de 2025`. Il vient de `PostController::archive()`, qui passe `'heading' => 'Articles de '.$year` à la vue `posts.index`, puis `resources/views/posts/index.blade.php` injecte `:title="$heading"` dans le composant `x-page-header`.