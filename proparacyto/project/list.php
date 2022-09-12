<?php
//===========================================================
//Charge automatiquemebnt les Class utilisées dans les pages
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
$rapidAccess = TeamProparacyto::getRapidAccess();
$menuItem = [];

$title = 'ProParaCyto';
$titleLink = 'proparacyto/index.php';

if(isset($_GET['l']) && $_GET['l'] != ""){$list = $_GET['l'];}else{$list = "1";}
  $new = new Project;

  $h1 = $new->getProjectTypeByList($list);
  if($h1){
  $h1 = $h1->type." Projects";
  $id = $h1->id;
}
else {
  $h1 = "All Projects";

}


echo Header::getHeader($title, $titleLink, $rapidAccess, $menuItem);
?>

<h1 class="mt-3"><?= $h1;?></h1>

<a class="btn btn-primary mb-3" href="index.php" role="button">Back to Project</a>
<a class="btn btn-success mb-3" href="uptype.php?t=<?= $list;?>" role="button">Modify the project's category</a>

<?php

$projects = new Project();
echo $projects->afficheProjects($list);

echo Footer::getFooter();
