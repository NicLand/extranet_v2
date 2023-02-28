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
  $search->getResult('azote', ['container','tige', 'box','place'], 'asc');
  $r = $search->getData();
  $n = $search->getNumResult();
  if(empty($r)){
    Session::getInstance()->setFlash('danger', "Sorry there are no result for your search.");
  }
}

//===========================================================
$rapidAccess = TeamProparacyto::getRapidAccess();
$menuItem = [];

$title = 'ProParaCyto';
$titleLink = 'proparacyto/index.php';

echo Header::getHeader($title, $titleLink, $rapidAccess, $menuItem);
echo "<h1 class='mt-3'>Liquid Nitrogen Vials</h1>";
echo '<a href="'.App::getRoot().'/proparacyto/azote" class="btn btn-primary m-2" role="button">Go back</a>';

$form = new cskForm($_POST);
echo $form->inputSearch('result.php',$_POST['search'], $_POST['search_option']);


if(isset($n) && $n>0){
  $azote = new Azote;
  echo $azote->afficheListResult($r,$n);
}
?>

<?= Footer::getFooter();
