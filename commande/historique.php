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
if(isset($_GET['y'])){
    $y = $_GET['y'];
  }
else{
    $last = new Commande;
    $y = $last->getLastYear()->y;
    $m = $last->getLastMonth()->m;
  }
if (isset($_GET['m'])){
    $m = $_GET['m'];
  }
else{
    $last = new Commande;
    $m = $last->getLastMonth()->m;
  }
  //===========================================================
  if(!empty($_POST)){
    $search = new Search($_POST['search'],$_POST['search_option']);
    $search->getResult('commande', ['date_commande','date_valide'], 'DESC');
    $r = $search->getData();
    $n = $search->getNumResult();
    //var_dump($r);
    //var_dump($n);
    $post = $_POST['search'];
    $opt = $_POST['search_option'];
    if(empty($r)){
      Session::getInstance()->setFlash('danger', "Sorry there are no result for your search.");
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

echo Header::getHeader($title, $titleLink, $rapidAccess, $menuItem);?>

    <h1 class="m-3">Historique</h1>

<?php
$commande = new Commande;

$form = new cskForm($_POST);
echo $form->inputSearch('#', $post, $opt);
if(isset($n) && $n>0){
  echo $commande->getSearchCommandeListHisto($r,$n,"historique",$access);
}

else{
  echo $commande->afficheHistorique($y,$m);
}
?>

<?= Footer::getFooter();
