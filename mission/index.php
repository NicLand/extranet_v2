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
    <h1 align="center">NOTE D'INFORMATION SUR LES MISSIONS</h1>
    <h3>I - Avant tout d&eacute;placement</h3>
     <p>Vous devez être en possession d'un ordre de mission établi <strong>préalablement</strong> à votre départ.</p>
    <h4 align="center">AUCUNE MISSION NE SERA FAITE &Agrave; POSTERIORI POUR REGULARISATION</h4>
    <h5>Pour cela, il est nécessaire de :</h5>
    <ol>
      <li>remplir une<strong><a href="mission.php" style="color:red;"> >> demande d'ordre de mission <<</a></strong></li>
      <p>-10 jours minimum avant le départ pour les missions France</p>
      <p> - 1 mois* minimum avant le départ pour les missions à l'étranger
      </p>

      <p><em>* Ce délai est à respecter impérativement, car nous devons pour chaque mission à l'étranger contacter le Fonctionnaire Sécurité Défense afin de demander l'autorisation d'envoi de missionnaires dans les pays à risques. Cette démarche doit être toujours faite avant signature de l'ordre de mission</em></p>
      <p>&nbsp;</p>
      <li>Joindre un R.I.B ou R.I.P pour la première mission ou en cas de changement de compte bancaire ou postal.</li></ol>
    <p><strong>Pour les participations à des colloques</strong>, joindre les pages du programme indiquant la date de la manifestation et les prestations éventuellement comprises dans les frais d'inscription : nombre de repas, nuitées.</p>
      <p>En cas d'utilisation du véhicule personnel il est nécessaire de joindre une copie de la carte grise, et de l'attestation d'assurance. Si vous transportez des passagers, n&rsquo;oubliez pas d'indiquer leurs noms.</p>
      <p>&nbsp;</p>

    <h3>II - À votre retour de mission</h3>
    <p>Pour un remboursement rapide des frais engagés, il est indispensable de ramener très vite à Geneviève, Valérie ou Sandrine les pièces justificatives afin qu'elles puissent établir l'état de frais de déplacement correspondant.</p>
    <h5><em>Pour les missions France :</em></h5>
    <p> * Titre de transport originaux : billet pour le train, coupon de vol pour l'avion, reçu ou facture à votre nom pour un taxi<br />
      * Ticket de péage : reçus (originaux)<br />
     * Ticket de parking : tickets (originaux)<br />
      * Ticket RER, Métro, Tram, Bus<br />
      * Hébergement : facture d'hôtel établie à votre nom</p>
    <h5><em>Pour les missions Etranger :</em></h5>
     <p> * Titre de transport originaux : billet pour le train, coupon de vol pour l'avion, reçu ou facture pour un taxi<br />
      * Hébergement : facture d'hôtel établie à votre nom<br />
    * Attestation de présence au Congrès</p>
  </div>
</div>



<?= Footer::getFooter();
