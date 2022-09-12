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
$access = new Access("commande", $user);
if(!$access->accessCommande()){
  Session::setFlash('danger', "Vous n'�tes pas autoris� � acc�der � cette page.");
  App::redirect('index.php');
  exit();
}
//===========================================================
if(isset($_POST['validation'])){
  $errors=[];
  $validator = new Validator($_POST);
  $validator->isText('bon_commande','Veuillez renseigner un bon de commande !');
  if($validator->isValid()){
    $date_valide = date("Y-m-d");
    $valide = 1;
    $up = new Commande;
    if($up->validCommande($_POST['ligne'],$_POST['bon_commande'],$date_valide,$valide)){
      Session::getInstance()->setFlash('success',"Ligne validée.");

    }
  }
  else{
    $errors = $validator->getErrors();
  }
}
//===========================================================
if(isset($_POST['search']) && $_POST['search'] !=""){
  $search = new Search($_POST['search'],$_POST['search_option']);
  $search->getResult('commande', ['date_commande'], 'desc');
  $r = $search->getData();
  $n = $search->getNumResult();
  //var_dump($r);
  //var_dump($n);
  $post = $_POST['search'];
  $opt = $_POST['search_option'];
  if(!empty($r)){
    $count = new Commande;
    $comptage = count($count->sortSearchCommandeList($r,"encours"));
    //var_dump($comptage);
    if($comptage<1){
      Session::getInstance()->setFlash('danger', "Sorry there are no result for your search.");
      App::redirect('commande/encours.php');
      exit();
    }
    else{
      $affiche = $count->getSearchCommandeList($r,$n,"encours",$user->commande,$post,$opt);
    }
  }
  else{
    Session::getInstance()->setFlash('danger', "Sorry there are no result for your search.");
    App::redirect('commande/encours.php');
    exit();
  }
}
else{
  $post = "";
  $opt = "all";
}
//===========================================================

$title = 'Extranet MFP';
$titleLink = 'index.php';

$rapidAccess = [];
$menuItem = Commande::getRapidAccess();


echo Header::getHeader($title, $titleLink, $rapidAccess, $menuItem);
if(isset($validator)){echo $validator->afficheErrors($errors,"EN");}
?>

    <h1 class="m-3">En cours</h1>

<?php
$commande = new Commande;
$form = new cskForm($_POST);
echo $form->inputSearch('#', $post, $opt);
if(isset($n) && $n>0){
  echo $affiche;
}

else{
  echo $commande->getList("encours",$user->commande,$post,$opt);
}
 ?>

<?= Footer::getFooter();
