<?php

namespace App\Controller;

use App\Form\ResetEmailType;
use App\Form\ChangePasswordType;
use Symfony\Component\Mime\Email;
use App\Repository\UserRepository;
use DateInterval;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ResetController extends AbstractController
{
    #[Route('/reset', name: 'app_reset')]
    public function index(
        Request $request, 
        MailerInterface $mailer, 
        EntityManagerInterface $manager, 
        UserRepository $repo
    ): Response
    {
        $form = $this->createForm(ResetEmailType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();

            $date = new DateTime();
            $date = $date->add(new DateInterval("P4D"));

            $info = [
                "email" => $data["from"],
                "expireAt" => $date->format(("Y-m-d H:i:s"))
            ];
            $json = json_encode($info);
            $token = base64_encode($json);
            // dd($base64);

            $user = $repo->findOneBy([
                "email" => $data["from"]
            ]);

            if ($user) {

                $user->setToken($token);
                $manager->flush();
                
                $email = new Email();
                $email->from($data["from"]);
                $email->to("contact@monsite.com");
                $email->subject("Reset password");
                $email->html("<b>titre</b><hr><a href='https://127.0.0.1:8000/change/$token'>lien</a>");
                
                $mailer->send($email);
            }
                
            $this->addFlash('success', 'Un email vous a été envoyé !');

            return $this->redirect("/");
        }

        return $this->render('reset/index.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/change/{token}', name: 'app_change')]
    public function change_password(
        Request $request, 
        MailerInterface $mailer, 
        EntityManagerInterface $manager, 
        UserRepository $repo,
        UserPasswordHasherInterface $hasher,
        $token
    ): Response
    {
        $user = $repo->findOneBy([
            "token" => $token
        ]);
        $expire_at = json_decode(base64_decode($token))["expireAt"];
        $date = DateTime::createFromFormat("Y-m-d H:i:s", $expire_at);
        $now = new DateTime();

        if($user && $date>$now ) {
            $form = $this->createForm(ChangePasswordType::class);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $data = $form->getData();
                // $test = $repo->findOneBy(["email"=>$data["email"]]);
    
                if ($data["password1"]==$data["password2"] && $user->getEmail()==$data["email"]) {

                    $user->setPassword($hasher->hashPassword($user, $data["password1"]));
                    $manager->flush();
                    
                    // $email = new Email();
                    // $email->from($data["from"]);
                    // $email->to("contact@monsite.com");
                    // $email->subject("Reset password");
                    // $email->html("<b>titre</b><hr>");
                    
                    // $mailer->send($email);
                    
                }  
                    
                $this->addFlash('success', 'Un email vous a été envoyé !');
    
                return $this->redirect("/");
            }

        }
        else {
            $this->addFlash('error', 'token invalide !');

            return $this->redirect("/");
        }

        

        return $this->render('reset/index.html.twig', [
            'form' => $form,
        ]);
    }
}
