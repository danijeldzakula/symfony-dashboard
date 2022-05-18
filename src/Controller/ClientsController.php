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

#[Route('/697868c2e3364b0c6529e42626305015', name: 'dashboard-')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
class ClientsController extends AbstractController
{
    // All clients
    #[Route('/clients', name: 'clients',  methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function index(Request $request, ClientRepository $cr): Response
    {
        // get all clients
        $clients = $cr->findAll(); // $ur->findAll();
        // add new client 
        $client = new Client();
        $form = $this->createForm(ClientsType::class, $client);
        $form->handleRequest($request);
        // form validation
        if($this->isGranted('ROLE_ADMIN')) {
            if ($form->isSubmitted() && $form->isValid()) {
                $cr->add($client, true);
                return $this->redirectToRoute('dashboard-clients', [], Response::HTTP_SEE_OTHER);
            }
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
    public function client($id, Client $client, ClientRepository $cr): Response
    {
        // get all user from current id client
        $tasks = $cr->find($id);
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
    public function edit(Client $client, Request $request, ClientRepository $cr): Response 
    {
        // auth
        if($this->isGranted('IS_AUTHENTICATED_FULLY') && $this->isGranted('ROLE_ADMIN')) {          
            $form = $this->createForm(ClientsType::class, $client);
            $form->handleRequest($request);
            // form validation
            if ($form->isSubmitted() && $form->isValid()) {
                $cr->add($client, true);
                return $this->redirectToRoute('dashboard-clients', [], Response::HTTP_SEE_OTHER);
            }
            // view render 
            return $this->renderForm('clients/edit-client.html.twig', [
                'client'=>$client,
                'form'=>$form,
            ]);
        }  
        // view redirect
        return $this->redirectToRoute('dashboard-account', [], Response::HTTP_SEE_OTHER);
    }

    // delete client
    #[Route('/client/delete/{id}', name: 'delete-client', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Client $client, Request $request, ClientRepository $cr): Response
    {
        // form validation
        if($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            if ($this->isCsrfTokenValid('delete'.$client->getId(), $request->request->get('_token'))) {
                $cr->remove($client, true);
            }
        }
        // view redirect
        return $this->redirectToRoute('dashboard-clients', [], Response::HTTP_SEE_OTHER);
    }
}
