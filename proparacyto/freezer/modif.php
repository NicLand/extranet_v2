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
  App::redirect('proparacyto/freezer/index.php');
  exit();
}
if(!empty($_POST)){
  if(isset($_POST['freeze'])){
  $errors = [];
  $validator = new Validator($_POST);

  $validator->isText('tube',"The name of the tube is not valid.");
  $validator->isSelect('investigator', "Choose an investigator.");
  $validator->isSelect('project', "Choose a project.");
  $project = "";
  foreach ($_POST["project"] as $proj){
    $project .= ",$proj";
  }
  $project = ltrim($project,",");

  $validator->isDate('date', "The date is not valid.");
  if($validator->isValid()){
    $newtube = new Freezer;
    if($newtube->newTube(
      $id,
      $_POST['tube'],
      $project,
      $_POST['investigator'],
      $_POST['date'],
      $_POST['commentaire_azote'])){
        Session::getInstance()->setFlash('success',"The tube is recorded.");
        App::redirect('proparacyto/freezer/index.php');
        exit();
      }
    }
    else{
      $errors = $validator->getErrors();
    }
  }
  if(isset($_POST['delete'])){
    $defreeze = new Freezer;
    if($defreeze->deFreeze($id)){
      Session::getInstance()->setFlash('success', "Tube defrosted !");
      App::redirect('proparacyto/freezer/index.php');
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

$freezer = new Freezer;
$tube = $freezer->getSingleTube($id);
if(empty($tube->tube)){
  $button = "New tube";
  $details = "New tube @ Freezer : $tube->freezer - Rack : $tube->rack - Box : $tube->box";
}
else{
  $button = "Update tube";
  $details = "Update tube @ Freezer : $tube->freezer - Rack : $tube->rack - Box : $tube->box";
}
$tub = $tube->tube;
$investigator = $tube->investigator;
$project = $tube->id_project;
$project2 = $tube->id_project2;
$date= $tube->date;
$comment = $tube->comment;


echo "<h1 class='mt-3'>$details</h1>";
echo "<form action='' method='post'>";
$form = new cskForm($_POST);
echo $form->input('tube', 'text', "Name of the tube : ", $tub);
echo $form->project_select('project', "Project : ", "Choose a project", $project);
echo $form->investigator_select('investigator',"Investigator : ","Choose an investigator", $investigator);
echo $form->input('date','date',"Date : ",$date);
echo $form->textArea('commentaire_azote', "Comments : ",$comment);
echo $form->submit('primary','freeze', $button);
if(!empty($tube->tube)){
echo $form->delete();
}

echo "</form>";
?>

<?= Footer::getFooter();
