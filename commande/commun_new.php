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
    $produit = new Commande;
    $dealers = $produit->distinctDealer($t);
  }


if(isset($_POST['new'])){
  var_dump($_POST);
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
      if($produit->newSingleCommun($t,$dealer,$nomenclature,$designation,$reference,$prix_u,$gauss,$conditionnement)){
        Session::getInstance()->setFlash('success',"La référence a été enregistrée !");
        $link = "commande/commun.php?t=$t&p=$dealer";
        App::redirect($link);
        exit();
      }
    }
    else{
      $errors = $validator->getErrors();
    }
}
//===========================================================
$title = 'Extranet MFP';
$titleLink = 'index.php';

$rapidAccess = [];
$menuItem = Commande::getRapidAccess();


echo Header::getHeader($title, $titleLink, $rapidAccess, $menuItem);
if(isset($validator)){
  echo $validator->afficheErrors($errors);
}

?>

    <h1 class="m-3">Nouvelle référence</h1>

<?php
$form = new Form;
echo $form->openForm();
echo $produit->selectDearlerCommun($dealers,'');
echo $produit->selectNomenclatures('');
echo $form->inputCard('designation','text','Désignation : ','','');
echo $form->inputCard('reference','text','Référence : ','','');
echo $form->inputCard('gauss','text','Référence GAUSS : ','','');
echo $form->inputCard('conditionnement','text','Conditionnement : ','','');
echo $form->inputCard('prix_u','number','Prix Unitaire : ','','');
echo $form->submit('primary','new','Enregistrer la référence');
echo $form->closeForm();
?>

<?= Footer::getFooter();
