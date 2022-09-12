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
if(!empty($_POST)){
  $search = new Search($_POST['search'],$_POST['search_option']);
  $search->getResult('commande_fournisseur', ['fournisseur'], 'asc');
  $r = $search->getData();
  $n = $search->getNumResult();
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

    <h1 class="m-3">Fournisseurs</h1>
<?php
if($user->commande == "sa"){
  echo '<a href="'.App::getRoot().'/commande/new_dealer.php" class="btn btn-success mb-2" role="button">Ajouter un nouveau fournisseur</a>';
}
$form = new cskForm($_POST);
echo $form->inputSearch('#',$post,$opt);

$dealers = new Commande;
if(isset($n) && $n>0){
  echo $dealers->getSearchList($r,$n);
}

else{
  echo $dealers->getListDealers();
}
 ?>

<?= Footer::getFooter();
