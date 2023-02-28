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

if(isset($_GET['t']) && $_GET['t'] !=""){$id = $_GET['t'];}else{}
$cat = new Project;
$projType = $cat->getProjectTypeByList($id);

if(!empty($_POST)){
  $errors=[];
  //var_dump($_POST);
  if(isset($_POST['update'])){
  $validator = new Validator($_POST);
  $validator->isText("type", "The project's category is not valid.");
  $validator->isSelect('color', "Please choose a color for the category of project.");
  if($validator->isValid()){
    $type = new Project();
    $type->upProjectType($id,$_POST['type'],$_POST['color'],'white',$_POST['description']);
    Session::getInstance()->setFlash('success', 'The new category is recorded.');
    App::redirect('proparacyto/project/index.php');
  }
  else{
      $errors = $validator->getErrors();
    }

}
if(isset($_POST['delete'])){
  $delete = new Project;
  if($delete->delProjectType($id)){
    Session::getInstance()->setFlash('success', "The Category has been deleted !");
    App::redirect('proparacyto/project/index.php');
    exit();
  }
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
echo $form->inputInline('type', 'text', 'Category of Projects : ',$projType->type);
echo $form->textArea('description','Description :',$projType->description);
echo $form->selectColor("color", "Color : ", "Choose a color",$projType->color);
echo $form->submit('primary','update','Update this Category');
echo $form->delete();
echo "</form>";

echo Footer::getFooter();
