# Session 12 - Défi

route:list --middleware=admin affiche 14 routes : oui / non
Un admin qui change son propre rôle reçoit 403 : oui / non
git grep -n isAdmin -- app/Policies/PostPolicy.php n'affiche rien : oui / non
UserRoleTest compte 2 tests verts et php artisan test est vert : oui / non

## Réponses

- 14 routes : oui (12 routes de catégories et de tags, plus `admin.users.index` et `admin.users.role`).
- Son propre rôle : oui. `UpdateUserRoleRequest::authorize()` appelle la règle `changeRole` de `UserPolicy`, qui refuse quand l'admin vise son propre compte. Le refus est un vrai 403 côté serveur, pas un bouton caché.
- `isAdmin` dans `PostPolicy` : non présent. Les admins passent par `Gate::before` dans `AppServiceProvider`.
- Tests : oui.

## 1. Pourquoi `role` n'est-il pas dans `#[Fillable]`, et comment le contrôleur le modifie-t-il quand même ?

Si `role` était « fillable », un visiteur pourrait ajouter un champ caché `role=admin` au formulaire d'inscription : `User::create($request->all())` ou un `fill()` trop large le copierait et créerait un administrateur. Hors de `#[Fillable]`, l'affectation de masse l'ignore. Le contrôleur, lui, affecte l'attribut explicitement (`$user->role = $request->validated('role')`) puis appelle `save()`, après l'autorisation (`changeRole`) et la validation (`in:author,admin`).

## 2. Pourquoi `Gate::before` doit-il renvoyer `null` et pas `false` ?

`Gate::before` s'exécute avant toutes les règles. Une valeur non nulle est la décision finale : `true` autorise, `false` refuse. Renvoyer `false` pour un auteur lui interdirait tout, même de modifier ses propres articles, car `PostPolicy` ne serait jamais consultée. `null` signifie « je ne décide pas » : Laravel continue alors vers le Gate ou la Policy. Le callback renvoie aussi `null` pour l'action `changeRole`, sinon un admin passerait toujours et pourrait changer son propre rôle.
