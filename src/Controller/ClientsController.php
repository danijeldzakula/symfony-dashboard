<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientsType;
use App\Repository\ClientRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/697868c2e3364b0c6529e42626305015', name: 'dashboard-')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
class ClientsController extends AbstractController
{
    private $slugger;
    private $clientRepo;

    public function __construct(SluggerInterface $slugger, ClientRepository $clientRepo)
    {
        $this->slugger = $slugger;
        $this->clientRepo = $clientRepo;
    }

    // All clients and Create new client 
    #[Route('/clients', name: 'clients',  methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function index(Request $request): Response
    {
        // get all clients
        $clients = $this->clientRepo->findAll();
        // add new client 
        $client = new Client();
        // create form and handle request 
        $form = $this->createForm(ClientsType::class, $client);
        $form->handleRequest($request);
        // form validation
        if ($form->isSubmitted() && $form->isValid() && $request->isMethod('POST')) {
            // get data from new client
            $clientEdited = $form->getData();
            // this is avatar from input - client
            $avatar = $form->get('avatar')->getData();
            // added image avatar 
            if ($avatar) {
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
                $clientEdited->setAvatar($fileName);
            }
            // create new client
            $this->clientRepo->add($client, true);
            return $this->redirectToRoute('dashboard-clients', [], Response::HTTP_SEE_OTHER);
        }
        // view render
        return $this->renderForm('clients/index.html.twig', [
            'clients'=>$clients,
            'form'=>$form,
        ]);
    }

    // view client
    #[Route('/client/view/{id}', name: 'view-client',  methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function client($id, Client $client): Response
    {
        // get all user from current id client
        $tasks = $this->clientRepo->find($id);
        // find all task 
        $userTasks = $tasks->getTasks();
        // view render
        return $this->render('clients/view-client.html.twig', [
            'client'=>$client,
            'tasks'=>$userTasks,
        ]);
    }

    // edit client 
    #[Route('/client/edit/{id}', name: 'edit-client', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Client $client, Request $request): Response 
    {
        // create form and handle request 
        $form = $this->createForm(ClientsType::class, $client);
        $form->handleRequest($request);
        // form validation
        if ($form->isSubmitted() && $form->isValid() && $request->isMethod('POST')) {
            // update client by - (ID)
            $this->clientRepo->add($client, true);
            // view redirect
            return $this->redirectToRoute('dashboard-clients', [], Response::HTTP_SEE_OTHER);
        }
        // view render 
        return $this->renderForm('clients/edit-client.html.twig', [
            'client'=>$client,
            'form'=>$form,
        ]);  
    }

    // delete client
    #[Route('/client/delete/{id}', name: 'delete-client', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Client $client, Request $request): Response
    {
        // form validation
        if($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            if ($this->isCsrfTokenValid('delete'.$client->getId(), $request->request->get('_token'))) {
                // delete client by - (ID)
                $this->clientRepo->remove($client, true);
            }
        }
        // view redirect
        return $this->redirectToRoute('dashboard-clients', [], Response::HTTP_SEE_OTHER);
    }
}
