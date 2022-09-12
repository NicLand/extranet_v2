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
  App::redirect('login.php');
  exit();
}
//===========================================================
$rapidAccess = [];
$menuItem = [];
$title = 'WebTools';
$titleLink = 'webtools/index.php';
echo Header::getHeader($title, $titleLink, $rapidAccess, $menuItem);
echo '<a class="btn btn-secondary m-3" href="../index.php" role="button">Back to Extranet</a>';
echo '<h1 class="mt-3">Web Tools</h1>';

$switch = new ButtonIndex();
echo $switch->afficheButton('extranet_web_tools_items');

?>

<?= Footer::getFooter();
