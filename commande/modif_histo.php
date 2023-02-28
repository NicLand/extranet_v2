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
$access = new Access("commande", $user);
if(!$access->accessCommande()){
  Session::setFlash('danger', "Vous n'êtes pas autorisé à accéder à cette page.");
  App::redirect('index.php');
  exit();
}

//===========================================================
if(isset($_GET['id']) && $_GET['id'] !=""){
  $id = $_GET['id'];
  $commande = new Commande;
  $nacres = $commande->getListNomenclatures();
  $dealers = $commande->setListDealers();
  $modif = $commande->getCommande($id);
}
if(isset($_POST['update'])){

  $errors =[];
  $validator = new Validator($_POST);
    $validator->isSelect('dealer',"Veuillez choisir un fournisseur.");
    $validator->isSelect('nomenclature','Veuillez saisir une nomenclature.');
    $validator->isNumeric('quantite', "Veuillez saisr un quantité.");
    $validator->isText('reference', "Veuillez saisir une référence.");
    $validator->isText('designation', "Veuillez saisir une désignation.");
    $validator->isNumeric('prix_unitaire', "Veuillez saisir un Prix Unitaire.");
    if($validator->isValid()){
      foreach($_POST as $index => $valeur) {
         $$index = $valeur;
      }
      if(isset($commun)){$commun = 1;}else{$commun = 0;}
      if(isset($valide)){$valide = 1;}else{$valide = 0;}
      if(isset($livre)){$livre = 1;}else{$livre = 0;}
      $prix_unitaire = floatval($prix_unitaire);
      $remise = floatval($remise);
      $prix = floatval(($prix_unitaire-(($prix_unitaire * $remise)/100)) * $quantite);
      if($commande->upCommandeHisto($id,$dealer,$offre,$nomenclature,$quantite,$designation,$reference,$prix_unitaire,$remise,$prix,$commun,$commentaire,$valide,$bon_commande,$livre,$bon_livraison,$reception,$comment_livraison)){
        Session::getInstance()->setFlash('success',"La commande a été modifiée !");
        App::redirect('commande/historique.php');
        exit();
      }
    }
    else{
      $errors = $validator->getErrors();
    }
  }

if(isset($_POST['delete'])){
  if($commande->delCommande($id)){
    Session::getInstance()->setFlash('success',"La commande a été supprimée !");
    App::redirect('commande/historique.php');
    exit();
  }
}

//===========================================================
$title = 'Extranet MFP';
$titleLink = 'index.php';

$rapidAccess = Commande::getRapidAccess();
$menuItem = [];


echo Header::getHeader($title, $titleLink, $rapidAccess, $menuItem);?>

    <h1 class="m-3">Modification de l'historique</h1>

<?php
$form = new Form;
echo $form->openForm();
echo "<div class='row m-1'><div class='col-sm-3'>Acheteur :</div><div class='col-sm-9'><input type='text' name='acheteur' class='form-control form-control' id='acheteur' value='$modif->user' readonly></div></div>";
echo $commande->selectDealers($modif->fournisseur);
echo $commande->setOffre($modif->offre);
echo $commande->selectNomenclatures($modif->nomenclature);
echo $form->inputCard('quantite','number','Quantité :',$modif->quantite,'');
echo $form->inputCard('designation','text','Désignation : ',$modif->designation,'');
echo $form->inputCard('reference','text','Référence : ',$modif->reference,'');
echo $form->inputCard('prix_unitaire','number','Prix Unitaire : ',$modif->prix_unitaire,'');
echo $form->inputCard('remise','number','Remise : ',$modif->remise,'');
echo $commande->checkCommande('commun','Commun : ',$modif->commun);
echo $form->textAreaCard('commentaire','Commentaire : ',$modif->comment);
echo $commande->checkCommande('valide','Validé : ',$modif->valide);
echo $form->inputCard('bon_commande','text','Bon de commande :',$modif->bon_commande,'');
echo $commande->checkCommande('livre','Livré : ',$modif->livre);
echo $form->inputCard('bon_livraison','text','Bon de Livraison :',$modif->bon_livraison,'');
echo $form->inputCard('reception','text','Receptionné par : ',$modif->reception,'');
echo $form->textAreaCard('comment_livraison','Commentaire Livraison : ',$modif->comment_livraison);
echo $form->submit('primary','update','Modifier la commande');
echo $form->delete("FR");
echo $form->closeForm();


 ?>

<?= Footer::getFooter();
