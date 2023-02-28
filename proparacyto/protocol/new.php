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
if(isset($_GET['p']) && strlen($_GET['p']) == 3){
  $p = $_GET['p'];
  if($p == 'cat'){$h1 = "New Protocol category";}
  if($p == 'pro'){$h1 = "New Protocol";}
}
else{
  App::redirect('proparacyto/protocol/');
  exit();
}
//===========================================================
$rapidAccess = TeamProparacyto::getRapidAccess();
$menuItem = [];

if(!empty($_POST)){
  $errors=[];
  $validator = new Validator($_POST);
  if(isset($_POST['protoc'])){
    $validator->isText('name','The protocol name is not valid.');
    if($validator->isValid()){
      $db = new Database;
      $validator->isUniq('name',$db,App::getTableProtocols(),"The protocol already exists.");
    }
    $validator->isSelect('category', "Please choose a category for your protocol.");

    if($validator->isValid()){

      $protocol = new Protocol("proparacyto");
      if($protocol->newProtocolCKE($_POST['name'],$_POST['category'],$_POST['core'],$user->id)){
        Session::getInstance('success', 'Protocol recorded !');
        App::redirect("proparacyto/protocol/");
        exit();
      }
    }
    else{
        $errors = $validator->getErrors();
      }
  }
  elseif(isset($_POST['catego'])){
    $validator->isText('category','The category name is not valid.');
    if($validator->isValid()){
      $db = new Database;
      $validator->isUniq('category',$db,App::getTableProtocolCategories(),"The category already exists.");
    }
    if($validator->isValid()){
      $protocol = new Protocol("proparacyto");
      if($protocol->newCategory($_POST['category'])){
        Session::getInstance('success', 'Category recorded !');
        App::redirect("proparacyto/protocol/");
        exit();
      }
    }
    else{
        $errors = $validator->getErrors();
      }
    }

  }
//===========================================================
$title = 'ProParaCyto';
$titleLink = 'proparacyto/index.php';

echo Header::getHeader($title, $titleLink, $rapidAccess, $menuItem);
if(isset($validator)){echo $validator->afficheErrors($errors,"EN");}

?>

<h1 class="mt-3"><?= $h1;?></h1>
<?php

$form = new cskForm($_POST);
echo "<form method='post' action='#' enctype='multipart/form-data'>";
if($p == 'pro'){
echo $form->inputInline('name', 'text', 'Protocol Name : ','');
echo $form->category_select("category", "Category : ", "Choose a category",'');
echo $form->ckeditorText('core','Protocol :','');
echo $form->submit('primary','protoc','Add this protocol');
}
elseif($p == 'cat'){
  echo $form->inputInline('category', 'text', 'Category Name : ','');
  echo $form->submit('primary','catego','Add this category');

}
echo "</form>";

echo Footer::getFooter();
