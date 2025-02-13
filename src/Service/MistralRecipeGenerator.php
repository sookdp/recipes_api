<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Recipe;

class MistralRecipeGenerator
{
    private HttpClientInterface $httpClient;
    private string $mistralApiKey;
    private EntityManagerInterface $entityManager;

    public function __construct(HttpClientInterface $httpClient, string $mistralApiKey, EntityManagerInterface $entityManager)
    {
        $this->httpClient = $httpClient;
        $this->mistralApiKey = $mistralApiKey;
        $this->entityManager = $entityManager;
    }

    public function generateRecipe(string $prompt): Recipe
    {
        // Envoi de la requête API
        $response = $this->httpClient->request('POST', 'https://api.mistral.ai/v1/chat/completions', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->mistralApiKey,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'model' => 'open-mistral-7b',
                'messages' => [
                    ['role' => 'system', 'content' => 'Tu es un chef cuisinier qui génère des recettes détaillées.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'temperature' => 0.7,
                'max_tokens' => 500,
            ],
        ]);

        // Décodage de la réponse de l'API
        $data = $response->toArray();
        dump($data); // Affichage de la réponse brute pour déboguer

        // Vérification de la structure de la réponse
        $recipeContent = $data['choices'][0]['message']['content'] ?? '';
        if (empty($recipeContent)) {
            throw new \Exception("Réponse de Mistral invalide");
        }

        // Extraction des données de la réponse sous forme de texte
        // Supposons que la réponse contient un format de recette bien structuré
        $recipeDetails = $this->parseRecipeDetails($recipeContent);

        // Création de l'entité Recipe
        $recipe = new Recipe();
        $recipe->setTitle($recipeDetails['title'] ?? 'Recette sans titre');
        $recipe->setDescription($recipeDetails['description'] ?? '');
        $recipe->setIngredients($recipeDetails['ingredients'] ?? []);
        $recipe->setSteps($recipeDetails['steps'] ?? []);
        $recipe->setLikes(0);
        $recipe->setFavorites(0);

        // Sauvegarde dans la base de données
        $this->entityManager->persist($recipe);
        $this->entityManager->flush();

        return $recipe;
    }

    // Méthode pour analyser la réponse de Mistral et extraire les données de la recette
    private function parseRecipeDetails(string $recipeContent): array
    {
        // Extraction basique (exemple simplifié)
        // Supposons que la réponse Mistral est structurée sous forme de texte comme suit :
        // Titre : Gâteau au chocolat
        // Description : Un délicieux gâteau au chocolat.
        // Ingrédients : farine, sucre, etc.
        // Étapes : 1. Mélanger les ingrédients...

        $recipeDetails = [];

        // Titre de la recette
        if (preg_match('/Titre : (.*?)(?=\n|$)/', $recipeContent, $matches)) {
            $recipeDetails['title'] = $matches[1];
        }

        // Description de la recette
        if (preg_match('/Description : (.*?)(?=\n|$)/', $recipeContent, $matches)) {
            $recipeDetails['description'] = $matches[1];
        }

        // Ingrédients
        if (preg_match('/Ingrédients : (.*?)(?=\n|$)/', $recipeContent, $matches)) {
            $recipeDetails['ingredients'] = explode(', ', $matches[1]);
        }

        // Étapes de la recette
        if (preg_match('/Étapes : (.*?)(?=\n|$)/', $recipeContent, $matches)) {
            $recipeDetails['steps'] = explode('. ', $matches[1]);
        }

        return $recipeDetails;
    }
}
