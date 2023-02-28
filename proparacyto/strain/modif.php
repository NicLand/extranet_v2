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
if(isset($_GET['id']) && $_GET['id'] !=""){
  $id = $_GET['id'];
  $strain = new Strain;
  $mod = $strain->getSingleStrain($id);
}
else{
  App::redirect('proparacyto/strain');
  exit();
}
//===========================================================
if(!empty($_POST)){
  if(isset($_POST['update'])){
  $validator = new Validator($_POST);
  $validator->isText('name',"the name is not valid");
  if($validator->isValid()){
    $new = new Strain;
    if($new->upStrain($id,$_POST['name'],$_POST['origin'],$_POST['paper'], $_POST['groupe'], $_POST['comment'])){
      Session::getInstance()->setFlash('success', "Strain updated !");
      App::redirect('proparacyto/strain/');
      exit();
    }
  }
  else{
    $errors = $validator->getErrors();
  }
}
  if(isset($_POST['delete'])){
    $del = new Strain;
    if($del->delStrain($id)){
      Session::getInstance()->setFlash('success', "Strain deleted !");
      App::redirect('proparacyto/strain/');
      exit();
    }
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

<h1 class="mt-3">Update Strain</h1>
<?php

$form = new cskForm($_POST);
echo "<form method='post' action='#' enctype='multipart/form-data'>";

echo $form->input('name','text',"Name : ",$mod->name);
echo $form->input('origin','text',"From : ",$mod->origin);
echo $form->textArea('paper',"Related Paper : ",$mod->paper);
echo $form->input('groupe','text',"Group :",$mod->groupe);
echo $form->textArea('comment',"Comment : ",$mod->comment);
echo $form->submit('primary','update','Update the strain');
echo $form->delete();

echo "</form>";
?>
<?= Footer::getFooter();
