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

if(!empty($_POST)){
  $search = new Search($_POST['search'],$_POST['search_option']);
  $search->getResult('plasmide', ['name'], 'asc');
  $r = $search->getData();
  $n = $search->getNumResult();
  //var_dump($r);
  //var_dump($n);
  $post = $_POST['search'];
  $opt = $_POST['search_option'];
  if(empty($r)){
    Session::getInstance()->setFlash('danger', "Sorry there are no result for your search.");
  }
}
else{
  $post = "";
  $opt = "all";
}

//===========================================================
$rapidAccess = TeamProparacyto::getRapidAccess();
$menuItem = [];

$title = 'ProParaCyto';
$titleLink = 'proparacyto/index.php';


echo Header::getHeader($title, $titleLink, $rapidAccess, $menuItem);
if(isset($validator)){echo $validator->afficheErrors($errors,"EN");}

echo '<h1 class="mt-3">Plasmides</h1>';
echo '<a href="'.App::getRoot().'/proparacyto/plasmide/new.php?type=plasmide" class="btn btn-primary m-2" role="button">Add a new plasmide</a>';
echo '<a href="'.App::getRoot().'/proparacyto/plasmide/new.php?type=cell" class="btn btn-success m-2" role="button">Add a new Cell Line</a>';

$form = new cskForm($_POST);

echo $form->inputSearch('#', $post, $opt);

if(isset($n) && $n>0){
  $project = new Plasmide;
  echo $project->getSearchList($r,$n);
}
else{
$plasmide = new Plasmide;
echo $plasmide->afficheAllPlasmides();
}
?>
<?= Footer::getFooter();
