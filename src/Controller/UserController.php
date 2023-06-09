<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\UserPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    /**
     * This controller is used to edit a user
     * 
     * @param User $selectedUser
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Security('is_granted("ROLE_USER") and user === selectedUser', message: "Vous n'avez pas accès à cette page")]
    #[Route('/user/edit/{id}', name: 'user.edit', methods: ['GET', 'POST'])]
    public function edit(
        User $selectedUser,
        Request $request,
        EntityManagerInterface $manager,
        UserPasswordHasherInterface $hasher
    ): Response {

        $form = $this->createForm(UserType::class, $selectedUser);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($hasher->isPasswordValid($selectedUser, $form->getData()->getPlainPassword())) {

                $user = $form->getData();

                $manager->persist($user);
                $manager->flush();

                $this->addFlash('success', 'Votre profil a bien été modifié !');

                return $this->redirectToRoute('home');
            } else {
                $this->addFlash('danger', 'le mot de passe est incorrect !');
            }
        }
        return $this->render('pages/user/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * This controller is used to edit a user's password
     * @param User $selectedUser
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param UserPasswordHasherInterface $hasher
     * @return Response
     */
    #[Security('is_granted("ROLE_USER") and user === selectedUser', message: "Vous n'avez pas accès à cette page")]
    #[Route('/user/edit-password/{id}', name: 'user.edit.password', methods: ['GET', 'POST'])]
    public function editPassword(User $selectedUser, Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $hasher): Response
    {
        $form = $this->createForm(UserPasswordType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($hasher->isPasswordValid($selectedUser, $form->getData()['plainPassword'])) {
                $selectedUser->setUpdatedAt(new \DateTimeImmutable());
                $selectedUser->setPlainPassword(

                    $form->getData()['newPassword']
                );

                $this->addFlash(
                    'success',
                    'Le mot de passe a été modifié.'
                );

                $manager->persist($selectedUser);
                $manager->flush();

                return $this->redirectToRoute('home');
            } else {
                $this->addFlash(
                    'warning',
                    'Le mot de passe renseigné est incorrect.'
                );
            }
        }

        return $this->render('pages/user/edit_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
