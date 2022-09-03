<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use DateTimeImmutable;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bridge\Doctrine\ManagerRegistry as DoctrineManagerRegistry;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UserController extends AbstractController
{
    #[Route('/admin/users', name: 'admin_users')]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/userList.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/admin/user/create', name: 'create_user')]
    public function new(ManagerRegistry $managerRegistry, Request $request, UserPasswordHasherInterface $userPasswordHasher, UserRepository $userRepository): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $users = $userRepository->findAll();
            $userFirstNames = [];
            $userLastNames = [];
            $userRoles = [];
            $userPassword = [];
            foreach ($users as $newUser) {
                $userFirstNames[] = $newUser->getFirstName();
                $userLastNames[] = $newUser->getLastName();
                $userRoles[] = $newUser->getRoles();
                $userPassword[] = $newUser->getPassword();
            }

            $user->setPassword($userPasswordHasher->hashPassword(
                $user,
                $form->get('password')->getData()
            ));


            $user->setCreatedAt(new DateTimeImmutable());

            $manager = $managerRegistry->getManager();
            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', 'L\'Utilisateur a bien été créé');
            return $this->redirectToRoute('admin_users');
        }

        return $this->render('user/form.html.twig', [
            'userForm' => $form->createView()
        ]);
    }


    #[Route('admin/user/update/{id}', name: 'update_user')]
    public function edit(Request $request, User $user, UserRepository $userRepository, ManagerRegistry $managerRegistry): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $users = $userRepository->findAll();
            $userFirstNames = [];
            $userLastNames = [];
            $userRoles = [];
            $userPassword = [];
            foreach ($users as $newUser) {
                $userFirstNames[] = $newUser->getFirstName();
                $userLastNames[] = $newUser->getLastName();
                $userRoles[] = $newUser->getRoles();
                $userPassword[] = $newUser->getPassword();
            }
            $manager = $managerRegistry->getManager();
            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', 'L\'Utilisateur a bien été modifié');
            return $this->redirectToRoute('admin_users');
        }

        return $this->render('user/form.html.twig', [
            'userForm' => $form->createView()
        ]);
    }

    #[Route('admin/user/delete/{id}', name: 'delete_user')]
    public function delete(User $user, ManagerRegistry $managerRegistry): Response
    {
        $manager = $managerRegistry->getManager();
        $manager->remove($user);
        $manager->flush();

        $this->addFlash('success', 'L\'Utilisateur a bein été supprimé');
        return $this->redirectToRoute('admin_users');
    }
}
