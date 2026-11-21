# Session 10 - Robustesse

## 1. Pourquoi ne trouve-t-on ni validation ni try/catch dans les contrôleurs ?

La validation vit dans les Form Requests (`StoreCategoryRequest`, `UpdateTagRequest`...). Laravel les exécute avant d'appeler la méthode du contrôleur : si une règle échoue, il redirige vers le formulaire avec les erreurs et l'ancienne saisie, et le corps de `store()` ne s'exécute jamais. Les erreurs « normales » (404 du route model binding, 503 du middleware `writable`) sont des exceptions HTTP que le gestionnaire d'exceptions transforme en pages `errors/404.blade.php` et `errors/503.blade.php`. Un try/catch dans le contrôleur cacherait ces mécanismes et dupliquerait le travail du framework.

## 2. Dans quel ordre Laravel cherche-t-il un message de validation ?

1. La méthode `messages()` de la Form Request (`slug.unique` : « Ce slug est déjà utilisé par une autre catégorie. »).
2. Le fichier `lang/fr/validation.php`, parce que `APP_LOCALE=fr`. Les noms de champs viennent du tableau `attributes` (`name` devient « nom »).
3. Le message anglais du framework (`APP_FALLBACK_LOCALE=en`) si la clé manque. Un message resté en anglais signale donc une clé absente, pas une panne.

## 3. Que devient un article quand on supprime sa catégorie ? Et un tag ?

La clé `category_id` a été créée avec `nullOnDelete()` : l'article reste, sans catégorie (test « keeps the posts when a category is deleted »). Pour un tag, la table pivot `post_tag` utilise `cascadeOnDelete()` : seules les lignes de liaison disparaissent, les articles restent (test « deletes a tag but keeps its posts »).
