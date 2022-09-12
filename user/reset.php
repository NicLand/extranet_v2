<?php
//===========================================================
//Charge automatiquemebnt les Class utilisÃ©es dans les pages
// Dans le bon __NAMESPACE__
namespace Extranet;

require '../class/Autoloader.php';
Autoloader::register();

//===========================================================
$menuItem=[];
$rapidAccess=[];
$title = 'Extranet MFP';

//===========================================================
if(isset($_GET['id']) && isset($_GET['token'])){

  $auth = App::getAuth();
  $db = App::getDatabase();
  $user = $auth->checkResetToken($db, $_GET['id'], $_GET['token']);
  if($user){
    if(!empty($_POST)){
      $errors = array();

      $validator = new Validator($_POST);
      $validator->isConfirmed('password', "Votre mot de passe n'est pas valide.");
      if($validator->isValid()){

        App::getAuth()->changePass($db, $_POST['password'],$user->id);
        Session::getInstance()->setFlash('success', "Mot de passe r�initialis� avec succ�s !");
        $auth->connect($user);
        App::redirect('user/account.php');
        exit();
        }
        else{
        $errors = $validator->getErrors();
        }

    }

  }else{
    Session::getInstance()->setFlash('danger' , "Ce token n'est pas valide.");
    App::redirect('login.php');
    exit();
  }
}
else{
  Session::getInstance()->setFlash('danger' , "NOP.");
  App::redirect('login.php');
  exit();
}

echo Header::getHeader("Extranet MFP",'index.php', $rapidAccess, $menuItem);
if(isset($validator)){echo $validator->afficheErrors($errors);}

?>

<h1>R�initialisation du mot de passe</h1>

<form action="" method="post">

  <div class="form-group">
    <label for="">Mot de passe</label>
    <input type="password" name="password" class="form-control">
  </div>

  <div class="form-group">
    <label for="">Confirmation du mot de passe</label>
    <input type="password" name="password_confirm" class="form-control">
  </div>

  <button type="submit" class="btn btn-primary">R�initialiser votre mot de passe</button>
</form>


<?= Footer::getFooter();
