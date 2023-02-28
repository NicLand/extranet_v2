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
if(isset($_GET['id'])){
  $id = $_GET['id'];
  $chem = new Chemical('proparacyto');
  $mod = $chem->getSingle($id);
}


if(!empty($_POST)){
  if(isset($_POST['update'])){
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
      if(!empty($mod->msds)){
        $del = new Upload('msds','proparacyto','chemical','msds');
        $del->delete($mod->msds);
      }
      $msds = $up->upload();
    }
    else{
      $msds=$mod->msds;
    }
    if(!empty($_FILES['documentation']['name'])){
      $up = new Upload('documentation','proparacyto','chemical','doc');
      if(!empty($mod->documentation)){
        $del = new Upload('documentation','proparacyto','chemical','doc');
        $del->delete($mod->documentation);
      }
      $doc = $up->upload();
    }
    else{
      $doc=$mod->documentation;
    }
    $data = new Chemical('proparacyto');
    if($data->upChemical(
      $id,
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
      Session::getInstance()->setflash('success',"Chemical updated !");
      App::redirect('proparacyto/chemical/index.php');
      exit();
      }
    }
  else{
      $errors = $validator->getErrors();
    }
  }
  if(isset($_POST['delete'])){
    $delete = new Chemical('proparacyto');
    if($delete->delChemical($id)){
      $del = new Upload('msds','proparacyto','chemical', 'msds');
      $del->delete($mod->msds);
      $del2 = new Upload('documentation','proparacyto','chemical','doc');
      $del2->delete($mod->documentation);
      Session::getInstance()->setFlash('success', "Chemical deleted !");
      App::redirect('proparacyto/chemical/');
      exit();
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
  echo $form->input('name','text','Usual Name : ',$mod->name);
  echo $form->input('real_name','text','Real Name : ',$mod->real_name);
  echo $form->input('formule','text','Formule : ',$mod->formule);
  echo $form->input('mw','text','MW : ',$mod->mw);
  echo $form->input('company','text','Company : ',$mod->company);
  echo $form->input('reference','text','Reference : ',$mod->reference);
  echo $form->input('quantity','text','Quantity : ',$mod->quantity);
  echo $form->input('cas','text','CAS number : ',$mod->cas);
  echo $form->checkBoxChemIcone('icone', "Icones :", $mod->icone);
  echo $form->input('localisation','text','Localisation : ',$mod->localisation);
  if(!empty($mod->msds)){echo "<div class='alert alert-info'>This chemical has already a MSDS datasheet : $mod->msds</div>";}
  echo $form->input('msds','file','MSDS : ','');
  if(!empty($mod->documentation)){echo "<div class='alert alert-info'>This chemical has already a documentation : $mod->documentation</div>";}
  echo $form->input('documentation','file','Documentation : ','');
  echo $form->submit('primary','update','Update Chemical');
  echo $form->delete();
echo "</form>";
?>


<?php echo Footer::getFooter();?>
