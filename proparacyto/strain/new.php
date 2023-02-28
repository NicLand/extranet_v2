<?php
//===========================================================
//Charge automatiquemebnt les Class utilisées dans les pages
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
$access = new Access("proparacyto", $user);
if(!$access->access()){
  Session::setFlash('danger', "Vous n'êtes pas autorisé à accéder à cette page.");
  App::redirect('index.php');
  exit();
}
//===========================================================

if(!empty($_POST)){
  var_dump($_POST);
  $validator = new Validator($_POST);
  $validator->isText('name',"the name is not valid");
  $db = new Database;
  $validator->isUniq('name',$db,App::getTableStrains(),"This strain already exists.");
  if($validator->isValid()){
    $new = new Strain;
    if($new->newStrain($_POST['name'],$_POST['origin'],$_POST['paper'], $_POST['groupe'], $_POST['comment'])){
      Session::getInstance()->setFlash('success', "Strain recorded !");
      App::redirect('proparacyto/strain/');
      exit();
    }
  }
  else{
    $errors = $validator->getErrors();
  }
}

//===========================================================

$rapidAccess = TeamProparacyto::getRapidAccess();
$menuItem = [];

$title = 'ProParaCyto';
$titleLink = 'proparacyto/index.php';

echo Header::getHeader($title, $titleLink, $rapidAccess, $menuItem);
if(isset($validator)){echo $validator->afficheErrors($errors,"EN");}

?>

<h1 class="mt-3">New Strain</h1>
<?php

$form = new cskForm($_POST);
echo "<form method='post' action='#' enctype='multipart/form-data'>";

echo $form->input('name','text',"Name : ",'');
echo $form->input('origin','text',"From : ",'');
echo $form->textArea('paper',"Related Paper : ",'');
echo $form->input('groupe','text',"Group :",'');
echo $form->textArea('comment',"Comment : ",'');
echo $form->submit('primary','new','Add new strain');

echo "</form>";
?>
<?= Footer::getFooter();
