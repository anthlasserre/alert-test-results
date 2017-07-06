<?php

// MySQL via PDO connexion
$bdd = new PDO('mysql:host=localhost;dbname=test;charset=utf8', 'test', 'test');

$reponse = $bdd->query("
SELECT
    *
FROM
    results
WHERE
    alerted = 'no';
");

// Adapted only for Bordeaux Academy
$resultPage = file_get_contents('http://publinet.ac-bordeaux.fr/pubbts/resultats?idBaseSession=pubbts_0&actionId=3');

while ($donnees = $reponse->fetch()) {
$user_name = $donnees['user_name'];
$user_mail = $donnees['user_mail'];
$user_div = $donnees['user_div'];
}


$find = $user_div . '</a></li';

$pos = strpos($resultPage, $find);
// Test page
// echo $resultPage;
if ($pos === false) {
    echo "Toujours pas!";
} else {
    echo "Résultats publiés";
    $mail = $user_mail ; // Adresse de destination par défault

    //=====Déclaration des messages au format texte et au format HTML.
    $message_txt = "Ca y est mec, les résultats sont publiés!";
    $message_html = "<html>
                        <head>
                        </head>
                        <body>
                        <b>Ca y est mec</b>, <a href=\"http://publinet.ac-bordeaux.fr/pubbts/resultats?idBaseSession=pubbts_0&actionId=3\" target=\"_blank\">les résultats</a> sont publiés!<br><br>
                        </body>
                    </html>";

      $passage_ligne = "\n";

    //=====Définition du sujet.
    $sujet = "Resultats '. $division_name .' | C est bon mec";
    //=========

    //=====Création de la boundary
    $boundary = "-----=".md5(rand());
    //==========


    //=====Création du header de l'e-mail.
    $header = "From: Results '. $division_name .' <results@results-direct-bts.com>".$passage_ligne;
    $header.= "MIME-Version: 1.0".$passage_ligne;
    $header.= "Content-Type: multipart/alternative;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;
    //==========

    //=====Création du message.
    $message = $passage_ligne."--".$boundary.$passage_ligne;
    //=====Ajout du message au format texte.
    $message.= "Content-Type: text/plain; charset=\"ISO-8859-1\"".$passage_ligne;
    $message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
    $message.= $passage_ligne.$message_txt.$passage_ligne;
    //==========
    $message.= $passage_ligne."--".$boundary.$passage_ligne;
    //=====Ajout du message au format HTML
    $message.= "Content-Type: text/html; charset=\"UTF-8\"".$passage_ligne;
    $message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
    $message.= $passage_ligne.$message_html.$passage_ligne;
    //==========
    $message.= $passage_ligne."--".$boundary."--".$passage_ligne;
    $message.= $passage_ligne."--".$boundary."--".$passage_ligne;
    //==========
    mail($mail,$sujet,$message,$header);
}
?>
