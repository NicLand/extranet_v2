<?php
namespace Extranet;

require '../class/Autoloader.php';
Autoloader::register();

$chemin = $_SERVER['DOCUMENT_ROOT'].'/extranet_v2/newsletter/';


// fonction pour recupere le contenu d'un fichier
function file_get_contents_curl($url){
	$ch = curl_init();

    curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

    $data = curl_exec($ch);
    curl_close($ch);

    return $data;
}


$NL = $_GET['nl'];
$news = new Newsletter;
$donnees = $news->getNewsletter($NL);

$lireLasuite = 'https://www.mfp.cnrs.fr/extranet_v2/newsletter/article.php?nl='.$NL.'&cat=';

//Envoie à tous les abonnés de la liste UMR5234-all@diff.u-bordeaux.fr
$fich = 'mailing.txt';
$mail_list = fopen($fich,'r');
$tabFich = file($fich);
$nbLignes = count($tabFich);
$massiveMail= array();
for($i=0;$i<$nbLignes;$i++){
  $massiveMail[$i] = fgets($mail_list);
}
//echo '<br/> massif<br/>';
//print_r($massiveMail);

  $mailNico = array('nicolas.landrein@u-bordeaux.fr','n_landrein@hotmail.com');
//echo '<br/> Nico <br/>';
//print_r($mailNico);
  $mailRedac =[
    'christina.calmels@u-bordeaux.fr',
    'patricia.recordon-pinson@u-bordeaux.fr',
    'nicolas.landrein@u-bordeaux.fr',
    'marie-lise.blondot@u-bordeaux.fr',
    'corinne.asencio@u-bordeaux.fr',
    'paul.lesbats@u-bordeaux.fr',
    'floriane.lagadec@u-bordeaux.fr'
  ];

//echo '<br/> redac<br/>';
//print_r($redactionMail);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="robots" content="noindex,nofollow" />
<title>Envoie de la Newsletter</title>
</head>
<div align="center" style="min-height:180px; width:300px; background-color:red; margin:0 auto; padding-top:40px; display:block; position:fixed; left:20px;">
<form action="<?php $_SERVER['PHP_SELF'];?>" method="post" id="sendForm" name="sendForm" onsubmit="alert('Christina, es-tu sûre de vouloir envoyer la NL au comité ???');">
	<input type="submit" value="Envoie de la NL au comité de rédaction" />
    <input type="hidden" value="comite" name="sendNL"/>
</form>
<br/>
<form action="<?php $_SERVER['PHP_SELF'];?>" method="post" id="sendForm" name="sendForm" onsubmit="alert('Christina, es-tu sûre de vouloir envoyer la NL à Frédéric ???');">
	<input type="submit" value="Envoie de la NL à Frédéric" />
    <input type="hidden" value="fred" name="sendNL"/>
</form>
<br/>
<form action="<?php $_SERVER['PHP_SELF'];?>" method="post" id="sendUMR" name="sendUMR" onsubmit="alert('Christina, es-tu sûre de vouloir envoyer la NL à tout le monde ???');">
	<input type="submit" value="Envoie de la NL à l'unité" />
    <input type="hidden" value="umr" name="sendNL"/>
</form>
<br/>
<form action="<?php $_SERVER['PHP_SELF'];?>" method="post" id="sendNico" name="sendNico" onsubmit="alert('Christina, es-tu sûre de vouloir envoyer la NL à Nico ???');">
	<input type="submit" value="Envoie de la NL à Nico" />
    <input type="hidden" value="nico" name="sendNL"/>
</form>
</div>
<?php
$lien = 'https://www.mfp.cnrs.fr/extranet_v2/newsletter/auto2.php?nl='.$NL;

$messageNL = file_get_contents_curl($lien);

echo $messageNL;

if (isset($_POST['sendNL'])){

	// Déclaration de l'adresse de destination.
	if ($_POST['sendNL'] == 'comite'){
		$mailList = $mailRedac;
	}
	if ($_POST['sendNL'] == 'umr'){
		$mailList = $massiveMail;
    //$updateSend = $bdd->exec('UPDATE `newsletter` SET `send` = "1" AND `date`= NOW() WHERE `id` ='.$NL);
	}
	if ($_POST['sendNL'] == 'nico'){
		$mailList = $mailNico;
    //$updateSend1 = $bdd-exec('UPDATE `lab0123sql0db`.`newsletter` SET `date` = NOW() , `send` = "1" WHERE `newsletter`.`id` ='.$NL);
    //$updateSend = $bdd->exec('UPDATE `newsletter` SET `send` = "1" AND `date`= NOW() WHERE `id` ='.$NL);
	}
  if ($_POST['sendNL'] == 'fred'){
		$mailList = array('frederic.bringaud@u-bordeaux.fr','nicolas.landrein@u-bordeaux.fr');
	}


if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $mailList)) // On filtre les serveurs qui rencontrent des bogues.
{
	$passage_ligne = "\r\n";
}
else
{
	$passage_ligne = "\n";
}
//=====Déclaration des messages au format texte et au format HTML.
$message_txt = "Bonjour, vous venez de recevoir la newsletter du MFP - Allez à l'adresse suivante pour la visualiser : https://www.mfp.cnrs.fr/extranet_v2/newsletter/newsletter.php?nl=".$NL;
$message_html = $messageNL;
//==========

//=====Création de la boundary
$boundary = "-----=".md5(rand());
//==========
$mail = "christina.calmels@u-bordeaux.fr";
//=====Définition du sujet.
$sujet = "Newsletter du laboratoire MFP";
//=========
foreach ($mailList as $key => $value) {

//=====Création du header de l'e-mail.
$header = "From: \"Newsletter MFP #".$NL."\"<no-reply@mfp.cnrs.fr>".$passage_ligne;
$header.= "Reply-to: \"Christina\" <christina.calmels@u-bordeaux.fr>".$passage_ligne;
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

if(mail($value,$sujet,$message,$header))
{
  echo "send";
}
//==========
}
}
?>
