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
$access = new Access("imet", $user);
if(!$access->access()){
  Session::getInstance()->setFlash('danger', "Vous n'�tes pas autoris� � acc�der � cette page.");
  App::redirect('../index.php');
  exit();
}
//===========================================================
if(!empty($_POST)){
  $search = new Search($_POST['search'],$_POST['search_option']);
  $search->getResult('imet_azote', ['container','tige', 'boite','place'], 'asc');
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
$rapidAccess = TeamIMet::getRapidAccess();
$menuItem = [];

$title = 'iMet';
$titleLink = 'imet/index.php';

echo Header::getHeader($title, $titleLink, $rapidAccess, $menuItem);
?>

<h1 class="mt-3">Azote</h1>
<?= '<a href="'.App::getRoot().'/imet/azote/log.php" class="btn btn-primary m-2" role="button">Log de decongelation</a>';?>

<?php
$azote = new TeamIMet();
$form = new cskForm($_POST);

echo $form->inputSearch('#', $post, $opt);

if(isset($n) && $n>0){
  echo $azote->getAzoteSearch($r,$n);
}
else{
echo $azote->getAzote();
}


?>
<?php echo Footer::getFooter();?>
