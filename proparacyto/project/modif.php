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
if(isset($_GET['id'])){
  $id = $_GET['id'];
  $project = new Project();
  $mod = $project->getProject($id)->fetch();
}

if(!empty($_POST)){
  if(isset($_POST['update'])){
  $errors=[];
  $validator = new Validator($_POST);
  $validator->isText('project', 'The project name is not valid.');

  $validator->isText('accession', "The accession number is not valid.");

  $validator->isSelect('type', "Please choose a type of project.");
  $validator->isSelect('investigator', "Please choos the principal investigator.");

  if($validator->isValid()){
    $project = new Project();
    $project->updateProject($id, $_POST['project'],$_POST['accession'],$_POST['type'],$_POST['investigator'],$_POST['associate'],$_POST['comment']);
    Session::getInstance()->setFlash('success', 'The project has been updated.');
    App::redirect('proparacyto/project/list.php');
    exit();
  }
  else{
      $errors = $validator->getErrors();
    }
}
if(isset($_POST['delete'])){
  $delete = new Project;
  if($delete->deleteProject($id)){
    Session::getInstance()->setFlash('success', "The Project has been deleted !");
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

<h1 class="mt-3"><?= $title;?></h1>
<?php

$form = new cskForm($_POST);
echo "<form method='post' action='#'>";
echo $form->inputInline('project', 'text', 'Project Name : ',$mod->project,'');
echo $form->inputInline('accession', 'text', 'Accession number : ', $mod->accession);
echo $form->type_select("type", "Project Type", "Choose a type", $mod->type);
echo $form->investigator_select("investigator", "Investigator : ", "Choose an investigator", $mod->investigator);
echo $form->inputInline('associate','text','Associate :',$mod->associate,'');
echo $form->textArea('comment', 'Comments :', $mod->comment);
echo $form->submit('primary','update','Update this project');
echo $form->delete();
echo "</form>";

echo Footer::getFooter();
