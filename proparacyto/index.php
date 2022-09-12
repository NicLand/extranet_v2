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
  App::redirect('login.php');
  exit();
}
$access = new Access("proparacyto", $user);
if(!$access->access()){
  Session::setFlash('danger', "Vous n'êtes pas autorisé à accéder à cette page.");
  App::redirect('index.php');
  exit();
}

//===========================================================
$rapidAccess = [];
$menuItem = [];
$title = 'ProParaCyto';
$titleLink = 'proparacyto/index.php';
echo Header::getHeader($title, $titleLink, $rapidAccess, $menuItem);
echo '<a class="btn btn-secondary m-3" href="../index.php" role="button">Back to Extranet</a>';
echo '<h1 class="mt-3">ProParaCyto Extranet</h1>';

//$form = new cskForm($_POST);
//echo $form->inputBigSearch('#', $post, $opt);

$switch = new ButtonIndex();

echo $switch->afficheButton('extranet_proparacyto_home_items');


echo Footer::getFooter();
