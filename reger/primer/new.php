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
$access = new Access("reger", $user);
if(!$access->access()){
  Session::setFlash('danger', "Vous n'�tes pas autoris� � acc�der � cette page.");
  App::redirect('index.php');
  exit();
}

//===========================================================
// verification des datas du formulaire
if(!empty($_POST)){
  $errors =[];
  $db = App::getDatabase();
  $tab = App::getTableRegerPrimers();
  $validator = new Validator($_POST);
  $validator->isNumeric('num',"The unique number is not correct.");
  if($validator->isValid()){
    $validator->isUniq('num', $db, $tab, "This unique number already exists.");
  }
  $validator->isText('name', "The name is not valid.");
  if($validator->isValid()){
    $validator->isUniq('name', $db, $tab , "This primer name already exists.");
  }
  $validator->isDNA('sequence',"This primer sequence is not DNA.");
  if($validator->isValid()){
    $validator->isUniq('sequence', $db, $tab, "This DNA sequence already exists");
  }

if($validator->isValid()){
  $primer = new Primer('reger');
  if($primer->newPrimerReger(
    $_POST['num'],
    $_POST['name'],
    $_POST['five_modif'],
    $_POST['sequence'],
    $_POST['three_modif'],
    $_POST['Tm'],
    $_POST['mol_w'],
    $_POST['concentration_ng'],
    $_POST['concentration_uM'],
    $_POST['date'],
    $_POST['investigateur'],
    $_POST['comments']
  )){
  Session::getInstance()->setFlash('success', 'The new primer is recorded.');
  App::redirect('reger/primer/index.php');
  exit();
  }
}
else{
    $errors = $validator->getErrors();
  }
}
//===========================================================
$rapidAccess = TeamReger::getRapidAccess();
$menuItem = [];

$title = 'ex-REGER';
$titleLink = 'reger/index.php';

echo Header::getHeader($title, $titleLink, $rapidAccess, $menuItem);
if(isset($validator)){echo $validator->afficheErrors($errors);}
echo '<a class="btn btn-secondary m-3" href="index.php" role="button">Back to Primers list</a>';

?>

<h1 class="mt-3">New Oligonucleotide</h1>
<form action="" method="post">
<?php
$today = date("d/m/Y");
$table = App::getTableRegerPrimers();
$lastnum = App::getDatabase()->query("SELECT num FROM $table ORDER BY id DESC LIMIT 1")->fetch();
$form = new cskForm($_POST);
echo $form->input('num','number','Unique Number',$lastnum->num+1);
echo $form->input('name', 'text', 'Name', $lastnum->num+1);
echo $form->input('five_modif','text',"5' modif",'');
echo $form->input('sequence','text','Sequence','');
echo $form->input('three_modif','text',"3' modif",'');
echo $form->input('Tm','text',"Tm",'');
echo $form->input('mol_w','text',"MW",'');
echo $form->input('concentration_ng','text',"[ng]",'');
echo $form->input('concentration_uM','text',"[um]",'');
echo $form->input('date','text', 'Date : ','');
echo $form->input('investigateur','text',"Investigateur",$user->firstname.' '.$user->name);
echo $form->textArea('comments','Comments : ','');
echo $form->submit('primary', 'add', 'Add this new primer');
?>
</form>
<?= Footer::getFooter();
