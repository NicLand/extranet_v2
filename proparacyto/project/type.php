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
$rapidAccess = TeamProparacyto::getRapidAccess();
$menuItem = [];

if(!empty($_POST)){
  $errors=[];
  var_dump($_POST);
  $validator = new Validator($_POST);
  $validator->isText("type", "The project's category is not valid.");
  if($validator->isValid()){
    $db = new Database();
    $validator->isUniq('type',$db, App::getTableProjectTypes(), "The project's category already exists.");
  }
  $validator->isSelect('color', "Please choose a color for the category of project.");
  if($validator->isValid()){
    $type = new Project();
    $type->newProjectType($_POST['type'],$_POST['color'],'white',$_POST['description']);
    Session::getInstance()->setFlash('success', 'The new category is recorded.');
    App::redirect('proparacyto/project/');
  }
  else{
      $errors = $validator->getErrors();
    }

}

$title = 'ProParaCyto';
$titleLink = 'proparacyto/index.php';

echo Header::getHeader($title, $titleLink, $rapidAccess, $menuItem);
if(isset($validator)){echo $validator->afficheErrors($errors,"EN");}

?>

<h1 class="mt-3">Project's Category</h1>
<?php

$form = new cskForm($_POST);
echo "<form method='post' action='#'>";
echo $form->inputInline('type', 'text', 'Category of Projects : ','');
echo $form->textArea('description','Description :','');
echo $form->selectColor("color", "Color : ", "Choose a color");
echo $form->button('submit','primary', 'Add this project');
echo "</form>";

echo Footer::getFooter();
