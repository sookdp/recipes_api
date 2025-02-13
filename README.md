# Recipes API

---
C'est l'API de gestion des recettes de cuisine. Elle permet de consulter, ajouter aux favoris et commenter les recettes.

---
## 1. Clonage du dépôt

```bash
git clone https://github.com/sookdp/recipes_api.git
cd recipes_api
```

## 2. Installation des dépendances

```bash
composer install
```

## 3. Configuration de la base de données

Remplacer les valeurs des variables d'environnement dans le fichier `.env` par les votres.

```bash
DATABASE_URL="mysql://root:root123@127.0.0.1:3306/recipesapi?serverVersion=8.0.32&charset=utf8mb4"
```

## 5. Démarage du serveur

```bash
symfony serve
```

## 6. Informations sur le serveur:
J'ai voulu utiliser l'IA pour générer les recettes et malheureusement je n'ai pas eu le temps l'afficher dans mon site. Mais voici un exemple de requête pour générer une recette de gâteau au chocolat qui fonctionne une l'API démarré.
```bash
curl -X POST https://api.mistral.ai/v1/chat/completions \
    -H "Authorization: Bearer ZvjOPsVoef5I4rooUSMb15ErTJi7FFwO" \
    -H "Content-Type: application/json" \
    -d '{
        "model": "open-mistral-7b", 
        "messages": [
            {"role": "system", "content": "Tu es un chef cuisinier qui génère des recettes détaillées."},
            {"role": "user", "content": "Donne-moi une recette de gâteau au chocolat"}
        ],
        "temperature": 0.7,
        "max_tokens": 500
    }'
```
