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
$access = new Access("imet", $user);
if(!$access->access()){
  Session::setFlash('danger', "Vous n'�tes pas autoris� �� acc�der � cette page.");
  App::redirect('../index.php');
  exit();
}
//===========================================================
if(!empty($_POST)){
	var_dump($_POST);
	$validator = new Validator($_POST);

	if(isset($_POST['new_souche']) && $_POST['new_souche'] == "Nouvelle Souche"){
		$validator->isText('souche','Le nom de la souche n\'est pas valide.');
		if($validator->isValid()){
			$new = new TeamIMet;
			if($new->newS($_POST['souche'])){
	      Session::getInstance()->setFlash('success', "Souche sauvegardee !");
	      App::redirect('imet/souchier_n3/');
	      exit();
	    }
		}
	}
	if(isset($_POST['new_antibio']) && $_POST['new_antibio'] == "Nouvel Antibiotique"){
		$validator->isText('antibiotique','Le nom de l\'antiobiotique n\'est pas valide.');
		if($validator->isValid()){
			$new = new TeamIMet;
			if($new->newA($_POST['antibiotique'])){
	      Session::getInstance()->setFlash('success', "Antibiotique sauvegarde !");
	      App::redirect('imet/souchier_n3/');
	      exit();
	    }
		}
	}
}
//===========================================================
$rapidAccess = TeamIMet::getRapidAccess();
$menuItem = [];

$title = 'iMet';
$titleLink = 'iMet/index.php';
echo Header::getHeader($title, $titleLink, $rapidAccess, $menuItem);
if(isset($validator)){echo $validator->afficheErrors($errors);}
echo "<h2 class='mt-3'>Nouvelle Souche</h2>";

$form = new TeamIMetForm($_POST);
echo $form->openFormHorizontal();
echo $form->inputHorizontal('souche','text','4',"Souche : ");
echo $form->submitHorizontal('primary','new_souche','Nouvelle Souche');
echo $form->closeFormHorizontal();

//===========================================================
echo "<h2 class='mt-3'>Nouvel Antibiotique</h2>";

echo $form->openFormHorizontal();
echo $form->inputHorizontal('antibiotique','text','4',"Antibiotique : ");
echo $form->submitHorizontal('success','new_antibio','Nouvel Antibiotique');
echo $form->closeFormHorizontal();



?>
<?php echo Footer::getFooter();?>
