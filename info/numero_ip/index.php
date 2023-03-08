<?php

//===========================================================
//Charge automatiquemebnt les Class utilisées dans les pages
// Dans le bon __NAMESPACE__
namespace Extranet;

require '../../class/Autoloader.php';
Autoloader::register();

$auth = App::getAuth();
$user = $auth->user();
//===========================================================
$rapidAccess = [];
$menuItem = [];

$title = 'Extranet MFP';
$titleLink = 'index.php';

echo Header::getHeader($title, $titleLink, $rapidAccess, $menuItem);
?>
    <h1>Numéro IP du MFP</h1>


<?= Footer::getFooter();
