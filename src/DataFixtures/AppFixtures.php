<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Mark;
use App\Entity\User;
use Faker\Generator;
use App\Entity\Recipe;
use App\Entity\Ingredient;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

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


        //Users 
        $users = [];
        for ($l = 0; $l < 10; $l++) {
            $user = new User();
            $user->setFullname($this->faker->Name());
            $user->setPseudo(mt_rand(0, 1) === 1 ? $this->faker->userName() : null);
            $user->setEmail($this->faker->email());
            $user->setPassword('password');
            $user->setRoles(['ROLE_USER']);
            $user->setPlainPassword('password');

            $users[] = $user;
            $manager->persist($user);
        }


        //Ingredients
        $ingredients = [];
        for ($i = 0; $i < 20; $i++) {
            $ingredient = new Ingredient();
            $ingredient->setName($this->faker->word());
            $ingredient->setPrice(mt_rand(1, 100));
            $ingredient->setUser($users[mt_rand(0, count($users) - 1)]);
            $ingredients[] = $ingredient;

            $manager->persist($ingredient);
        }


        //Recipes
        $recipes = [];
        for ($j = 0; $j < 20; $j++) {

            $recipe = new Recipe();
            $recipe->setName($this->faker->word());
            $recipe->setTime(mt_rand(0, 1) == 1 ? mt_rand(1, 1440) : null);
            $recipe->setServings(mt_rand(1, 50));
            $recipe->setLevel('facile');
            $recipe->setDescription($this->faker->paragraph());
            $recipe->setIsFavorite(mt_rand(0, 1) == 1 ? true : false);
            $recipe->setIsPublic(mt_rand(0, 1) == 1 ? true : false);
            $recipe->setUser($users[mt_rand(0, count($users) - 1)]);

            for ($k = 0; $k < 20; $k++) {
                $recipe->addIngredient($ingredients[mt_rand(0, count($ingredients) - 1)]);
            }
            $recipes[] = $recipe;
            $manager->persist($recipe);
        }

        // Marks
        foreach ($recipes as $recipe) {
            for ($m = 0; $m < mt_rand(0, 4); $m++) {
                $mark = new Mark();
                $mark->setUser($users[mt_rand(0, count($users) - 1)]);
                $mark->setRecipe($recipe);
                $mark->setMark(mt_rand(1, 5));

                $manager->persist($mark);
            }
        }

        $manager->flush();
    }
}
