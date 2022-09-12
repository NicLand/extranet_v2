<?php
//===========================================================
//Charge automatiquemebnt les Class utilisées dans les pages
// Dans le bon __NAMESPACE__
namespace Extranet;

require '../class/Autoloader.php';
Autoloader::register();

$auth = App::getAuth();
$user = $auth->user();

if(!$user){
  App::redirect('login.php');
  exit();
}
//===========================================================

$title = 'Extranet MFP';
$titleLink = 'index.php';

$rapidAccess = array(
  'ProParaCyto'=>'cytoskeletor',
  'iMET' => 'imet',
  'ex-REGER' => 'reger'
);
$menuItem = array(
  'Réservation MFP'=>'http://www.mfp.cnrs.fr/grr',
  'Achats'=>'../mfp/commande',
  'Missions'=>'administration'
);

echo Header::getHeader($title, $titleLink, $rapidAccess, $menuItem);?>

<div class="row justify-content-center">
  <div class="col-10">
    <h1 align="center">NOTE D&rsquo;INFORMATION SUR LES MISSIONS</h1>
    <h3>I - Avant tout d&eacute;placement</h3>
     <p>Vous devez &ecirc;tre en possession d&rsquo;un ordre de mission &eacute;tabli <strong>pr&eacute;alablement</strong> &agrave; votre d&eacute;part.</p>
    <h4 align="center">AUCUNE MISSION NE SERA FAITE &Agrave; POSTERIORI POUR REGULARISATION</h4>
    <h5>Pour cela, il est n&eacute;cessaire de&nbsp;:</h5>
    <ol>
      <li>remplir une<strong><a href="mission.php" style="color:red;"> >> demande d&rsquo;ordre de mission <<</a></strong></li>
      <p>-10 jours minimum avant le d&eacute;part pour les missions France</p>
      <p> - 1 mois* minimum avant le d&eacute;part pour les missions &agrave; l'&eacute;tranger
      </p>

      <p><em>* Ce d&eacute;lai est &agrave; respecter imp&eacute;rativement, car nous devons pour chaque mission &agrave; l&rsquo;&eacute;tranger contacter le Fonctionnaire S&eacute;curit&eacute; D&eacute;fense afin de demander l&rsquo;autorisation d&rsquo;envoi de missionnaires dans les pays &agrave; risques. Cette d&eacute;marche doit &ecirc;tre toujours faite avant signature de l&rsquo;ordre de mission</em></p>
      <p>&nbsp;</p>
      <li>Joindre un R.I.B ou R.I.P pour la premi&egrave;re mission ou en cas de changement de compte bancaire ou postal.      </li></ol>
    <p><strong>Pour les participations &agrave; des colloques</strong>, joindre les pages du programme indiquant la date de la manifestation et les prestations &eacute;ventuellement comprises dans les frais d'inscription : nombre de repas, nuit&eacute;es.</p>
      <p>En cas d&rsquo;utilisation du v&eacute;hicule personnel il est n&eacute;cessaire de joindre une copie de la carte grise, et de l&rsquo;attestation d&rsquo;assurance. Si vous transportez des passagers, n&rsquo;oubliez pas d&rsquo;indiquer leurs noms.</p>
      <p>&nbsp;</p>

    <h3>II - &Agrave; votre retour de mission</h3>
    <p>Pour un remboursement rapide des frais engag&eacute;s, il est indispensable de ramener tr&egrave;s vite &agrave; Genevi&egrave;ve, Val&eacute;rie ou Isabelle les pi&egrave;ces justificatives afin qu&rsquo;elles puissent &eacute;tablir l&rsquo;&eacute;tat de frais de d&eacute;placement correspondant.</p>
    <h5><em>Pour les missions France&nbsp;:</em></h5>
    <p> * Titre de transport originaux&nbsp;: billet pour le train, coupon de vol pour l&rsquo;avion, re&ccedil;u ou facture &agrave; votre nom pour un taxi<br />
      * Ticket de p&eacute;age&nbsp;: re&ccedil;us (originaux)<br />
     * Ticket de parking : tickets (originaux)<br />
      * Ticket RER, M&eacute;tro, Tram, Bus<br />
      * H&eacute;bergement&nbsp;: facture d&rsquo;h&ocirc;tel &eacute;tablie &agrave; votre nom</p>
    <h5><em>Pour les missions Etranger&nbsp;:</em></h5>
     <p> * Titre de transport originaux&nbsp;: billet pour le train, coupon de vol pour l&rsquo;avion, re&ccedil;u ou facture pour un taxi<br />
      * H&eacute;bergement&nbsp;: facture d&rsquo;h&ocirc;tel &eacute;tablie &agrave; votre nom<br />
    * Attestation de pr&eacute;sence au Congr&egrave;s</p>
  </div>
</div>



<?= Footer::getFooter();
