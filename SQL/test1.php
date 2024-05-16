<?php

$debut=hrtime(true);

echo "test pdo php...\n";

$db = new PDO('mysql:host=localhost;dbname=demo', 'admin', 'Afpa1234');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$db->exec("START TRANSACTION;");
for ($i=0; $i < 100000; $i++) { 
    # code...
    $nom= bin2hex(openssl_random_pseudo_bytes(50));
    $prenom=bin2hex(openssl_random_pseudo_bytes(50));
    $ville=bin2hex(openssl_random_pseudo_bytes(50));
    $requete = $db->prepare("insert into client (nom, prenom, ville) values (?, ?, ?);");
    $requete->execute([$nom, $prenom, $ville]);
    
}
$db->exec("COMMIT;");


$fin = hrtime(true);
$t1 = ($fin-$debut)/1000000000;

echo "Insertion {$t1}\n";

$requete = $db->prepare("select * from client where nom=?;");
$requete->execute([$nom]);
    

$fin2 = hrtime(true);
$t2 = ($fin2-$fin)/1000000000;

echo "Requête {$t2}\n";
