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
  $plasmide = new Plasmide();
  $mod = $plasmide->getSinglePlasmide($id);
}

if(!empty($_POST)){
  //var_dump($_POST);
  //die();

  if(isset($_POST['update'])){

  $errors =[];
  $db = App::getDatabase();
  $validator = new Validator($_POST);
  //$_POST = $validator->getProtectedData($_POST);

  $validator->isText('name', "The name is not valid.");
  $validator->isSelect('project', "You must select a project.");
  $project = "";
  foreach ($_POST["project"] as $proj){
    $project .= ",$proj";
  }
  $project = ltrim($project,",");

  $validator->isNumeric('size', "The size is not valid.");
  $validator->isText('antibiotic', "The antibiotic is not valid.");
  $validator->isSelect('investigator', "You must select an investigator.");
  if(!empty($_FILES['link_biomol']['name'])){
    $validator->isFileUniq('link_biomol','proparacyto','plasmide','biomol_files',$db, App::getTablePlasmides(), "This File already exists.");
  }
  if($validator->isValid()){
    if(!empty($_FILES['link_biomol']['name'])){
      $up = new Upload('link_biomol','proparacyto','plasmide','biomol_files');
      if(!empty($mod->link_biomol)){
        $del = new Upload('link_biomol','proparacyto','plasmide', 'biomol_files');
        $del->delete($mod->link_biomol);
      }
      $biomolFileName = $up->upload();
    }
    else{
      $biomolFileName = $mod->link_biomol;
    }
    if(isset($_POST['dna_stock'])){$dna = $_POST['dna_stock'];}else{$dna= NULL;}
    if(isset($_POST['glycerol_stock'])){$gly = $_POST['glycerol_stock'];}else{$gly = NULL;}
    $new = new Plasmide;
    if($plamide = $new->modifPlasmide(
      $id,
      $project,
      $_POST['name'],
      $_POST['size'],
      $_POST['antibiotic'],
      $_POST['type'],
      $_POST['fragment_cloned'],
      $_POST['cloning_vector'],
      $_POST['date'],
      $_POST['investigator'],
      $_POST['comments'],
      $dna,
      $gly,
      $biomolFileName
    )){
      Session::getInstance()->setFlash('success', "Plasmide updated !");
      App::redirect('proparacyto/plasmide/');
      exit();
    }
  }
  else{
      $errors = $validator->getErrors();
    }
  }
  if(isset($_POST['delete'])){
    $delete = new Plasmide;
    if($delete->deletePlasmide($id)){
      $del = new Upload('link_biomol','proparacyto','plasmide', 'biomol_files');
      $del->delete($mod->link_biomol);
      Session::getInstance()->setFlash('success', "Plasmide deleted !");
      App::redirect('proparacyto/plasmide/');
      exit();
    }
  }
}
//===========================================================
$rapidAccess = TeamProparacyto::getRapidAccess();
$menuItem = [];

$title = "ProParaCyto";
$titleLink = 'proparacyto/index.php';

echo Header::getHeader($title, $titleLink, $rapidAccess, $menuItem);

if(isset($validator)){echo $validator->afficheErrors($errors,"EN");}

if($mod->type === 'cell'){
  $displayType = "Cell Line";
  $cell = true;
}
elseif($mod->type === "plasmide"){
  $displayType = "Plasmide";
  $plas = true;
}

echo '<h1 class="mt-3">Update '.$displayType.'</h1>';


$form = new cskForm($_POST);

echo "<form method='post' action='' enctype='multipart/form-data'>";

echo $form->input('name', 'text', 'Name : ', "$mod->name", "Should be precise and easy to read/understand.");
echo $form->project_select('project', 'Project : ', ' Choose a project ', $mod->id_project);
echo $form->input('size','number','Size in base pair : ',"$mod->size");
echo $form->input('antibiotic', 'text', 'Antibiotic(s) : ', "$mod->antibiotic");
echo $form->radioInlineCheck('type','cell','Cell line',$cell);
echo $form->radioInlineCheck('type', 'plasmide', 'Plasmide', $plas);
echo $form->textArea('fragment_cloned','Purpose : ',"$mod->fragment_cloned");
echo $form->input('cloning_vector', 'text', 'Original vector : ', "$mod->cloning_vector");
echo $form->input('date','date', 'Date : ', $mod->date);
echo $form->investigator_select('investigator','Investigator : ','Choose an investigator', $mod->investigator);
echo $form->textArea('comments','Comments : ',$mod->comments);
if($mod->type === 'plasmide'){
if($mod->dna_stock === "X"){$checked = 'checked';}else{$checked ='';}
echo $form->checkBox('dna_stock','dna','X','DNA stock', $checked);
if($mod->glycerol_stock === "X"){$checked = 'checked';}else{$checked ='';}
echo $form->checkBox('glycerol_stock', 'gly','X','Glycerol stock', $checked);
}
if(!empty($mod->link_biomol)){echo "<div class='alert alert-info'>This plasmide has already a datafile : $mod->link_biomol</div>";}
echo $form->input('link_biomol','file', "Biomol file : ","","Supported format : .xdna, .dna");
echo $form->submit('primary','update','Update');
echo $form->delete();
echo "</form>";




?>
<?= Footer::getFooter();
