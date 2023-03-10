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
$arr = [310,311,322];
if(in_array($user->id,$arr)){
    $super = true;
}
else{
    $super = false;
}
//===========================================================
if(!empty($_POST)){
  $search = new Search($_POST['search'],$_POST['search_option']);
  $search->getResult('spacvir_plasmides', ['id'], 'asc');
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
//=========================================================

$rapidAccess = TeamSpacvir::getRapidAccess();
$menuItem = [];

$title = 'SpacVir';
$titleLink = 'spacvir/index.php';

echo Header::getHeader($title, $titleLink, $rapidAccess, $menuItem);
?>

<h1 class="mt-3">SpacVir Plasmide List</h1>
<?php
if($super == true){
    echo '<a href="'.App::getRoot().'/spacvir/plasmides/new.php" class="btn btn-primary m-2" role="button">Add a new plasmide</a>';
}

$form = new TeamSpacvirForm($_POST);
echo $form->inputSearch('#', $post, $opt);

if(isset($n) && $n>0){
    $plasmide = new TeamSpacvirPlasmide();
    echo $plasmide->getSearchList($r,$n,$super);
}
else{
    $plasmide = new TeamSpacvirPlasmide();
    echo $plasmide->getPlasmideData($super);
}
?>



<?= Footer::getFooter();
