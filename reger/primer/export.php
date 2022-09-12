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

if(isset($_GET['p']) && $_GET['p'] !=""){
  if($_GET['p'] === "snap"){
    header ('Content-type: text/csv');
    header ('Content-Disposition: attachment;filename="primer.csv"');

    $export = new Primer('reger');

    echo $export->exportPrimer($_GET['p']);
  }
  elseif($_GET['p'] === 'amplifx'){
    header ('Content-type: text/xml');
    header ('Content-Disposition: attachment;filename="primer.xpl"');

    $export = new Primer('reger');

    echo $export->exportPrimer($_GET['p']);
  }
  else{
    App::redirect('index.php');
  }
}



?>
