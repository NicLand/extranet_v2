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
if(isset($_GET['id'])){
  $id = $_GET['id'];
}
else{
  App::redirect('proparacyto/azote/box.php');
  exit();
}
if(!empty($_POST)){
  if(isset($_POST['freeze'])){
  $errors = [];
  //var_dump($_POST);
  $validator = new Validator($_POST);
  //var_dump($errors);
  $validator->isText('tube',"The name of the tube is not valid.");
  //$validator->isText('souche', "The strain is not valid.");
  //$validator->isText('clonality', "The clonality is not valid.");
  $validator->isSelect('investigator', "Choose an investigator.");
  $validator->isSelect('project', "Choose a project.");
  $project = "";
  foreach ($_POST["project"] as $proj){
    $project .= ",$proj";
  }
  $project = ltrim($project,",");
  $validator->isDate('date', "The date is not valid.");
  if($validator->isValid()){
    $newVial = new Azote;
    if($newVial->newTube(
      $id,
      $_POST['tube'],
      $_POST['souche'],
      $_POST['clonality'],
      $_POST['investigator'],
      $project,
      $_POST['date'],
      $_POST['commentaire_azote'])){
        Session::getInstance()->setFlash('success',"The vial is recorded.");
        App::redirect('proparacyto/azote/index.php');
        exit();
      }
    }
    else{
      $errors = $validator->getErrors();
    }
  }
  if(isset($_POST['delete'])){
    $defreeze = new Azote;
    if($defreeze->deFreeze($id)){
      Session::getInstance()->setFlash('success', "Vial defrosted !");
      App::redirect('proparacyto/azote/index.php');
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

$azote = new Azote;
$vial = $azote->getSingleTube($id);
if(empty($vial->plasmide) && empty($vial->souche)){
  $button = "New Vial";
}
else{
  $button = "Update Vial";
}
$position = "Container : $vial->container | Rack : $vial->tige | Box : $vial->box | Place : $vial->place";
$tube = $vial->plasmide;
$souche=$vial->souche;
$clonality= $vial->clonality;
$investigator= $vial->investigator;
$project= $vial->id_project;
$date= $vial->date;
$commentaire_azote = $vial->commentaire_azote;



echo "<h1 class='mt-3'>$button</h1>";
echo "<form action='' method='post'>";
$form = new cskForm($_POST);
echo "<p>Position : $position</p>";
echo $form->input('tube', 'text', "Name of the vial : ", $tube);
echo $form->input('souche', 'text', "Strain : ",$souche);
echo $form->input('clonality', 'text', "Clonality : ",$clonality);
echo $form->investigator_select('investigator',"Investigator : ","Choose an investigator", $investigator);
echo $form->project_select('project', "Project : ", "Choose a project", $project);
echo $form->input('date','date',"Date : ",$date);
echo $form->textArea('commentaire_azote', "Comments : ",$commentaire_azote);
echo $form->submit('primary','freeze', $button);
echo $form->delete();
//echo $form->submit('secondary','defreeze', "Defreeze the vial");

echo "</form>";
?>

<?= Footer::getFooter();
