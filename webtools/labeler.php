<?php

//===========================================================
//Charge automatiquemebnt les Class utilisées dans les pages
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

//===========================================================
$rapidAccess = [];
$menuItem = [];
$title = 'WebTools | Labeler';
$titleLink = 'webtools/index.php';
echo Header::getHeader($title, $titleLink, $rapidAccess, $menuItem);
echo '<a class="btn btn-secondary m-3" href="../index.php" role="button">Back to Extranet</a>';
echo '<h1 class="mt-3">Label generator</h1>';

$form = new Form;
echo "<form method='post' action='label.php' enctype='multipart/form-data'>";
echo $form->selectSimpleCard([1=>"0,5 mL",2=>"1,5 mL"],'size','Tube size :','','');
echo $form->inputCard('tube_num','number','Number of tubes : ','','');
echo $form->inputCard('sample','text','Sample name :','','20 characters max.');
echo $form->inputCard('comments','text','Comments :','','60 characters max.');
echo $form->submit('primary','label','Label it!');
echo $form->closeForm();


?>

<?= Footer::getFooter();
