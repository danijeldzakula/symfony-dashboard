<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UsersType;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/697868c2e3364b0c6529e42626305015', name: 'dashboard-')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
class UsersController extends AbstractController
{
    // all users
    #[Route('/users', name: 'users', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function index(Request $request, UserRepository $ur, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        // get all users
        $users = $ur->findAll(); // findBy(['status' => true]);
        // add new user
        $user = new User();
        // create form and handle request
        $form = $this->createForm(UsersType::class, $user);
        $form->handleRequest($request);
        // form validation
        if($this->isGranted('ROLE_ADMIN')) {
            if ($form->isSubmitted() && $form->isValid()) {
                $user->setPassword($userPasswordHasher->hashPassword($user,$form->get('plainPassword')->getData()));
                $ur->add($user, true);   
                return $this->redirectToRoute('dashboard-users', [], Response::HTTP_SEE_OTHER);
            }
        }
        // view render
        return $this->renderForm('users/index.html.twig', [
            'users'=>$users,
            'form'=>$form,
        ]);
    }
    

    // view user
    #[Route('/user/view/{id}', name: 'view-user', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function user($id, User $user, UserRepository $ur): Response
    {
        // get all tasks from current id user
        $tasks = $ur->find($id); // $tr->findBy(['user'=> $id]);
        $userTasks = $tasks->getTasks();
        // render view 
        return $this->render('users/view-user.html.twig', [
            'user' => $user, 
            'tasks' => $userTasks,
        ]);
    }   

    
    // edit user
    #[Route('/user/edit/{id}', name: 'edit-user',  methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Request $request, User $user, UserRepository $ur, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        //$currentPasswordHash = $user->getPassword();
        if($this->isGranted('ROLE_ADMIN')) {
            $form = $this->createForm(UsersType::class, $user);
            $form->handleRequest($request);
            // form validation
            if($this->isGranted('IS_AUTHENTICATED_FULLY')) {
                if ($form->isSubmitted() && $form->isValid()) {
                    $user->setPassword($userPasswordHasher->hashPassword($user,$form->get('plainPassword')->getData()));
                    $ur->add($user, true);
                    return $this->redirectToRoute('dashboard-users', [], Response::HTTP_SEE_OTHER);
                }
            }       
            // view render
            return $this->renderForm('users/edit-user.html.twig', [
                'user'=>$user,
                'form'=>$form,
            ]);
        }
        // view redirect
        return $this->redirectToRoute('dashboard-account', [], Response::HTTP_SEE_OTHER);
    }  

    // delete user
    #[Route('/user/delete/{id}', name: 'delete-user',  methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(User $user, Request $request, UserRepository $ur): Response
    {
        // auth
        if($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
                $ur->remove($user, true);
            }
        }
        // render view 
        return $this->redirectToRoute('dashboard-users', [], Response::HTTP_SEE_OTHER);
    }      
}
