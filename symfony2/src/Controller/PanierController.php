<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

class PanierController extends AbstractController
{
    #[Route('/panier', name: 'app_panier')]
    public function index(SessionInterface $session, ProduitRepository $repo): Response
    {
        $panier = $session->get("panier", []);
        dump($panier);

        $panier_affichage = [];

        foreach ($panier as $id => $quantite) {
            $produit = $repo->find($id);
            $produit->quantite = $quantite;
            $panier_affichage[] = $produit;
        }


        return $this->render('panier/index.html.twig', [
            'panier' => $panier_affichage,
        ]);
    }

    #[Route('/add/{id}', name: 'app_add')]
    public function add(SessionInterface $session, Produit $produit): Response
    {
        $panier = $session->get("panier", []);

        if (isset($panier[$produit->getId()])) {
            $panier[$produit->getId()]++;
            
        }
        else {
            $panier[$produit->getId()] = 1;

        }


        $session->set("panier", $panier);

        // dd($panier);

        return $this->redirect("/panier");
    }

    #[Route('/del/{id}', name: 'app_del')]
    public function del(SessionInterface $session, Produit $produit): Response
    {
        $panier = $session->get("panier", []);

        if (isset($panier[$produit->getId()])) {
            $panier[$produit->getId()]--;
            if ($panier[$produit->getId()] == 0 ) {
                unset($panier[$produit->getId()]);
            }
            
        }
        else {
            // $panier[$produit->getId()] = 1;

        }


        $session->set("panier", $panier);

        // dd($panier);

        return $this->redirect("/panier");
    }

    #[Route('/clear', name: 'app_clear')]
    public function clear(SessionInterface $session): Response
    {

        $session->set("panier", []);


        return $this->redirect("/panier");
    }


    public function calcul_quantite(SessionInterface $session): Response
    {
        $panier = $session->get("panier", []);
        $quantite_totale = 0;

        foreach ($panier as $id => $quantite) {
            $quantite_totale += $quantite;
        }


        return $this->render('panier/quantite_totale.html.twig', [
            'quantite_totale' => $quantite_totale,
        ]);
    }
}
