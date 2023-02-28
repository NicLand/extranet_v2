<?php
//===========================================================
//Charge automatiquemebnt les Class utilis�es dans les pages
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
$access = new Access("commande", $user);
if(!$access->accessCommande()){
  Session::setFlash('danger', "Vous n'�tes pas autoris� � acc�der � cette page.");
  App::redirect('index.php');
  exit();
}

//===========================================================

$title = 'Extranet MFP';
$titleLink = 'index.php';

$rapidAccess = [];

$menuItem = Commande::getRapidAccess();

echo Header::getHeader($title, $titleLink, $rapidAccess, $menuItem);?>

    <h1 class="m-3">Système de commande du MFP</h1>
    <p>Bienvenue dans l'application web permettant de passer des demandes d'achats auprès des gestionnaires du laboratoire.</p>
    <ul>
      <li>Pour passer une demande d'achat, veuillez cliquer sur l'onglet "<a href="commander.php">Commander</a>".</li>
      <p></p>
      <li>Pour consulter les demandes d'achat en attente de validation par les gestionnaires, veuillez cliquer sur l'onglet "<a href="encours.php">En cours</a>".</li><p></p>
      <li>Pour consulter les commandes (validées et faxées) en attente de livraison, veuillez cliquer sur l'onglet "<a href="enlivraison.php">En livraison</a>".</li><p></p>
      <li>Pour consulter l'historique des commandes passées, veuillez cliquer sur l'onglet "<a href="historique.php">Historique</a>".<br/>
      Les commandes sont classées par ordre chronologique par année. Le moteur de recherche  permet de retrouver des commandes par utilisateur, fournisseur, désignation et référence.</li>
      <p></p>
      <li>La liste des fournisseurs réguliers du laboratoire est disponible sur la page "<a href="fournisseurs.php">Fournisseurs</a>".<br/>
        Si vous voulez commander chez un fournisseur qui n'est pas présent dans la liste, vous devez d'abord contacter votre gestionnaire pour qu'il l'ajoute à la liste.</li><p></p>
    </ul>
  </div>

<?= Footer::getFooter();
