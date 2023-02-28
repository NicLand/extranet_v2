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
$rapidAccess = [];
$menuItem = [];

if(!empty($_POST)){

  $errors = array();
  $db = App::getDatabase();

  $validator = new Validator($_POST);
  $validator->isconfirmed('password', "Votre mot de passe n'est pas valide.");
  if($validator->isValid()){
    App::getAuth()->changePass($db, $_POST['password'],$user->id);
    Session::getInstance()->setFlash('success', "Mot de passe chang� avec succ�s !");
    App::redirect('user/account.php');
    exit();
  }
  else{
    $errors = $validator->getErrors();
  }

}

$title = 'Extranet MFP';

echo Header::getHeader("Extranet MFP",'index.php', $rapidAccess, $menuItem);
?>

<h1>Bienvenue </h1>

<form  action="" method="post">
  <?php $form = new Form($_POST);
    echo $form->input('password', 'password', 'Changer votre mot de passe','');
    echo $form->input('password_confirm', 'password', 'Confirmer votre mot de passe','');
    echo $form->button('submit', 'primary', 'Valider le changment de mot de passe','');
  ?>
</form>

<?= Footer::getFooter();
