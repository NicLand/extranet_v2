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

<h1 class="mt-3">Interactome setting</h1>
<?php
echo '<a href="'.App::getRoot().'/proparacyto/y2h/index.php" class="btn btn-primary m-1" role="button">Back to Y2H</a>';
//echo '<a href="'.App::getRoot().'/proparacyto/y2h/choice.php" class="btn btn-success m-1" role="button">Choose proteins/constructions for interactions</a>';
$y2h = new Interactome;
$form = new cskForm($_POST);
echo "<form action='index.php' method='post'>";
echo $y2h->getChoice();

echo $form->submit('primary','interactome','See interactome');
echo "</form>";
?>

<?php echo Footer::getFooter();?>
