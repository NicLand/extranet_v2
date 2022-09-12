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
  //var_dump($_POST);
  $validator = new Validator($_POST);
  $validator->isText('project', 'The project name is not valid.');
  if($validator->isValid()){
    $db = new Database();
    $validator->isUniq('project',$db, App::getTableProjects(), "The project is already into the project's list.");
  }
  $validator->isText('accession', "The accession number is not valid.");
  if($validator->isValid()){
    $db = new Database();
    $validator->isUniq('accession',$db, App::getTableProjects(), "The accession number is already into the project's list.");
  }
  $validator->isSelect('type', "Please choose a type of project.");
  $validator->isSelect('investigator', "Please choos the principal investigator.");

  if($validator->isValid()){
    $project = new Project();
    $project->newProject($_POST['project'],$_POST['accession'],$_POST['type'],$_POST['investigator'],$_POST['associate'],$_POST['comment']);
    Session::getInstance()->setFlash('success', 'The new project is recorded.');
    App::redirect('proparacyto/project/list.php');
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

<h1 class="mt-3"><?= $title;?></h1>
<?php

$form = new cskForm($_POST);
echo "<form method='post' action='#'>";
echo $form->inputInline('project', 'text', 'Project Name : ','');
echo $form->inputInline('accession', 'text', 'Accession number : ','');
echo $form->type_select("type", "Project Type", "Choose a type",'');
echo $form->investigator_select("investigator", "Investigator : ", "Choose an investigator", $user->id);
echo $form->inputInline('associate', 'text', 'Associate : ','');
echo $form->textArea('comment', 'Comments :','');
echo $form->button('submit','primary', 'Add this project');
echo "</form>";

echo Footer::getFooter();
