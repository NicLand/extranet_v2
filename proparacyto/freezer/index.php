<?php
//===========================================================
//Charge automatiquemebnt les Class utilisÃ©es dans les pages
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
  Session::setFlash('danger', "Vous n'ï¿½tes pas autorisï¿½ ï¿½ accï¿½der ï¿½ cette page.");
  App::redirect('index.php');
  exit();
}

//===========================================================

//===========================================================
$rapidAccess = TeamProparacyto::getRapidAccess();
$menuItem = [];

$title = 'ProParaCyto';
$titleLink = 'proparacyto/index.php';

echo Header::getHeader($title, $titleLink, $rapidAccess, $menuItem);

echo "<h1 class='mt-3'>-80°C Freezer Stocks</h1>";

$form = new cskForm($_POST);
echo $form->inputSearch('result.php','','all');


$freezer = new Freezer();
//echo "<pre>";
//var_dump($freezer->getFreePlaces("4","6","12"));
//echo "</pre>";
echo $freezer->getFreezer();
?>

<?= Footer::getFooter();
