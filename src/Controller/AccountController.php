<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TasksType;
use App\Form\UsersType;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('697868c2e3364b0c6529e42626305015', 'dashboard-')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
class AccountController extends AbstractController
{
    private $security;
    private $taskRepo;
    private $userRepo;

    public function __construct(Security $security, TaskRepository $taskRepo, UserRepository $userRepo)
    {
        $this->security = $security;
        $this->taskRepo = $taskRepo;
        $this->userRepo = $userRepo;
    }

    // account page
    #[Route('/account', name: 'account', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function index(Request $request, UserRepository $ur,): Response
    {
        // get current log User - (object)
        $user = $this->security->getUser();
        // current user - (id)
        $id = $user->getId();
        $user = $ur->findOneBy(['email' => $user->getUserIdentifier()]);
        // get all tasks from current id user
        $tasks = $ur->find($id);
        $userTasks = $tasks->getTasks();
        // get task object 
        $task = new Task();
        // form
        $form = $this->createForm(TasksType::class, $task);
        $form->handleRequest($request);
        // form validation
        if ($form->isSubmitted() && $form->isValid() && $request->isMethod('POST')) {
            $newTask = $form->getData();
            $newTask->setUser($user);
            $this->taskRepo->add($newTask, true);
            // view render
            return $this->redirectToRoute('dashboard-account', [], Response::HTTP_SEE_OTHER);
        }
        // view render
        return $this->renderForm('account/index.html.twig', [
            'user'=>$user,
            'tasks'=>$userTasks,
            'form'=>$form,
        ]);
    }

    // edit account edit
    #[Route('/account/edit', name: 'account-edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function editAccount(Request $request): Response
    {
        // get current log user
        $user = $this->security->getUser();
        // create form and handle request        
        $form = $this->createForm(UsersType::class, $user);
        $form->handleRequest($request);
        // form validation
        if ($form->isSubmitted() && $form->isValid() && $request->isMethod('POST')) {
            // update account 
            $this->userRepo->add($user, true);
            // view redirect
            return $this->redirectToRoute('dashboard-account', [], Response::HTTP_SEE_OTHER);
        }
        // view render
        return $this->renderForm('account/edit-account.html.twig', [
            'user'=>$user,
            'form'=>$form,
        ]);
    }

    // edit task from current user (id)
    #[Route('/account/edit-task/{id}', name: 'account-edit-task', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]    
    public function editTask(Task $task, Request $request): Response
    {
        // get current log user
        $user = $this->security->getUser();
        // create form and handle request        
        $form = $this->createForm(TasksType::class, $task);
        $form->handleRequest($request);
        // form validation
        if ($form->isSubmitted() && $form->isValid() && $request->isMethod('POST')) {
            // update task
            $this->taskRepo->add($task, true);
            // view redirect
            return $this->redirectToRoute('dashboard-account', [], Response::HTTP_SEE_OTHER);
        }
        // view render
        return $this->renderForm('account/edit-task-account.html.twig', [
            'user'=>$user,
            'form'=>$form,
            'task'=>$task,
        ]);
    }


    // delete task from current user (id)  
    #[Route('/account/delete-task/{id}', name: 'account-delete-task', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]    
    public function deleteTask(Task $task, Request $request): Response
    {
        // auth
        if($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            if ($this->isCsrfTokenValid('delete'.$task->getId(), $request->request->get('_token'))) {
                // delete task by - (ID)
                $this->taskRepo->remove($task, true);
            }
        }
        // view render 
        return $this->redirectToRoute('dashboard-account', [], Response::HTTP_SEE_OTHER);
    }
}