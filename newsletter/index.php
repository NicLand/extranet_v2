<?php
//===========================================================
//Charge automatiquemebnt les Class utilisées dans les pages
// Dans le bon __NAMESPACE__
namespace Extranet;

require '../class/Autoloader.php';
Autoloader::register();


//===========================================================
$rapidAccess = [];
$menuItem = [];
$title = 'Newsletter';
$titleLink = 'newsletter/index.php';
echo Header::getHeader($title, $titleLink, $rapidAccess, $menuItem);
echo '<a class="btn btn-secondary m-3" href="../index.php" role="button">Back to Extranet</a>';
echo '<h1 class="mt-3">Newsletter</h1>';

$nl = new Newsletter;

echo $nl->afficheListNewsletter();

echo Footer::getFooter();
