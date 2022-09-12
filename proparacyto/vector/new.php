<?php
//===========================================================
//Charge automatiquemebnt les Class utilis�es dans les pages
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
  Session::getInstance()->setFlash('danger', "Vous n'�tes pas autoris� � acc�der � cette page.");
  App::redirect('index.php');
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
      $validator->isUniq('name', $db, App::getTableVectors(), "The vector already exists.");
    }
    $validator->isNumeric('size', "The size is not valid.");
    $validator->isText('antibiotic', "The antibiotic is not valid.");
    $validator->isText('investigator', "The investigator name is not valid.");
    if(!empty($_FILES['link_biomol']['name'])){
      $validator->isFileUniq('link_biomol','proparacyto', 'vector', 'biomol',$db, App::getTableVectors(), "This File already exists.");
    }
    if(!empty($_FILES['link_pdf']['name'])){
      $validator->isFileUniq('link_pdf','proparacyto', 'vector', 'pdf',$db, App::getTableVectors(), "This File already exists.");
    }
    if($validator->isValid()){
      if(!empty($_FILES['link_biomol']['name'])){
        $up = new Upload('link_biomol','proparacyto','vector','biomol');
        $biomolFileName = $up->upload();
      }
      else{
        $biomolFileName = NULL;
      }
      if(!empty($_FILES['link_pdf']['name'])){
        $up2 = new Upload('link_pdf','proparacyto','vector','pdf');
        $pdfFileName = $up2->upload();
      }
      else{
        $pdfFileName = NULL;
      }
      $new = new Vector;
      if($vector = $new->newVector(
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
        Session::getInstance()->setFlash('success', "Vector recorded !");
        App::redirect('proparacyto/vector/');
        exit();
      }
      else{
      Session::getInstance()->setFlash('danger', "Something went wrong, please try again.");
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

echo '<h1 class="mt-3">New Cloning Vector</h1>';

$form = new cskForm($_POST);

echo "<form method='post' action='' enctype='multipart/form-data'>";

echo $form->input('name', 'text', 'Name : ', '');
echo $form->input('size','number','Size in base pair : ','');
echo $form->input('antibiotic', 'text', 'Antibiotic(s) : ', '');
echo $form->textArea('fragment_cloned','Purpose : ','');
echo $form->input('cloning_vector', 'text', 'Original vector : ', '');
echo $form->input('investigator','text','Investigator : ','');
echo $form->textArea('comments','Comments : ','');
echo $form->input('link_biomol','file', "Biomol file : ",'',"Supported format : .xdna, .dna");
echo $form->input('link_pdf', 'file', "PDF file", '', 'Supported format : .pdf');
echo $form->button('submit', 'primary', 'Add this new vector');

echo "</form>";


echo Footer::getFooter();
