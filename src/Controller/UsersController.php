<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UsersType;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/697868c2e3364b0c6529e42626305015', name: 'dashboard-')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
class UsersController extends AbstractController
{
    private $slugger;
    private $userRepo;
    private $passwordHasher;

    public function __construct(SluggerInterface $slugger, UserRepository $userRepo, UserPasswordHasherInterface $passwordHasher)
    {
        $this->slugger = $slugger;
        $this->userRepo = $userRepo;
        $this->passwordHasher = $passwordHasher;
    }

    // all users
    #[Route('/users', name: 'users', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function index(Request $request): Response
    {
        // get all users
        $users = $this->userRepo->findAll(); // findBy(['status' => true]);
        // add new user
        $user = new User();
        // create form and handle request
        $form = $this->createForm(UsersType::class, $user);
        $form->handleRequest($request);

        // form validation
        if ($form->isSubmitted() && $form->isValid()) {
            // get data from new user
            $userEdited = $form->getData();
            // this is avatar from input - user
            $avatar = $form->get('avatar')->getData();
            // added image avatar
            if($avatar) {
                // image property
                $originalFilename = pathinfo($avatar->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $this->slugger->slug($originalFilename);
                $fileName = $safeFilename.'-'.uniqid().'.'.$avatar->guessExtension();
                // move image files
                try {
                    $avatar->move($this->getParameter('image_avatar'), $fileName);
                } catch (FileException $e) {
                    return new Response("File Upload Error: $e");
                }
                // set avatar
                $userEdited->setAvatar($fileName);    
            }
            // password hasher 
            $user->setPassword($this->passwordHasher->hashPassword($user,$form->get('plainPassword')->getData()));
            $this->userRepo->add($user, true);
            // view redirect
            return $this->redirectToRoute('dashboard-users', [], Response::HTTP_SEE_OTHER);   
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
    public function edit(Request $request, User $user): Response
    {
        // create form and handle request
        $form = $this->createForm(UsersType::class, $user);
        $form->handleRequest($request);
        // form validation    
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($this->passwordHasher->hashPassword($user,$form->get('plainPassword')->getData()));
            $this->userRepo->add($user, true);
            return $this->redirectToRoute('dashboard-users', [], Response::HTTP_SEE_OTHER);
        }
        // view render
        return $this->renderForm('users/edit-user.html.twig', [
            'user'=>$user,
            'form'=>$form,
        ]);
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
