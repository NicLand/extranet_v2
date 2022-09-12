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
if(isset($_GET['t']) && $_GET['t'] !=''){
  $t = $_GET['t'];
  if(isset($_GET['id']) && $_GET['id'] !=''){
    $id = $_GET['id'];
    $produit = new Commande;
    $dealers = $produit->distinctDealer($t);
    $modif = $produit->getSingleCommun($t,$id);
  }
}


if(isset($_POST['update'])){
  $errors =[];
  $validator = new Validator($_POST);
    $validator->isSelect('dealer',"Veuillez choisir un fournisseur.");
    $validator->isSelect('nomenclature','Veuillez saisir une nomenclature.');
    $validator->isText('reference', "Veuillez saisir une référence.");
    $validator->isText('designation', "Veuillez saisir une désignation.");
    $validator->isNumeric('prix_u', "Veuillez saisir un Prix Unitaire.");
    if($validator->isValid()){
      foreach($_POST as $index => $valeur) {
         $$index = $valeur;
      }
      $prix_u = floatval($prix_u);
      if($produit->upSingleCommun($t,$id,$dealer,$nomenclature,$designation,$reference,$prix_u, $gauss,$conditionnement)){
        Session::getInstance()->setFlash('success',"La référence a été modifiée !");
        $link = "commande/commun.php?t=$t";
        App::redirect($link);
        exit();
      }
    }
    else{
      $errors = $validator->getErrors();
    }
}
if(isset($_POST['delete'])){
  if($produit->delSingleCommun($t,$id)){
    Session::getInstance()->setFlash('success',"La référence a été supprimée !");
    $link = "commande/commun.php?t=$t";
    App::redirect($link);
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
echo $produit->selectDearlerCommun($dealers,$modif->fournisseur);
echo $produit->selectNomenclatures($modif->nomenclature);
echo $form->inputCard('designation','text','Désigantion : ',$modif->designation,'');
echo $form->inputCard('reference','text','Référence : ',$modif->reference,'');
echo $form->inputCard('gauss','text','Référence GAUSS : ',$modif->gauss,'');
echo $form->inputCard('conditionnement','text','Conditionnement : ',$modif->conditionnement,'');
echo $form->inputCard('prix_u','number','Prix Unitaire : ',$modif->prix_u);
echo $form->submit('primary','update','Modifier la référence');
echo $form->delete("FR");
echo $form->closeForm();
?>

<?= Footer::getFooter();
