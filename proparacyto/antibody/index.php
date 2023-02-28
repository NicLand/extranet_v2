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
$access = new Access("proparacyto", $user);
if(!$access->access()){
  Session::getInstance()->setFlash('danger', "Vous n'�tes pas autoris� � acc�der � cette page.");
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
?>

<h1 class="mt-3">Antibodies list</h1>

<?php

$button = new ButtonIndex();
$link = "list.php?l=";
echo '<div class="row row-cols-1 row-cols-md-4 m-3">';
echo $button->manualButton('Primary Antibodies', $link.'1', "All the primary antibodies of the team", "primary","white");
echo $button->manualButton('Secondary Antibodies', $link.'2', "All the secondary antibodies of the team", "success","white");
echo "</div>";
?>
<?php echo Footer::getFooter();?>
