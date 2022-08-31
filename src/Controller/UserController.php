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

class UserController extends AbstractController
{
    #[Route('/admin/users', name: 'admin_users')]
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        return $this->render('user/userList.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/admin/user/create', name: 'create_user')]
    public function create(Request $request, UserRepository $userRepository, ManagerRegistry $managerRegistry): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $users = $userRepository->findAll();
            $userFirstNames = [];
            $userLastNames = [];
            foreach ($users as $newUser) {
                $userFirstNames[] = $newUser->getFirstName();
            }
            foreach ($users as $newUser) {
                $userLastNames[] = $newUser->getLastName();
            }

            if (in_array($form['first_name']->getData(), $userFirstNames)) {
                $this->addFlash('danger', 'L\'utilisateur n\'a pas pu être créé : le prénom de l\'utilisateur est déjà utilisé');
                return $this->redirectToRoute('admin_users');
            }

            if (in_array($form['last_name']->getData(), $userLastNames)) {
                $this->addFlash('danger', 'L\'utilisateur n\'a pas pu être créé : la référence du produit est déjà utilisée');
                return $this->redirectToRoute('admin_users');
            }


            $user->setCreatedAt(new DateTimeImmutable());

            $manager = $managerRegistry->getManager();
            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', 'L\'utilisateur a bien été créé');
            return $this->redirectToRoute('admin_users');
        }

        return $this->render('user/form.html.twig', [
            'userForm' => $form->createView()
        ]);
    }

    #[Route('/admin/user/update/{id}', name: 'update_user')]
    public function update(Request $request, UserRepository $userRepository, ManagerRegistry $managerRegistry, User $user): Response
    {

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $users = $userRepository->findAll();
            $userFirstNames = [];
            $userLastNames = [];
            foreach ($users as $newUser) {
                $userFirstNames[] = $newUser->getFirstName();
            }
            foreach ($users as $newUser) {
                $userLastNames[] = $newUser->getLastName();
            }

            $manager = $managerRegistry->getManager();
            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', 'L\'utilisateur a bien été créé');
            return $this->redirectToRoute('admin_users');
        }

        return $this->render('user/form.html.twig', [
            'userForm' => $form->createView()
        ]);
    }
}
