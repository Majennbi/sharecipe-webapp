<?php

namespace App\Form;

use App\Entity\Recipe;
use App\Entity\Ingredient;
use App\Repository\IngredientRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\FormBuilderInterface;

use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class RecipeType extends AbstractType
{
    private $token;

    public function __construct(TokenStorageInterface $token)
    {
        $this->token = $token;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Nom de la recette',
                    'minlength' => '2',
                    'maxlength' => '50',
                ],

                'label' => 'Nom de la recette',
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

            ->add('imageFile', VichImageType::class, [
                'required' => false,
                'allow_delete' => true,
                'download_uri' => true,
                'image_uri' => true,
                'label' => 'Image de la recette',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Image de la recette',
                ],
                'constraints' => [
                    new Assert\Image([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                        ],
                        'mimeTypesMessage' => 'Veuillez uploader une image valide',
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
                'query_builder' => function (IngredientRepository $r) {
                    return $r->createQueryBuilder('i')
                        ->where('i.user = :user')
                        ->orderBy('i.name', 'ASC')
                        ->setParameter('user', $this->token->getToken()->getUser());
                },
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
