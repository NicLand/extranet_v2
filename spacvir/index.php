<?php

//===========================================================
//Charge automatiquemebnt les Class utilisées dans les pages
// Dans le bon __NAMESPACE__
namespace Extranet;

require '../class/Autoloader.php';
Autoloader::register();

$auth = App::getAuth();
$user = $auth->user();

if(!$user){
  Session::setFlash('danger', "Veuillez vous identifier.");
  App::redirect('../extranet_v2/login.php');
  exit();
}
$access = new Access("spacvir", $user);
if(!$access->access()){
  Session::setFlash('danger', "Vous n'êtes pas autorisé à accéder à cette page.");
  App::redirect('../extranet_v2/index.php');
  exit();
}
//===========================================================
$rapidAccess = [];
$menuItem = [];
$title = 'SpacVir';
$titleLink = 'spacvir/index.php';
echo Header::getHeader($title, $titleLink, $rapidAccess, $menuItem);
echo '<a class="btn btn-secondary m-3" href="../index.php" role="button">Back to Extranet</a>';
echo '<h1 class="mt-3">SpacVir Extranet</h1>';

$switch = new ButtonIndex();

echo $switch->afficheButton('extranet_spacvir_home_items');


echo Footer::getFooter();
?>
