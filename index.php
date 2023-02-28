<?php

//===========================================================
//Charge automatiquemebnt les Class utilisées dans les pages
// Dans le bon __NAMESPACE__
namespace Extranet;

require 'class/Autoloader.php';
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
      <h1><?=$title;?></h1>

      <h6>Bienvenue sur l'extranet du MFP</h6>
      <?= "<h6>Ici sont presents tous les outils numeriques du laboratoire MFP</h6>";?>

<?php
$switch = new ButtonIndex;
$homeItems = App::getTableButtonIndex();
echo $switch->afficheButton($homeItems);


echo Footer::getFooter();
