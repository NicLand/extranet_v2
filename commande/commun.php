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
  Session::setFlash('danger', "Vous n'êtes pas autorisé à accéder à cette page.");
  App::redirect('commande/commander.php');
  exit();
}

//===========================================================
if(isset($_GET['t']) && $_GET['t'] !=''){
  $t = $_GET['t'];
  if(isset($_GET['p']) && $_GET['p'] !=''){
    $p = $_GET['p'];
  }
  else{
    $p = 'UGAP';
  }
}
//===========================================================
  if(isset($_POST['quantite']) && !isset($_POST['final_validation'])){
    $upCommun = new Commande;
    foreach($_POST['quantite'] as $product=>$quantity){
        if(empty($quantity)){$quantity = 0;}
        if($upCommun->upCommandeCommun($t,$product,intval($quantity))){
      }
    }
  }

  if(isset($_POST['final_validation'])){
    $validCommande = new Commande;
    foreach($_POST['quantite'] as $product=>$quantity){
      if($quantity >0){
        $produit = $validCommande->getSingleCommun($t,$product);
        $prix = $quantity * $produit->prix_u;
        $date = date("Y-m-d");
        $validCommande->commander($t,$user->team_id,$p,$produit->nomenclature,$quantity,$produit->designation,$produit->reference,'',$produit->prix_u,0,$prix,$date,1,'');
      }
    }
    Session::setFlash('success', "La liste de produit est passée en commande !");
    App::redirect('commande/commander.php');
    exit();
  }
  if(isset($_POST['effacer'])){
    $raz = new Commande;
    if($raz->razCommun($t,$p)){
      Session::setFlash('success', "La liste de produit est été remise à zéro !");
      $link = "commande/commun.php?t=$t&p=$p";
      App::redirect($link);
      exit();
    }
  }

//===========================================================
$title = 'Extranet MFP';
$titleLink = 'index.php';

$rapidAccess = [];
$menuItem = Commande::getRapidAccess();

echo Header::getHeader($title, $titleLink, $rapidAccess, $menuItem);
$commande = new Commande;
$tabs = $commande->distinctDealer($t);
echo '<a href="'.App::getRoot().'/commande/commun_new.php?t='.$t.'" class="btn btn-success mt-2" role="button">Ajouter une nouvelle référence.</a>';

?>
<nav class="nav nav-pills flex-column flex-sm-row mt-3">
    <?php foreach($tabs as $tab){
      if($tab->fournisseur == $p){$sup = "active";}else{$sup="";}
      echo "<a class='flex-sm-fill text-sm-center nav-link $sup' href='commun.php?t=$t&p=$tab->fournisseur'>$tab->fournisseur</a>";
    }
    ?>
</nav>

<?php

echo $commande->getCommandeCommun($t,$p);

?>

<?= Footer::getFooter();
