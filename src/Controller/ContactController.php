<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use App\Message\Event\MessageEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="app_contact")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param EventDispatcherInterface $dispatcher
     * @return Response
     */
    public function index(
        Request $request,
        EntityManagerInterface $manager,
        EventDispatcherInterface $dispatcher
    ): Response {
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($message);
            $manager->flush();
            $dispatcher->dispatch(new MessageEvent($message));
            $this->addFlash('success', 'Votre message a bien été envoyé');
            return $this->redirectToRoute('app_contact');
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
