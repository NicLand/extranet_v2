<?php
//===========================================================
//Charge automatiquemebnt les Class utilis�es dans les pages
// Dans le bon __NAMESPACE__
namespace Extranet;

require '../class/Autoloader.php';
Autoloader::register();

$auth = App::getAuth();
$user = $auth->user();

if(!$user){
  App::redirect('login.php');
  exit();
}
//===========================================================

$title = 'Extranet MFP';
$titleLink = 'index.php';

$rapidAccess = [];
$menuItem = [];



//===========================================================
//Recup de la Mission

$id = $_GET['id'];
$mission = new Mission();
$res = $mission->getData($id);

echo Header::getHeader($title,$titleLink,$rapidAccess,$menuItem);
?>

<h2 class="mt-3">D�tails de la mission</h2>


  <p>Date de la mission : du <strong><?= $res->allerDate;?></strong> au <strong><?= $res->retourDate;?></strong></p>
  <p>Objet de la mission : <strong><?= $res->objet;?></strong></p>
  <p>...</p>


<?php
echo Footer::getFooter();
