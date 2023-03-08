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
$access = new Access("gestion_info", $user);
if(!$access->access()){
    Session::setFlash('danger', "Vous n'êtes pas autorisé à accéder à cette page.");
    App::redirect('../extranet_v2/index.php');
    exit();
}
//===========================================================
$rapidAccess = [];
$menuItem = [];

$title = 'Extranet MFP';
$titleLink = 'index.php';

echo Header::getHeader($title, $titleLink, $rapidAccess, $menuItem);
?>
    <h1>Gestion informatique MFP</h1>

<?php
    $switch = new ButtonIndex();
    echo $switch->afficheButton('extranet_info_home_items');
?>
<?= Footer::getFooter();
