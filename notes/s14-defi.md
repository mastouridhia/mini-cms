# Session 14 - Défi

La requête Connexion enregistre le jeton dans la variable token : oui / non
Créer un article renvoie 201 sans coller de jeton à la main : oui / non
L'export docs/postman/mini-cms.postman_collection.json ne contient aucun jeton : oui / non

## Réponses

- Connexion : oui. Le script après réponse fait `pm.collectionVariables.set('token', pm.response.json().token)`.
- Créer un article : oui, la requête utilise l'autorisation Bearer `{{token}}`. La collection a été exécutée de bout en bout avec Newman (le lanceur en ligne de commande de Postman) : 4 requêtes, 3 assertions, 0 échec.
- Export : oui. La variable `token` est vide dans le fichier, et la requête Déconnexion la vide aussi après usage.

## Que risque-t-on si un jeton fuite, et comment le révoquer ?

Un jeton Sanctum vaut le mot de passe pour l'API : celui qui le possède peut créer, modifier et supprimer les articles du compte, sans connaître le mot de passe et sans limite de durée (aucune expiration n'est configurée). Pour le révoquer : appeler `POST /api/logout` avec ce jeton (`currentAccessToken()->delete()`), ou supprimer la ligne dans `personal_access_tokens` (`$user->tokens()->delete()` supprime tous les jetons du compte). C'est pour cela qu'aucune capture d'écran ni aucun fichier versionné ne doit montrer un jeton.

## Pourquoi les écritures de l'API passent-elles par les Form Requests et la PostPolicy ?

Le site et l'API appliquent ainsi exactement les mêmes règles : `StorePostRequest` et `UpdatePostRequest` valident les données (422 en français), et `PostPolicy` décide qui peut modifier ou supprimer (403). Une règle écrite une seule fois ne peut pas diverger entre le site et l'API, et le contrôleur de l'API reste court. Si les règles étaient dans le contrôleur, il faudrait les recopier, et une correction faite d'un côté serait oubliée de l'autre.
