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
