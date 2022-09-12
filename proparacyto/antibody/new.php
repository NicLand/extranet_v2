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
if(isset($_GET['ab']) && $_GET['ab'] !=""){
  $ab = $_GET['ab'];
  if($ab==1){
    $h1 = "New Primary Antibody";
    $folder = "prim";
    $table = App::getTablePrimAntibody();
    $diffField = 'immunogen';
  }
  elseif($ab==2){
    $h1 = "New Secondary Antibody";
    $folder = "sec";
    $table = App::getTableSecAntibody();
    $diffField = 'ig_specificity';
  }
}else{
  App::redirect('proparacyto/antibody/');
  exit();
}
//===========================================================
  //on s'occupe des anticorps primaires
  if(!empty($_POST)){
    $errors=[];
    $validator = new Validator($_POST);
    $validator->isText('name','The name is not valid.');
    $validator->isDate('date','The date is not valid.');
    if(!empty($_FILES['link_biomol']['name'])){
      $validator->isFileUniq('document','proparacyto','antibody',$folder,$db, $table, "This File already exists.");
    }
    if($validator->isValid()){
      if(!empty($_FILES['document']['name'])){
        $up = new Upload('document','proparacyto','antibody', $folder);
        $file = $up->upload();
      }
      else{
        $file = NULL;
      }
      $new = new Antibody;
      if($new->newAntibody($ab,[
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
        Session::getInstance()->setFlash('success', "Antibody recorded !");
        App::redirect('proparacyto/antibody/index.php');
        exit();
      }
    }
    else{
        $errors = $validator->getErrors();
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
  echo $form->input('name','text','Name : ','');
  echo $form->input('made_in','text','Made in : ','');
  echo $form->input('ig_class','text','Ig class : ','');
  if($ab==1){echo $form->input('immunogen','text','Immunogen : ','');}
  elseif($ab==2){echo $form->input('ig_specificity','text','Ig specificity : ','');}
  echo $form->input('conjugate','text','Conjugate : ','');
  echo $form->input('company', 'text','Company : ','');
  echo $form->input('reference','text','Reference : ','');
  echo $form->input('batch','text','Batch : ','');
  echo $form->textArea('dilution_used','Dilution :','');
  echo $form->textArea('storage','Storage :','');
  echo $form->input('date','date','Date : ','');
  echo $form->textArea('comments','Comments : ','');
  echo $form->input('document','file','Documentation :','',"Supported format : .pdf");
  echo $form->button('submit','primary', 'Add this antibody');


echo Footer::getFooter();
