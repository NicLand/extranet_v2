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
if(isset($_GET['id']) && $_GET['id'] !=""){
  $id = $_GET['id'];
}
else{
  App::redirect('proparacyto/chemical/index.php');
  exit();
}
//===========================================================
$rapidAccess = TeamProparacyto::getRapidAccess();
$menuItem = [];

$title = 'ProParaCyto';
$titleLink = 'proparacyto/index.php';

echo Header::getHeader($title, $titleLink, $rapidAccess, $menuItem);
?>

<h1 class="mt-3">Chemical details</h1>

<?php
//echo '<a href="'.App::getRoot().'/proparacyto" class="btn btn-secondary mb-2" role="button" aria-pressed="true">Back to Proparacyto</a>';

$data = new Chemical('proparacyto');
echo '<a href="'.App::getRoot().'/proparacyto/chemical/new.php" class="btn btn-primary mb-2" role="button">Add a new chemical</a>';

echo $data->afficheSingle($id);

?>


<?php echo Footer::getFooter();?>
