<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MessageController
 * @Route("/admin/message")
 */
class MessageController extends AbstractController
{
    /**
     * @Route("/{id}/edit", name="app_admin_message_edit")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param Message $message
     * @return Response
     */
    public function edit(Request $request, EntityManagerInterface $manager, Message $message): Response
    {
        $form = $this->createForm(MessageType::class, $message)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->flush();
            $this->addFlash('success', 'Votre message a bien été modifié');
            return $this->redirectToRoute('app_admin_message_index');
        }
        return $this->render('admin/message/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/", name="app_admin_message_index")
     * @param MessageRepository $repository
     * @return Response
     */
    public function index(MessageRepository $repository)
    {
        $allMessages = $repository->findAll();

        $messages = [];
        foreach ($allMessages as $message) {
            $messages[$message->getEmail()][] = $message;
        }

        return $this->render('admin/message/index.html.twig', [
            'messages' => $messages,
        ]);
    }
}