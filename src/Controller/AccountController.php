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
    /* account page */
    #[Route('/account', name: 'account', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function index(Request $request, Security $security, UserRepository $ur, TaskRepository $tr): Response
    {
        // current log user
        $user = $security->getUser();
        $id = $security->getUser()->getId();
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
            $tr->add($newTask, true);

            return $this->redirectToRoute('dashboard-account', [], Response::HTTP_SEE_OTHER);
        }

        // view render
        return $this->renderForm('account/index.html.twig', [
            'user'=>$user,
            'tasks'=>$userTasks,
            'form'=>$form,
        ]);
    }

    /* edit account edit */
    #[Route('/account/edit', name: 'account-edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function edit(Request $request, Security $security, UserRepository $ur): Response
    {
        // auth 
        if($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            if (!$this->isGranted('ROLE_ADMIN')) {
                // logged user
                $user = $security->getUser();
                $form = $this->createForm(UsersType::class, $user);
                $form->handleRequest($request);
                // form validation
                if ($form->isSubmitted() && $form->isValid()) {
                    $ur->add($user, true);
                    return $this->redirectToRoute('dashboard-account', [], Response::HTTP_SEE_OTHER);
                }

                // view render
                return $this->renderForm('account/edit-account.html.twig', [
                    'user'=>$user,
                    'form'=>$form,
                ]);
            }
        }
        // view redirect
        return $this->redirectToRoute('dashboard-account', [], Response::HTTP_SEE_OTHER);
    }
}