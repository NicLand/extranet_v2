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
$access = new Access("reger", $user);
if(!$access->access()){
  Session::getInstance()->setFlash('danger', "Vous n'�tes pas autoris� � acc�der � cette page.");
  App::redirect('index.php');
  exit();
}
//===========================================================
if(!empty($_POST)){
  $search = new Search($_POST['search'],$_POST['search_option']);
  $search->getResult('reger_primer', ['num'], 'asc');
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
$rapidAccess = TeamReger::getRapidAccess();
$menuItem = [];

$title = 'ex-REGER';
$titleLink = 'reger/index.php';

echo Header::getHeader($title, $titleLink, $rapidAccess, $menuItem);
echo '<a class="btn btn-secondary m-3" href="../index.php" role="button">Back to ex-REGER extranet</a>';

?>

<h1 class="mt-3">Oligonucleotides list</h1>

<?php

$primers = new Primer('reger');
echo '<a href="'.App::getRoot().'/reger/primer/new.php" class="btn btn-primary m-2" role="button">Add a new primer</a>';
echo '<a href="'.App::getRoot().'/reger/primer/export.php?p=snap" class="btn btn-success m-2" role="button">Export for SnapGene</a>';
$form = new cskForm($_POST);

echo $form->inputSearch('#', $post, $opt);

if(isset($n) && $n>0){
  echo $primers->getSearchList($r,$n);
}

else{
if(isset($_GET['p'])){
  $p=$_GET['p'];
}
else{
  $p = $primers->nbPage();
}
echo $primers->paginationPrimer($p);
echo $primers->getList($p);
}
?>


<?php echo Footer::getFooter();?>
