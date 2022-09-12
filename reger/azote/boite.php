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
  App::redirect('../index.php');
  exit();
}
//===========================================================
if(!empty($_GET['c']) && !empty($_GET['t']) && !empty($_GET['e'])){
  $c = $_GET['c'];
  $t = $_GET['t'];
  $e = $_GET['e'];
}
//===========================================================
$rapidAccess = TeamReger::getRapidAccess();
$menuItem = [];

$title = 'ex-REGER';
$titleLink = 'reger/index.php';

echo Header::getHeader($title, $titleLink, $rapidAccess, $menuItem);
echo '<a class="btn btn-secondary m-3" href="index.php" role="button">Back to Cuve</a>';

?>

<h1 class="mt-3 mb-3">Cuve <?php echo $c;?> Tige <?php echo $t;?> Etage <?php echo $e;?></h1>

<?php
$azote = new TeamReger;
echo $azote->afficheBoite($c,$t,$e);

?>
<?php echo Footer::getFooter();?>
