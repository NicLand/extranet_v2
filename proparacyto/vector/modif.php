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
  $vector = new Vector;
  $mod = $vector->getSingle($id);
}

if(!empty($_POST)){
  if(isset($_POST['update'])){

  $errors =[];
  $db = App::getDatabase();
  $validator = new Validator($_POST);

  $validator->isText('name', "The name is not valid.");
  $validator->isNumeric('size', "The size is not valid.");
  $validator->isText('antibiotic', "The antibiotic is not valid.");
  if(!empty($_FILES['link_biomol']['name'])){
    $validator->isFileUniq('link_biomol','proparacyto','vector','biomol',$db, App::getTableVectors(), "This File already exists.");
  }
  if(!empty($_FILES['link_pdf']['name'])){
    $validator->isFileUniq('link_pdf','proparacyto','vector','pdf',$db, App::getTableVectors(), "This File already exists.");
  }
  if($validator->isValid()){
    if(!empty($_FILES['link_biomol']['name'])){
      $up = new Upload('link_biomol','proparacyto','vector','biomol');
      if(!empty($mod->link_biomol)){
        $del = new Upload('link_biomol','proparacyto','vector', 'biomol');
        $del->delete($mod->link_biomol);
      }
      $biomolFileName = $up->upload();
    }
    else{
      $biomolFileName = $mod->link_biomol;
    }
    if(!empty($_FILES['link_pdf']['name'])){
      $up2 = new Upload('link_pdf','proparacyto','vector','pdf');
      if(!empty($mod->link_pdf)){
        $del = new Upload('link_pdf','proparacyto','vector', 'pdf');
        $del->delete($mod->link_pdf);
      }
      $pdfFileName = $up2->upload();
    }
    else{
      $pdfFileName = $mod->link_pdf;
    }
    $new = new Vector;
    if($vector = $new->modifVector(
      $id,
      $_POST['name'],
      $_POST['size'],
      $_POST['antibiotic'],
      $_POST['fragment_cloned'],
      $_POST['cloning_vector'],
      $_POST['investigator'],
      $_POST['comments'],
      $pdfFileName,
      $biomolFileName
    )){
      Session::getInstance()->setFlash('success', "Vector updated !");
      App::redirect('proparacyto/vector/');
      exit();
    }
  }
  else{
      $errors = $validator->getErrors();
    }
  }
  if(isset($_POST['delete'])){
    $delete = new Vector;
    if($delete->deleteVector($id)){
      $del = new Upload('link_biomol','proparacyto','vector', 'biomol');
      $del->delete($mod->link_biomol);
      $del2 = new Upload('link_pdf','proparacyto','vector','pdf');
      $del2->delete($mod->link_pdf);
      Session::getInstance()->setFlash('success', "Vector deleted !");
      App::redirect('proparacyto/vector/');
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

echo '<h1 class="mt-3">Update plasmide</h1>';


$form = new cskForm($_POST);

echo "<form method='post' action='' enctype='multipart/form-data'>";

echo $form->input('name', 'text', 'Name : ', "$mod->name", "Should be precise and easy to read/understand.");
echo $form->input('size','number','Size in base pair : ',"$mod->size");
echo $form->input('antibiotic', 'text', 'Antibiotic(s) : ', "$mod->antibiotic");
echo $form->textArea('fragment_cloned','Purpose : ',"$mod->fragment_cloned");
echo $form->input('cloning_vector', 'text', 'Original vector : ', "$mod->cloning_vector");
echo $form->input('investigator','text','Investigator : ',$mod->investigator);
echo $form->textArea('comments','Comments : ',$mod->comments);
echo $form->input('link_biomol','file', "Biomol file : ","$mod->link_biomol","Supported format : .xdna, .dna");
if(!empty($mod->link_biomol)){echo "<div class='alert alert-info'>This plasmide has already a datafile : $mod->link_biomol</div>";}
echo $form->input('link_pdf', 'file', "Documentation : ","$mod->link_pdf", "Supported format : .pdf");
if(!empty($mod->link_pdf)){echo "<div class='alert alert-info'>This plasmide has already a documentation : $mod->link_pdf</div>";}
echo $form->submit('primary','update','Update');
echo $form->delete();
echo "</form>";

?>
<?= Footer::getFooter();
