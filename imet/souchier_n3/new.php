<?php
//===========================================================
//Charge automatiquemebnt les Class utilis�es dans les pages
// Dans le bon __NAMESPACE__
namespace Extranet;

require '../../class/Autoloader.php';
Autoloader::register();

$auth = App::getAuth();
$user = $auth->user();

if(!$user){
  Session::setFlash('danger', "Veuillez vous identifier.");
  App::redirect('login.php');
  exit();
}
$access = new Access("imet", $user);
if(!$access->access()){
  Session::getInstance()->setFlash('danger', "Vous n'�tes pas autoris� � acc�der � cette page.");
  App::redirect('../index.php');
  exit();
}
//===========================================================
if(!empty($_POST)){
  var_dump($_POST);
  $validator = new Validator($_POST);
  if($validator->isValid()){
    $new = new TeamIMet;
    if($new->newSouche($_POST['boite'],$_POST['numero'],$_POST['plasmide'],$_POST['insert'],$_POST['souche'],$_POST['date'],$_POST['investigateur'],$_POST['antibio'],$_POST['commentaire'])){
      Session::getInstance()->setFlash('success', "Souche sauvegardee !");
      App::redirect('imet/souchier_n3/');
      exit();
    }
  }
  else{
    $errors = $validator->getErrors();
  }
}

//===========================================================
$rapidAccess = TeamIMet::getRapidAccess();
$menuItem = [];

$title = 'iMET';
$titleLink = 'imet/index.php';

echo Header::getHeader($title, $titleLink, $rapidAccess, $menuItem);
if(isset($validator)){echo $validator->afficheErrors($errors);}
?>

<h1 class="mt-3">Nouvelle Souche</h1>
<?php

$form = new TeamIMetForm($_POST);
echo "<form method='post' action='#' enctype='multipart/form-data'>";

echo $form->input('boite','number',"Boite : ",'');
echo $form->input('numero','number',"Numero : ",'');
echo $form->input('plasmide','text',"Plasmide : ",'');
echo $form->input('insert','text',"Insert (bp) : ",'');
echo $form->selectSouche();
echo $form->input('date','date',"Date : ",'');
echo $form->input('investigateur','text','Investigateur :','');
echo $form->selectAntibio();
echo $form->textArea('commentaire','Commentaires :','');
echo $form->submit('primary','new','Ajouter');

echo "</form>";
?>
<?php echo Footer::getFooter();?>
