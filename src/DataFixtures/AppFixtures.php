<?php

namespace App\DataFixtures;

use Faker\Factory;
use Faker\Generator;
use App\Entity\Recipe;
use App\Entity\Ingredient;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    /** 
     * @var Generator 
     * */

    private Generator $faker;

    public function __construct() 
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        //Ingredients
        $ingredients = [];
        for ($i = 0; $i < 20; $i++) {
            $ingredient = new Ingredient();
            $ingredient->setName($this->faker->word());
            $ingredient->setPrice(mt_rand(1, 100));
            $ingredients[] = $ingredient;

            $manager->persist($ingredient);
        }


    //Recipes
    for ($j = 0; $j < 20; $j++) {
        $recipe = new Recipe();
        $recipe->setName($this->faker->word());
        $recipe->setTime(mt_rand(0, 1) == 1 ? mt_rand(1, 1440) : null);
        $recipe->setServings(mt_rand(1, 50));
        $recipe->setLevel($this->faker->word());
        $recipe->setDescription($this->faker->paragraph());
        $recipe->setIsFavorite(mt_rand(0, 1) == 1 ? true : false);

            for ($k = 0; $k < 20; $k++) {
                $recipe->addIngredient($ingredients[mt_rand(0, count($ingredients) - 1)]);
            }
            $manager->persist($recipe);
        }
        $manager->flush();
    }     
}
