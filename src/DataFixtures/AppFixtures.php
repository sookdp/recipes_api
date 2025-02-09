<?php

namespace App\DataFixtures;

use App\Entity\Recipe;
use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Ajout de 3 recettes
        $recipes = [];

        $recipe1 = new Recipe();
        $recipe1->setTitle("Tajine de Poulet aux Olives")
            ->setDescription("Un plat traditionnel marocain aux épices et aux olives.")
            ->setIngredients(["Poulet", "Olives", "Citron confit", "Oignons", "Épices"])
            ->setSteps(["Faire revenir les oignons", "Ajouter le poulet et les épices", "Laisser mijoter", "Ajouter les olives et citron confit"]);
        $manager->persist($recipe1);
        $recipes[] = $recipe1;

        $recipe2 = new Recipe();
        $recipe2->setTitle("Sushi Maki Saumon")
            ->setDescription("Un plat japonais emblématique à base de riz et de saumon frais.")
            ->setIngredients(["Riz à sushi", "Saumon", "Feuille de Nori", "Vinaigre de riz"])
            ->setSteps(["Cuire le riz", "Assaisonner avec du vinaigre", "Rouler les makis", "Découper en tranches"]);
        $manager->persist($recipe2);
        $recipes[] = $recipe2;

        $recipe3 = new Recipe();
        $recipe3->setTitle("Pasta Carbonara")
            ->setDescription("Un grand classique italien avec des œufs, du fromage et du lard.")
            ->setIngredients(["Pâtes", "Lardons", "Œufs", "Parmesan", "Poivre noir"])
            ->setSteps(["Cuire les pâtes", "Faire revenir les lardons", "Mélanger avec les œufs et le fromage", "Servir immédiatement"]);
        $manager->persist($recipe3);
        $recipes[] = $recipe3;

        // Ajout de commentaires
        foreach ($recipes as $index => $recipe) {
            for ($i = 1; $i <= 2; $i++) { // 2 commentaires par recette
                $comment = new Comment();
                $comment->setContent("Délicieuse recette, je recommande !")
                    ->setRating(rand(3, 5)) // Note aléatoire entre 3 et 5
                    ->setRecipe($recipe)
                    ->setUser(rand(13, 22)); // Simule des utilisateurs différents
                $manager->persist($comment);
            }
        }

        // Enregistrer en base de données
        $manager->flush();
    }
}
