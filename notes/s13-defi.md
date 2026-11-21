# Session 13 - Défi

Chaque article de /api/posts a une clé tags, sans LazyLoadingViolationException : oui / non
La clé tags disparaît quand les tags ne sont pas chargés : oui / non
La clé body n'apparaît que dans /api/posts/{post} : oui / non

## Réponses

- Clé `tags` : oui. `PostResource` utilise `$this->whenLoaded('tags', fn () => $this->tags->pluck('name'))` et le contrôleur charge `tags` dans `index()` (`with(...)`) et dans `show()` (`load(...)`). Aucune requête n'est lancée depuis la ressource.
- Clé absente sans chargement : oui. Sans `tags` dans `with()`, `whenLoaded` renvoie une valeur manquante et la clé n'est pas sérialisée.
- Clé `body` : oui, grâce à `$this->when($request->routeIs('api.posts.show'), $this->body)`.

## Pourquoi la liste n'envoie-t-elle pas le contenu (`body`) ?

Une liste sert à choisir un article : titre, extrait, auteur et catégorie suffisent. Envoyer le texte complet de chaque article alourdirait chaque page de résultats (réseau, mémoire du client mobile) pour un contenu que l'utilisateur ne lira pas. Le contenu est envoyé seulement quand on ouvre un article.

## Pourquoi un brouillon renvoie-t-il 403 alors qu'un 404 cacherait son existence ?

Un 403 confirme qu'un article existe à cet identifiant, ce qui peut révéler une information (un article en préparation). Un 404 masquerait cette existence. L'API garde ici 403 parce qu'elle réutilise la `PostPolicy` du site, mais pour un contenu sensible on pourrait préférer répondre 404.
