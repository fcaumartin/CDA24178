<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use App\Entity\Produit;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class Jeu1 extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $c1 = new Categorie();
        $c1->setNom("Guitares");
        $c1->setImage("guitares.png");
        $manager->persist($c1);

        $sc1 = new Categorie();
        $sc1->setNom("Guitares electriques");
        $sc1->setImage("guitares_electriques.png");
        $manager->persist($sc1);

        //$c1->addSousCategory($sc1);
        $sc1->setParent($c1);

        $ssc1 = new Categorie();
        $ssc1->setNom("poipoi");
        $ssc1->setImage("...");
        $manager->persist($ssc1);

        $sc1->addSousCategory($ssc1);
        $ssc1->setParent($sc1);

        $c2 = new Categorie();
        $c2->setNom("Sonorisation");
        $c2->setImage("sonorisation.png");
        $manager->persist($c2);

        $c3 = new Categorie();
        $c3->setNom("Piano");
        $c3->setImage("piano.png");
        $manager->persist($c3);

        $p1 = new Produit();
        $p1->setNom("Strato");
        $p1->setImage("Strato");
        $p1->setPrix(2500);
        $p1->setDescription("");
        $manager->persist($p1);

        $p1->setCategorie($sc1);

        $p2 = new Produit();
        $p2->setNom("Gibson");
        $p2->setImage("");
        $p2->setPrix(2500);
        $p2->setDescription("");
        $manager->persist($p2);

        $p2->setCategorie($sc1);

        $p3 = new Produit();
        $p3->setNom("Les Paul");
        $p3->setImage("Strato");
        $p3->setPrix(2500);
        $p3->setDescription("");
        $manager->persist($p3);

        $p3->setCategorie($sc1);


        $u = new User();

        $u->setEmail("toto@gmail.com");
        $u->setPassword("");

        $manager->persist($u);


        $manager->flush();
    }
}
