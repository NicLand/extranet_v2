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
  $c = new Commande;
  $dealer = $c->getDealer($id);
}
else{
  App::redirect('commande/fournisseurs.php');
  exit();
}
//===========================================================

if(isset($_POST['update'])){

$errors =[];
$validator = new Validator($_POST);
$validator->isText('fournisseur', "The name is not valid.");
if($validator->isValid()){
  if(!empty($_FILES['document']['name'])){
    $up = new Upload('document','commande','document', "offre");
    if(!empty($dealer->offre_sheet)){
      $del = new Upload('document','commande','document', "offre");
      $del->delete($dealer->offre_sheet);
    }
    $file = $up->upload();
  }
  else{
    $file = $dealer->offre_sheet;
  }
  $upDealer = new Commande;
  if($upDealer->upDealer(
    $id,
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
    Session::getInstance()->setFlash('success', "Fournisseur modifié !");
    App::redirect('commande/fournisseurs.php');
    exit();
  }
}
else{
    $errors = $validator->getErrors();
  }
}
if(isset($_POST['delete'])){
  $delete = new Commande;
  if($delete->delDealer($id)){
    $del = new Upload('document','commande','document', "offre");
    $del->delete($dealer->offre_sheet);
    Session::getInstance()->setFlash('success', "Fournisseur supprimé !");
    App::redirect('commande/fournisseurs.php');
    exit();
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
echo $form->inputCard('fournisseur','text','Fournisseur :',$dealer->fournisseur,'');
echo $form->inputCard('representant','text','Représentant :',$dealer->representant,'');
echo $form->inputCard('portable','text','Portable :',$dealer->portable,'');
echo $form->inputCard('email_representant','email','E-mail Représentant :',$dealer->email_representant,'');
echo $form->inputCard('telephone','text','Téléphone :',$dealer->telephone,'');
echo $form->inputCard('fax','text','Fax :',$dealer->fax,'');
echo $form->inputCard('email_commande','email','E-mail Commande :',$dealer->email_commande,'');
echo $form->inputCard('website','text','Site Internet :',$dealer->website,'');
echo $form->inputCard('offre','text','Offre :',$dealer->offre,'');
echo $form->inputCard('frais','text','Frais :',$dealer->frais,'');
echo $form->inputCard('revendeur','text','Revendeur :',$dealer->revendeur,'');
echo $form->inputCard('annee','text','Année :',$dealer->annee,'');
if(!empty($dealer->offre_sheet)){echo "<div class='alert alert-info'>Ce fournisseur a déjà une offre : $dealer->offre_sheet</div>";}
echo $form->inputCard('document','file','Documentation :','',"Formats acceptés : Doc(X), PDF, Xls(X)");
echo $form->submit('primary','update','Modifier fournisseur');
echo $form->delete("FR");
echo $form->closeForm();


 ?>

<?= Footer::getFooter();
