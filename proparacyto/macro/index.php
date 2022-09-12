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

//===========================================================

$rapidAccess = TeamProparacyto::getRapidAccess();
$menuItem = [];

$title = 'ProParaCyto';
$titleLink = 'proparacyto/index.php';

echo Header::getHeader($title, $titleLink, $rapidAccess, $menuItem);
?>

<h1 class="mt-3">ImageJ Macros</h1>
<div class="row">
  <div class="col-sm-3">
    <div class="card">
    <img src="../../img/icon_imageJ.png" class="card-img-top mt-3 mx-auto" style="width:100px;" alt="imageJ">
    <div class="card-body">
      <h5 class="card-title">Magic Montage</h5>
      <p class="card-text"></p>
      <a href="ijm/MagicMontage.ijm" download="MagicMontage" class="btn btn-primary">Download</a>
    </div>
  </div>
  </div>
  <div class="col-sm-3">
  <div class="card">
    <img src="../../img/icon_imageJ.png" class="card-img-top mt-3 mx-auto" style="width:100px;" alt="imageJ">
    <div class="card-body">
      <h5 class="card-title">Auto Composite for Mac v9</h5>
      <p class="card-text"></p>
      <a href="ijm/autoComposite_V9.ijm" download="Auto-composite-V9" class="btn btn-primary">Download</a>
    </div>
  </div>
  </div>
  </div>
</div>
</div>

<?= Footer::getFooter();
