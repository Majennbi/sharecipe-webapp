<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullName', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlenght' => 2,
                    'maxlength' => 50
                ],
                'label' => 'Nom / Prénom',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length([
                        'min' => 2,
                        'minMessage' => 'Votre nom et prénom doit faire au moins {{ limit }} caractères',
                        'max' => 50,
                        'maxMessage' => 'Votre nom et prénom ne peut pas faire plus de {{ limit }} caractères'
                    ])
                ]

            ])
            ->add('pseudo', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlenght' => 2,
                    'maxlength' => 50
                ],
                'label' => 'Pseudo',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'required' => false,
                'constraints' => [
                    new Assert\Length([
                        'min' => 2,
                        'minMessage' => 'Votre pseudo doit faire au moins {{ limit }} caractères',
                        'max' => 50,
                        'maxMessage' => 'Votre pseudo ne peut pas faire plus de {{ limit }} caractères'
                    ])
                ]

            ])

            ->add('plainPassword', PasswordType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlenght' => 6,
                    'maxlength' => 50
                ],
                'label' => 'Mot de passe',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
               
            ])
        
            ->add('submit', SubmitType::class, [
                'label' => 'Modifier',
                'attr' => [
                    'class' => 'btn btn-primary mt-4'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
