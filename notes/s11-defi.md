# Session 11 - Défi

/mes-articles n'affiche que mes articles, brouillons compris : oui / non
Un visiteur qui ouvre /mes-articles est redirigé vers /connexion : oui / non
La case « Se souvenir de moi » crée le cookie remember_web_... : oui / non
AuthorizationTest compte 10 tests verts : oui / non

## Réponses

- Mes articles : oui. La requête part de `$request->user()->posts()` : elle ne peut pas renvoyer l'article d'un autre auteur, et elle ne passe pas par `published()`, donc les brouillons de l'auteur apparaissent.
- Visiteur : oui. La route `my-posts` est dans le groupe `auth`, qui redirige vers la route nommée `login`.
- Se souvenir de moi : oui. La case est lue avec `$request->boolean('remember')` et passée en second argument de `Auth::attempt()`. Elle ne fait pas partie du tableau d'identifiants, sinon Laravel chercherait une colonne `remember` dans `users`.
- Tests : oui, 10 tests verts dans `AuthorizationTest`.
- Pourquoi `session()->regenerate()` après la connexion : on change l'identifiant de session pour bloquer la fixation de session (un identifiant connu d'un attaquant avant la connexion ne sert plus à rien après).
- Pourquoi le même message pour un e-mail inconnu et un mauvais mot de passe : sinon le formulaire révèle quelles adresses ont un compte (énumération de comptes).
