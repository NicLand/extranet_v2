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
$access = new Access("spacvir", $user);
if(!$access->access()){
  Session::setFlash('danger', "Vous n'êtes pas autorisé à accéder à cette page.");
  App::redirect('index.php');
  exit();
}
//===========================================================
if(!empty($_POST)){
  $search = new Search($_POST['search'],$_POST['search_option']);
  $search->getResult('virus', ['date'], 'asc');
  $r = $search->getData();
  $n = $search->getNumResult();
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
//=========================================================

$rapidAccess = TeamSpacvir::getRapidAccess();
$menuItem = [];

$title = 'SpacVir';
$titleLink = 'spacvir/index.php';

echo Header::getHeader($title, $titleLink, $rapidAccess, $menuItem);
?>
<h1 class="mt-3">-80�C Freezer</h1>

<?php
$freezer = new TeamSpacvirFreezer;

echo $freezer->afficheFreezer();
?>

<?= Footer::getFooter();
