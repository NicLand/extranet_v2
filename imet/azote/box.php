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
if(isset($_GET['c']) && isset($_GET['r']) && isset($_GET['b'])){
  $c = $_GET['c'];
  $r = $_GET['r'];
  $b = $_GET['b'];
}else{
  App::redirect('imet/azote/index.php');
  exit();
}


//===========================================================
$rapidAccess = TeamIMet::getRapidAccess();
$menuItem = [];

$title = 'iMet';
$titleLink = 'imet/index.php';

echo Header::getHeader($title, $titleLink, $rapidAccess, $menuItem);

$azote = new TeamIMet();
echo $azote->getAzoteBox($c,$r,$b);

?>


<?= Footer::getFooter();
