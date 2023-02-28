<?php
//===========================================================
//Charge automatiquemebnt les Class utilisées dans les pages
// Dans le bon __NAMESPACE__
namespace Extranet;

require '../class/Autoloader.php';
Autoloader::register();

//===========================================================
$menuItem=[];
$rapidAccess=[];
$title = 'Extranet MFP';

//===========================================================
// On valide le formulaire

if(!empty($_POST)){

$errors = array();
$db = App::getDatabase();

$validator = new Validator($_POST);
$validator->isAlpha('name', "Votre nom n'est pas valide.");
$validator->isAlpha('firstname', "Votre prenom n'est pas valide.");
$validator->isAlpha('username', "Votre identifiant n'est pas valide.");
$validator->isSelect('team' , "Veuillez renseigner une equipe.");
if($validator->isValid()){
  $validator->isUniq('username', $db, App::getTableUsers(),"Cet identifiant existe deja!");
}
$validator->isEmail('email', "Votre email n'est pas valide.");
if($validator->isValid()){
  $validator->isUniq('email',$db, App::getTableUsers(),"Cet email existe deja pour un autre compte !");
}
$validator->isConfirmed('password', "Votre mot de passe n'est pas valide.");

if ($validator->isValid()){
    App::getAuth()->register($db, $_POST['name'], $_POST['firstname'], $_POST['username'], $_POST['password'], $_POST['email'], $_POST['team']);
    Session::getInstance()->setFlash('success', "Un email de confirmation vous a ete envoye !<br/> Merci de verifier vos messages indesirables.");
    App::redirect('login.php');
    exit();
}
else{
    $errors = $validator->getErrors();
  }
}
//===========================================================

//var_dump($_SESSION);
echo Header::getHeader("Extranet MFP",'index.php', $rapidAccess, $menuItem);
if(isset($validator)){echo $validator->afficheErrors($errors);}
?>

<h1>S'inscrire</h1>

<form method="post" action="">
<?php
$teamList = Database::query('SELECT * FROM mfp_extranet_teams ORDER BY id ASC')->fetchAll();
$form = new Form($_POST);
  echo $form->input('name', 'text', 'Nom : ','');
  echo $form->input('firstname' , 'text' , 'Prenom : ','');
  echo $form->input('email' , 'email' , 'Email : ','');
  echo $form->input('username' , 'text' , 'Identifiant : ','');
  echo $form->input('password' , 'password' , 'Mot de passe : ','');
  echo $form->input('password_confirm' , 'password' , 'Confirmer le mot de passe : ','');
  echo $form->select($teamList, 'team', "Equipe : ", "Choisissez une equipe ");
  echo $form->button('submit', 'primary',"S'inscrire");
  echo $form->button('reset', 'secondary', "Effacer le formulaire");


?>
</form>

<?= Footer::getFooter();
