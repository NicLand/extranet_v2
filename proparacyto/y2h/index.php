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
if(isset($_POST['update'])){
  $interaction = new Interactome;
  if(isset($_POST['inter']) && $_POST['inter'] != ""){
    if($interaction->updateInteraction($_POST['inter'],$_POST['interaction'])){
      Session::getInstance()->setFlash('success', "Interaction updated !");

    }
    else{
      Session::getInstance()->setFlash('danger', "No updated !");
    }
  }
  else{
    if($interaction->newInteraction($_POST['ad'],$_POST['bd'],$_POST['interaction'])){
      Session::getInstance()->setFlash('success', "Interaction saved !");
    }
    else{
      Session::getInstance()->setFlash('danger', "Interaction not saved !");
    }
  }

}

//===========================================================
$rapidAccess = TeamProparacyto::getRapidAccess();
$menuItem = [];

$title = 'ProParaCyto';
$titleLink = 'proparacyto/index.php';

echo Header::getHeader($title, $titleLink, $rapidAccess, $menuItem);
?>

<h1 class="mt-3">Y2H interactome</h1>
<?php
echo '<a href="'.App::getRoot().'/proparacyto/y2h/new.php" class="btn btn-primary m-1" role="button">Add a new AD or BK vector</a>';
echo '<a href="'.App::getRoot().'/proparacyto/y2h/choice.php" class="btn btn-success m-1" role="button">Choose proteins/constructions for interactions</a>';

  if(isset($_POST['AD']) && isset($_POST['BD'])){
    if(!empty($_POST['AD'])){
    if(is_array($_POST['AD'])){
      $AD = $_POST['AD'];
    }
    elseif(is_string($_POST['AD'])){
      $AD = explode(',',$_POST['AD']);
    }
  }
  if(!empty($_POST['BD'])){
    if(is_array($_POST['BD'])){
      $BD = $_POST['BD'];
    }
    elseif(is_string($_POST['BD'])){
      $BD = explode(',',$_POST['BD']);
    }
  }
  $interactome = new Interactome;
  echo $interactome->getInteractome($AD,$BD);
}




echo Footer::getFooter();
