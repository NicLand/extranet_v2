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
  Session::setFlash('danger', "Vous n'êtes pas autorisé à accéder à cette page.");
  App::redirect('index.php');
  exit();
}

//===========================================================
$rapidAccess = [];
$menuItem = array(
  "S'inscrire"=> App::getRoot().'/user/register.php',
  'Se connecter'=> App::getRoot().'/login.php'
);

$title = 'ProParaCyto Projects';

echo Header::getHeader($title, $rapidAccess, $menuItem);
?>

<h1><?= $title;?></h1>
