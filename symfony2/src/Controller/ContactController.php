<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Notifier\Message\EmailMessage;
use Symfony\Component\Routing\Attribute\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
            
            $email = new Email();
            $email->from($data["from"]);
            $email->to("contact@monsite.com");
            $email->subject($data["subject"]);
            $email->html("<b>titre</b><hr>".$data["message"]);

            $mailer->send($email);

            $this->addFlash('success', 'Super, votre email est envoyé !');
            $this->addFlash('error', 'ARGHHHH !!!!');
            $this->addFlash('error', ' !!!!');

            return $this->redirect("/");
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form,
        ]);
    }
}
