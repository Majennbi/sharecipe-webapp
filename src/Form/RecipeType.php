<?php

namespace App\Form;

use App\Entity\Recipe;
use App\Entity\Ingredient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Nom de l\'ingrédient',
                    'minlength' => '2',
                    'maxlength' => '50',
                ],

                'label' => 'Nom de l\'ingrédient',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length([
                        'min' => 2,
                        'max' => 50,
                    ]),
                ]
            ]) 
            ->add('time', IntegerType::class, [
            'attr' => [
                'class' => 'form-control',
                'placeholder' => 'Temps de préparation',
                'min' => '1',
                'max' => '1440',
            ],
            'label' => 'Temps de préparation (en minutes)',
            'label_attr' => [
                'class' => 'form-label mt-4'
            ],
            'required' => false,
            'constraints' => [
                new Assert\Positive(),
                new Assert\LessThan([
                    'value' => 1441,
                    'message' => 'Le temps ne peut pas être supérieur à 24h'
                ]),
            ]])

            ->add( 'servings', IntegerType::class, [
            'attr' => [
                'class' => 'form-control',
                'placeholder' => 'Nombre de personnes',
                'min' => '1',
                'max' => '50',
            ],
            'label' => 'Nombre de personnes',
            'label_attr' => [
                'class' => 'form-label mt-4'
            ],
            'required' => false,
            'constraints' => [
                new Assert\Positive(),
                new Assert\LessThan([
                    'value' => 51,
                    'message' => '50 personnes maximum'
                ]),
            ]
        ])
            ->add('level', ChoiceType::class, [
                'choices' => [
                    'Facile' => 'Facile',
                    'Moyen' => 'Moyen',
                    'Difficile' => 'Difficile',
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Difficulté',
                    'minlength' => '2',
                    'maxlength' => '50',
                ],
                'required' => false,
                'label' => 'Difficulté',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\Length([
                        'min' => 2,
                        'max' => 50,
                    ]),
                ]
            ])

            ->add('description', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Description de la recette',
                    'minlength' => '2',
                    'maxlength' => '500',
                ],
                'label' => 'Description de la recette',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\Length([
                        'min' => 2,
                        'max' => 500,
                    ]),
                ]
            ])
            ->add('isFavorite', CheckboxType::class, [
                'attr' => [
                    'class' => 'form-check-input mt-4',
                    'placeholder' => 'Favoris',
                ],
                'label' => 'Favoris',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'required' => false,
            ])
            ->add('ingredients', EntityType::class, [
                'class' => Ingredient::class,
                'choice_label' => 'name',
                'attr' => [
                    'class' => 'form-control',
                ],
                'multiple' => true,
                'expanded' => true,
                'label' => 'Ingrédients',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                ]
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary mt-4',
                ],
                'label' => 'Créer ma recette',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
