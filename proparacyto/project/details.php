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
if(isset($_GET['type'])){
  $type = $_GET['type'];
}
else{
  $type="";
}
//===========================================================
$rapidAccess = TeamProparacyto::getRapidAccess();
$menuItem = [];

$title = 'ProParaCyto';
$titleLink = 'proparacyto/index.php';

echo Header::getHeader($title, $titleLink, $rapidAccess, $menuItem);

$id = $_GET['id'];

$project = new Project();
$plasmides = new Plasmide();
?>

<?php
echo $project->afficheSingleProject($id);
echo '<a href="'.App::getRoot().'/proparacyto/plasmide/new.php?type=plasmide" class="btn btn-primary m-2" role="button">Add a new plasmide</a>';
echo '<a href="'.App::getRoot().'/proparacyto/plasmide/new.php?type=cell" class="btn btn-success m-2" role="button">Add a new Cell Line</a>';
echo "<div class='display-6'>What do you want to see :</div>";
echo "<a href='details.php?id=$id&type=plasmide' class='btn btn-warning m-2' role='button'>Show Plasmides</a>";
echo "<a href='details.php?id=$id&type=cell' class='btn btn-info m-2' role='button'>Show Cell lines</a>";
echo "<a href='details.php?id=$id' class='btn btn-secondary m-2' role='button'>Show All</a>";
echo $plasmides->affichePlasmidesOrCells($id,$type);
?>
<?= Footer::getFooter();
