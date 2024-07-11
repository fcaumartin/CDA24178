<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProduitController extends AbstractController
{
    #[Route('/crud/add', name: 'app_produit_add')]
    public function index(Request $request, EntityManagerInterface $manager): Response
    {
        $p = new Produit();

        $form = $this->createForm(ProduitType::class, $p);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // dd($p);

            $manager->persist($p);
            $manager->flush();
            // return $this->redirect("/");
            return $this->redirectToRoute("app_accueil");
        }

        return $this->render('produit/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
