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
if(isset($_POST['livraison'])){
  $errors=[];
  $validator = new Validator($_POST);
  $validator->isText('bon_livraison','Veuillez renseigner un bon de livraison !');
  $validator->isText('date_livraison','Veuillez renseigner un date de livraison !');
  if($validator->isValid()){
    $livre = 1;
    $comment_livraison ="";
    $up = new Commande;
    if($up->livreCommande($_POST['ligne'],$_POST['bon_livraison'],$_POST['date_livraison'],$livre,$comment_livraison)){
      Session::getInstance()->setFlash('success',"Ligne livrée !");
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
    $comptage = count($count->sortSearchCommandeList($r,"enlivraison"));
    //var_dump($comptage);
    if($comptage<1){
      Session::getInstance()->setFlash('danger', "Sorry there are no result for your search.");
      App::redirect('commande/enlivraison.php');
      exit();
    }
    else{
      $affiche = $count->getSearchCommandeList($r,$n,"enlivraison",$user->commande,$post,$opt);
    }
  }
  else{
    Session::getInstance()->setFlash('danger', "Sorry there are no result for your search.");
    App::redirect('commande/enlivraison.php');
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
    <h1 class="m-3">En livraison</h1>

    <?php
    $commande = new Commande;
    $form = new cskForm($_POST);
    echo $form->inputSearch('#', $post, $opt);
    if(isset($n) && $n>0){
      echo $affiche;
    }

    else{
      echo $commande->getList("enlivraison",$user->commande,$post,$opt);
    }
  ?>


<?= Footer::getFooter();
