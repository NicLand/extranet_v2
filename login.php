<?php
//===========================================================
//Charge automatiquemebnt les Class utilis�es dans les pages
// Dans le bon __NAMESPACE__
namespace Extranet;

require 'class/Autoloader.php';
Autoloader::register();

//===========================================================
$db = App::getDatabase();
$auth = App::getAuth();

$auth->connectFromCookie($db);
if($auth->user()){
  App::redirect('user/account.php');
  exit();
}

if(!empty($_POST) && !empty($_POST['username']) && !empty($_POST['password'])){

  $user = $auth->login($db, $_POST['username'], $_POST['password'], isset($_POST['remember']));
if($auth->user()){
    App::redirect('index.php');
  }
  else{
    Session::getInstance()->setFlash('danger' , "Identifiant et/ou mot de passe incorrect(s) !");
  }
}
else if (!empty($_POST) && (empty($_POST['username']) || empty($_POST['password']))){
  Session::getInstance()->setFlash('danger', "Veuillez remplir les champs !");
}

$title = 'Extranet MFP';
$titleLink = 'index.php';

$rapidAccess = [];
$menuItem = array(
  "S'inscrire"=> App::getRoot().'/user/register.php'
);
echo Header::getHeader($title, $titleLink, $rapidAccess, $menuItem);?>

<h1>Se connecter</h1>
<div class="container">
        <form action="" method="post">

          <?php $form = new Form($_POST);
            echo '<div class="row justify-content-center"><div class="col-4">';
            echo $form->input('username', 'text', 'Identifiant : ','');
            echo '</div></div><div class="row justify-content-center"><div class="col-4">';
            echo $form->input('password' , 'password' , 'Mot de passe : ','');
            echo '</div></div><div class="row justify-content-center"><div class="col-4">';
            echo "<p><a href='".App::getRoot()."/user/forget.php'>Mot de passe oublie</a></p>";
            echo '</div></div><div class="row justify-content-center"><div class="col-4">';
            echo $form->checkBox('remember', "1",'', "Se souvenir de moi",'');
            echo '</div></div><div class="row justify-content-center"><div class="col-4">';
            echo $form->button('submit', 'primary','Se connecter');
            echo '</div></div>';
          ?>

        </form>
</div>


<?php echo Footer::getFooter();?>
