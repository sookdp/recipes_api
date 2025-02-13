<?php
namespace App\Controller;

use App\Service\MistralRecipeGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RecipeController extends AbstractController
{
    #[Route('api/generate-recipes', name: 'generate_recipes', methods: ['GET','POST'])]
    public function generateRecipes(Request $request, MistralRecipeGenerator $recipeGenerator): JsonResponse
    {
        // Récupérer les prompts depuis la requête JSON
        $data = json_decode($request->getContent(), true);
        $prompts = $data['prompts'] ?? ['Donne-moi une recette de gâteau au chocolat', 'Donne-moi une recette de pizza'];

        try {
            $recipes = $recipeGenerator->generateMultipleRecipes($prompts);
            $recipesData = [];
            foreach ($recipes as $recipe) {
                $recipesData[] = [
                    'id' => $recipe->getId(),
                    'title' => $recipe->getTitle(),
                    'description' => $recipe->getDescription(),
                    'ingredients' => $recipe->getIngredients(),
                    'steps' => $recipe->getSteps(),
                ];
            }

            return $this->json($recipesData);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }
}
