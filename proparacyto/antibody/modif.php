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
if(isset($_GET['ab']) && $_GET['ab'] !="" && isset($_GET['id']) && $_GET['id'] !=""){
  $antibody = new Antibody;
  $ab = $_GET['ab'];
  $id = $_GET['id'];
  if($ab==1){
    $h1 = "Update Primary Antibody";
    $folder = "prim";
    $table = App::getTablePrimAntibody();
    $diffField = 'immunogen';
    $data = $antibody->getSingle($ab,$id);
  }
  elseif($ab==2){
    $h1 = "Update Secondary Antibody";
    $folder = "sec";
    $table = App::getTableSecAntibody();
    $diffField = 'ig_specificity';
    $data = $antibody->getSingle($ab,$id);
  }
}else{
  App::redirect('proparacyto/antibody/');
  exit();
}
//===========================================================
  //on s'occupe des anticorps primaires
  if(!empty($_POST)){
    if(isset($_POST['update'])){
    $db = App::getDatabase();
    $errors=[];
    $validator = new Validator($_POST);
    $validator->isText('name','The name is not valid.');
    $validator->isDate('date','The date is not valid.');

    if($validator->isValid()){

      if(!empty($_FILES['document']['name'])){
        $up = new Upload('document','proparacyto','antibody', $folder);
        if(!empty($data->document)){
          $del = $up->delete($data->document);
        }
        $file = $up->upload();
      }
      else{
        $file = $data->document;
      }
      $new = new Antibody;
      if($new->updateAntibody($ab,$id,[
        $_POST['name'],
        $_POST['made_in'],
        $_POST['ig_class'],
        $_POST[$diffField],
        $_POST['conjugate'],
        $_POST['company'],
        $_POST['reference'],
        $_POST['batch'],
        $_POST['dilution_used'],
        $_POST['storage'],
        $_POST['date'],
        $_POST['comments'],
        $file
      ])
      ){
        Session::getInstance()->setFlash('success', "Antibody updated !");
        App::redirect('proparacyto/antibody/list.php?l='.$ab.'');
        exit();
      }
    }
    else{
        $errors = $validator->getErrors();
      }
  }
  elseif(isset($_POST['delete'])){
    $delete = new Antibody;
    if($delete->deleteAntibody($ab, $id)){
      $del = new Upload('document','proparacyto','antibody', $folder);
      $del->delete($data->document);
      Session::getInstance()->setFlash('success', "Antibody deleted !");
      App::redirect('proparacyto/antibody/list.php?l='.$ab.'');
      exit();
  }
}
}

//===========================================================
$rapidAccess = TeamProparacyto::getRapidAccess();
$menuItem = [];

$title = 'ProParaCyto';
$titleLink = 'proparacyto/index.php';

echo Header::getHeader($title, $titleLink, $rapidAccess, $menuItem);
if(isset($validator)){echo $validator->afficheErrors($errors,"EN");}

?>

<h1 class="mt-3"><?= $h1;?></h1>
<?php
$form = new cskForm($_POST);
echo "<form method='post' action='#' enctype='multipart/form-data'>";
  echo $form->input('name','text','Name : ',$data->name);
  echo $form->input('made_in','text','Made in : ',$data->made_in);
  echo $form->input('ig_class','text','Ig class : ',$data->ig_class);
  if($ab==1){echo $form->input('immunogen','text','Immunogen : ',$data->immunogen);}
  elseif($ab==2){echo $form->input('ig_specificity','text','Ig specificity : ',$data->ig_specificity);}
  echo $form->input('conjugate','text','Conjugate : ',$data->conjugate);
  echo $form->input('company', 'text','Company : ',$data->company);
  echo $form->input('reference','text','Reference : ',$data->reference);
  echo $form->input('batch','text','Batch : ',$data->batch);
  echo $form->textArea('dilution_used','Dilution :',$data->dilution_used);
  echo $form->textArea('storage','Storage :',$data->storage);
  echo $form->input('date','text','Date : ',$data->date);
  echo $form->textArea('comments','Comments : ',$data->comments);
  if(!empty($data->document)){echo "<div class='alert alert-info'>This antibody has already a datafile : $data->document</div>";}
  echo $form->input('document','file','Documentation :','',"Supported format : .pdf");
  echo $form->submit('primary','update','Update');
  echo $form->delete();
echo "</form>";

echo Footer::getFooter();
