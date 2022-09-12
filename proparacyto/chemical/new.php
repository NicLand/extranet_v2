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
$access = new Access("proparacyto", $user);
if(!$access->access()){
  Session::getInstance()->setFlash('danger', "Vous n'�tes pas autoris� � acc�der � cette page.");
  App::redirect('index.php');
  exit();
}
//===========================================================
if(!empty($_POST)){
  $errors=[];
  $icone ="";
  for($i=1;$i<=9;$i++){
    if(isset($_POST['icone'.$i])){$icone .= ",".$i;}
  }
  $icone = ltrim($icone,",");

  $validator = new Validator($_POST);
  $validator->isText('name', "The name is not valid.");
  if($validator->isValid()){
    if(!empty($_FILES['msds']['name'])){
      $up = new Upload('msds','proparacyto','chemical','msds');
      $msds = $up->upload();
    }else{$msds=NULL;}
    if(!empty($_FILES['documentation']['name'])){
      $up = new Upload('documentation','proparacyto','chemical','doc');
      $doc = $up->upload();
    }else{$doc=NULL;}
    $data = new Chemical('proparacyto');
    if($data->newChemical(
      $_POST['name'],
      $_POST['real_name'],
      $_POST['formule'],
      $_POST['mw'],
      $_POST['company'],
      $_POST['reference'],
      $_POST['quantity'],
      $_POST['cas'],
      $icone,
      $_POST['localisation'],
      $msds,
      $doc
    )){
      Session::getInstance()->setflash('success',"Chemical recorded !");
      App::redirect('proparacyto/chemical/index.php');
      exit();
    }
    else{
      Session::getInstance()->setflash('alert',"A problem occurs ! Please repeat.");
    }
  }
}
//===========================================================
$menuItem = [];
$rapidAccess = TeamProparacyto::getRapidAccess();

$title = 'ProParaCyto';
$titleLink = 'proparacyto/index.php';

echo Header::getHeader($title, $titleLink, $rapidAccess, $menuItem);
if(isset($validator)){echo $validator->afficheErrors($errors,"EN");}

?>

<h1 class="mt-3">Chemicals list</h1>

<?php
//echo '<a href="'.App::getRoot().'/proparacyto" class="btn btn-secondary mb-2" role="button" aria-pressed="true">Back to Proparacyto</a>';

$form = new cskForm($_POST);

echo "<form method='post' action='#' enctype='multipart/form-data'>";
  echo $form->input('name','text','Usual Name : ','');
  echo $form->input('real_name','text','Real Name : ','');
  echo $form->input('formule','text','Formule : ','');
  echo $form->input('mw','text','MW : ','');
  echo $form->input('company','text','Company : ','');
  echo $form->input('reference','text','Reference : ','');
  echo $form->input('quantity','text','Quantity : ','');
  echo $form->input('cas','text','CAS number : ','');
  echo $form->checkBoxChemIcone('icone', "Icones :",'');
  echo $form->input('localisation','text','Localisation : ','');
  echo $form->input('msds','file','MSDS : ','');
  echo $form->input('documentation','file','Documentation : ','');
  echo $form->submit('primary','new','New Chemical');
echo "</form>";
?>


<?php echo Footer::getFooter();?>
