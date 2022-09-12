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
if(isset($_GET['id']) && $_GET['id'] !=''){
  $id = $_GET['id'];
  $mod = new Primer('reger');
  $primer = $mod->getSinglePrimer($id);
}
// verification des datas du formulaire
if(!empty($_POST)){
  if(isset($_POST['update'])){
    $errors =[];
    $db = App::getDatabase();
    $tab = App::getTableRegerPrimers();
    $validator = new Validator($_POST);
    $validator->isNumeric('num',"The unique number is not correct.");
    $validator->isText('name', "The name is not valid.");
    $validator->isDNA('sequence',"This primer sequence is not DNA.");
    if($validator->isValid()){
      $primer = new Primer('reger');
      $primer->upPrimerReger(
        $id,
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
      );
      Session::getInstance()->setFlash('success', 'The new primer is recorded.');
      App::redirect('reger/primer/index.php');
      exit();
    }
    else{
        $errors = $validator->getErrors();
      }
  }
  if(isset($_POST['delete'])){
    $delete = new Primer('reger');
    if($delete->deletePrimer($id)){
      Session::getInstance()->setFlash('success', "The primer has been deleted !");
      App::redirect('reger/primer/index.php');
      exit();
    }
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

<h1 class="mt-3">Modif Oligonucleotide</h1>
<form action="" method="post">
<?php
$form = new cskForm($_POST);
echo $form->input('num','number','Unique Number',$primer->num);
echo $form->input('name', 'text', 'Name', $primer->name);
echo $form->input('five_modif','text',"5' modif",$primer->five_modif);
echo $form->input('sequence','text','Sequence',$primer->sequence);
echo $form->input('three_modif','text',"3' modif",$primer->three_modif);
echo $form->input('Tm','text',"Tm",$primer->Tm);
echo $form->input('mol_w','text',"MW",$primer->mol_w);
echo $form->input('concentration_ng','text',"[ng]",$primer->concentration_ng);
echo $form->input('concentration_uM','text',"[um]",$primer->concentration_uM);
echo $form->input('date','text', 'Date : ',$primer->date);
echo $form->input('investigateur','text',"Investigateur",$primer->investigateur);
echo $form->textArea('comments','Comments : ',$primer->comments);
echo $form->submit('primary', 'update', 'Update this primer');
echo $form->delete();
?>
</form>
<?= Footer::getFooter();
