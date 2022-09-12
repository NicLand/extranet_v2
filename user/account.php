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
// On defini le menu
$rapidAccess = [];
$menuItem = [];

//===========================================================
$title = 'Mon compte';

echo Header::getHeader("Extranet MFP",'index.php', $rapidAccess, $menuItem);
?>

  <h1>Mon compte</h1>

<h2>Bienvenue <?= ucfirst($user->firstname);?></h2/>
  <?php echo "<p><a class='btn btn-primary' href='".App::getRoot()."/user/change_pass.php' role='button'>Changer mon mot de passe</a></p>";?>


<?= Footer::getFooter();?>
