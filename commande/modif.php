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
      $prix_unitaire = floatval($prix_unitaire);
      $remise = floatval($remise);
      $prix = floatval(($prix_unitaire-(($prix_unitaire * $remise)/100)) * $quantite);
      $date = date("Y-m-d");
      if($commande->upCommande($id,$dealer,$nomenclature,$quantite,$designation,$reference,$offre,$prix_unitaire,$remise,$prix,$date,$commun,$commentaire)){
        Session::getInstance()->setFlash('success',"La commande a été modifiée !");
        App::redirect('commande/commander.php');
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
    App::redirect('commande/commande.php');
    exit();
  }
}

//===========================================================
$title = 'Extranet MFP';
$titleLink = 'index.php';

$rapidAccess = [];
$menuItem = Commande::getRapidAccess();


echo Header::getHeader($title, $titleLink, $rapidAccess, $menuItem);?>

    <h1 class="m-3">Modification</h1>

<?php
$form = new Form;
echo $form->openForm();
echo $form->inputCard('acheteur','text','Acheteur :',$modif->user,'');
echo $commande->selectDealers($modif->fournisseur);
echo $commande->setOffre($modif->offre);
echo $commande->selectNomenclatures($modif->nomenclature);
echo $form->inputCard('quantite','number','Quantité :',$modif->quantite,'');
echo $form->inputCard('designation','text','Désigantion : ',$modif->designation,'');
echo $form->inputCard('reference','text','Référence : ',$modif->reference,'');
echo $form->inputCard('prix_unitaire','number','Prix Unitaire : ',$modif->prix_unitaire,'');
echo $form->inputCard('remise','number','Remise : ',$modif->remise,'');
echo $commande->checkCommande('commun','commun :',$modif->commun);
echo $form->textAreaCard('commentaire','Commantaire : ',$modif->comment);
echo $form->submit('primary','update','Modifier la commande');
echo $form->delete("FR");
echo $form->closeForm();


 ?>

<?= Footer::getFooter();
