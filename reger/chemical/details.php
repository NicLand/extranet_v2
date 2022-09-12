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
$access = new Access("reger", $user);
if(!$access->access()){
  Session::getInstance()->setFlash('danger', "Vous n'�tes pas autoris� � acc�der � cette page.");
  App::redirect('index.php');
  exit();
}
//===========================================================
if(isset($_GET['id']) && $_GET['id'] !=""){
  $id = $_GET['id'];
}
else{
  App::redirect('reger/chemical/index.php');
  exit();
}
//===========================================================
$rapidAccess = TeamReger::getRapidAccess();
$menuItem = [];

$title = 'ex-REGER';
$titleLink = 'reger/index.php';

echo Header::getHeader($title, $titleLink, $rapidAccess, $menuItem);
echo '<a class="btn btn-secondary m-3" href="index.php" role="button">Back to Chemicals list</a>';
?>

<h1 class="mt-3">Chemical details</h1>

<?php
//echo '<a href="'.App::getRoot().'/proparacyto" class="btn btn-secondary mb-2" role="button" aria-pressed="true">Back to Proparacyto</a>';

$data = new Chemical('reger');
echo '<a href="'.App::getRoot().'/reger/chemical/new.php" class="btn btn-primary mb-2" role="button">Add a new chemical</a>';

echo $data->afficheSingle($id);

?>


<?php echo Footer::getFooter();?>
