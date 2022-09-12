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
if(isset($_POST['new'])){
  $errors=[];
  $validator = new Validator($_POST);
  $validator->isText('fournisseur',"Le nom du fournisseur n'est pas valide.");
  if($validator->isValid()){
    if(!empty($_FILES['document']['name'])){
      $up = new Upload('document','commande','document', "offre");
      $file = $up->upload();
    }
    else{
      $file = NULL;
    }
    $new = new Commande;
    if($new->newDealer(
      $_POST['fournisseur'],
      $_POST['representant'],
      $_POST['portable'],
      $_POST['email_representant'],
      $_POST['telephone'],
      $_POST['fax'],
      $_POST['email_commande'],
      $_POST['website'],
      $_POST['offre'],
      $_POST['frais'],
      $_POST['revendeur'],
      $_POST['annee'],
      $file
  )){
      Session::getInstance()->setFlash('success', "Nouveau fournisseur enregistré !");
      App::redirect('commande/fournisseurs.php');
      exit();
    }

  else{
      $errors = $validator->getErrors();
    }

  }

}
//===========================================================

//===========================================================
$title = 'Extranet MFP';
$titleLink = 'index.php';

$rapidAccess = [];
$menuItem = Commande::getRapidAccess();


echo Header::getHeader($title, $titleLink, $rapidAccess, $menuItem);
if(isset($validator)){echo $validator->afficheErrors($errors,"EN");}
?>

    <h1 class="m-3">Modification fournisseur</h1>

<?php
$form = new Form;
echo $form->openForm();
echo $form->inputCard('fournisseur','text','Fournisseur :','','');
echo $form->inputCard('representant','text','Représentant :','','');
echo $form->inputCard('portable','text','Portable :','','');
echo $form->inputCard('email_representant','email','E-mail Représentant :','','');
echo $form->inputCard('telephone','text','Téléphone :','','');
echo $form->inputCard('fax','text','Fax :','','');
echo $form->inputCard('email_commande','email','E-mail Commande :','','');
echo $form->inputCard('website','text','Site Internet :','','');
echo $form->inputCard('offre','text','Offre :','','');
echo $form->inputCard('frais','text','Frais :','','');
echo $form->inputCard('revendeur','text','Revendeur :','','');
echo $form->inputCard('annee','text','Année :','','');
echo $form->inputCard('document','file','Documentation :','',"Formats acceptés : Doc(X), PDF, Xls(X)");
echo $form->submit('primary','new','Ajouter nouveau fournisseur');
echo $form->closeForm();


 ?>

<?= Footer::getFooter();
