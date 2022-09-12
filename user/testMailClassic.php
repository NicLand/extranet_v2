<?php

$mailList = "n_landrein@hotmail.com";


if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $mailList)) // On filtre les serveurs qui rencontrent des bogues.
{
	$passage_ligne = "\r\n";
}
else
{
	$passage_ligne = "\n";
}
//=====Déclaration des messages au format texte et au format HTML.
$message_txt = "Bonjour, vous venez de recevoir la newsletter du MFP - Allez à l'adresse suivante pour la visualiser : http://www.mfp.cnrs.fr/mfp/newsletter/newsletter.php?nl=".$NL;
$message_html = "Ceci est un message classique";
//==========

//=====Création de la boundary
$boundary = "-----=".md5(rand());
//==========
$mail = "nicolas.landrein@u-bordeaux.fr";
//=====Définition du sujet.
$sujet = "Mail classique";
//=========

//=====Création du header de l'e-mail.
$header = "From: \"Newsletter MFP #".$NL."\"<no-reply@mfp.cnrs.fr>".$passage_ligne;
$header.= "Reply-to: \"Nico\" <".$mail.">".$passage_ligne;
$header.= "Bcc:".$mailList.$passage_ligne;
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

//=====Envoi de l'e-mail.
if(mail($mail,$sujet,$message,$header)){echo 'OK!';}
//==========
