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
if(isset($_GET['type'])){
  $type = $_GET['type'];
  if($type === 'cell'){$displayType = "Cell Line";}
  elseif($type === 'plasmide'){$displayType = "Plasmide";}
  else{$displayType ="Hello";}
}
else{
  App::redirect('proparacyto/plasmide/index.php');
  exit();
}
//===========================================================
// verification des datas du formulaire
if(!empty($_POST)){

  $errors =[];
  $db = App::getDatabase();
  $validator = new Validator($_POST);

  $validator->isText('name', "The name is not valid.");
  if($validator->isValid()){
    $validator->isUniq('name', $db, App::getTablePlasmides(), "The plasmide already exists.");
  }
  $validator->isSelect('project', "You must select a project.");
  if($validator->isValid()){
  $project = "";
  foreach ($_POST["project"] as $proj){
    $project .= ",$proj";
  }
  $project = ltrim($project,",");
  }
  $validator->isNumeric('size', "The size is not valid.");
  $validator->isText('antibiotic', "The antibiotic is not valid.");
  $validator->isSelect('investigator', "You must select an investigator.");
  if(!empty($_FILES['link_biomol']['name'])){
    $validator->isFileUniq('link_biomol','proparacyto','plasmide','biomol_files',$db, App::getTablePlasmides(), "This File already exists.");
  }
  if($validator->isValid()){
    if(!empty($_FILES['link_biomol']['name'])){
      $up = new Upload('link_biomol','proparacyto','plasmide','biomol_files');
      $biomolFileName = $up->upload();
    }
    else{
      $biomolFileName = NULL;
    }
    if(isset($_POST['dna_stock'])){$dna = $_POST['dna_stock'];}else{$dna= NULL;}
    if(isset($_POST['glycerol_stock'])){$gly = $_POST['glycerol_stock'];}else{$gly = NULL;}
    $new = new Plasmide();
    if($plamide = $new->newPlasmide(
      $type,
      $project,
      $_POST['name'],
      $_POST['size'],
      $_POST['antibiotic'],
      $_POST['fragment_cloned'],
      $_POST['cloning_vector'],
      $_POST['date'],
      $_POST['investigator'],
      $_POST['comments'],
      $dna,
      $gly,
      $biomolFileName
    )){
      Session::getInstance()->setFlash('success', "Plasmide recorded !");
      App::redirect('proparacyto/plasmide/index.php');
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


echo '<h1 class="mt-3">New '.$displayType.'</h1>';


$form = new cskForm($_POST);

echo "<form method='post' action='' enctype='multipart/form-data'>";
echo $form->input('name', 'text', 'Name : ', '', "Should be precise and easy to read/understand.");
echo $form->project_select('project', 'Project : ', ' Choose a project ','');
echo $form->input('size','number','Size in base pair : ','');
echo $form->input('antibiotic', 'text', 'Antibiotic(s) : ', '');
echo $form->textArea('fragment_cloned','Purpose : ','');
echo $form->input('cloning_vector', 'text', 'Original vector : ', '');
echo $form->input('date','date', 'Date : ', date("Y-m-d"), "Default : today's date");
echo $form->investigator_select('investigator','Investigator : ','Choose an investigator', $user->id);
echo $form->textArea('comments','Comments : ','');
if($type==='plasmide'){
echo $form->checkBox('dna_stock','dna','X','DNA stock','');
echo $form->checkBox('glycerol_stock', 'gly','X','Glycerol stock','');
}
echo $form->input('link_biomol','file', "Biomol file : ",'',"Supported format : .xdna, .dna");
echo $form->button('submit', 'primary', 'Add this new plasmide');

echo "</form>";

?>
<?= Footer::getFooter();
