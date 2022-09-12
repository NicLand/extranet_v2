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
$access = new Access("reger", $user);
if(!$access->access()){
  Session::setFlash('danger', "Vous n'êtes pas autorisé à accéder à cette page.");
  App::redirect('index.php');
  exit();
}
//===========================================================
if(isset($_GET['p']) && strlen($_GET['p']) == 3 && isset($_GET['id']) && $_GET['id'] !=""){
  $p = $_GET['p'];
  $id = $_GET['id'];
  if($p == 'cat'){
    $h1 = "Update Protocol category";
    $cat = new Protocol("reger");
    $data = $cat->getCategory($id);
    if(!$data){
      App::redirect('reger/protocol/');
      exit();
    }
  }
  if($p == 'pro'){
    $h1 = "Update Protocol";
    $pro = new Protocol("reger");
    $data = $pro->getProtocolCKE($id);
    if(!$data){
      App::redirect('reger/protocol/');
      exit();
    }
  }
}
else{
  App::redirect('reger/protocol/');
  exit();
}
//===========================================================
$rapidAccess = TeamReger::getRapidAccess();
$menuItem = [];
if(!empty($_POST)){
  $errors=[];
  $validator = new Validator($_POST);
  if($p == 'pro'){
    if(isset($_POST['update'])){
      $validator->isText('name','The protocol name is not valid.');
      $validator->isSelect('category', "Please choose a category for your protocol.");
      if($validator->isValid()){
            $protocol = new Protocol("reger");
            if($protocol->upProtocolCKE($id,$_POST['name'], $_POST['category'], $_POST['protocolBody'],$user->id)){
              Session::getInstance('success', 'Protocol updated !');
              App::redirect("reger/protocol/");
              exit();
          }
        }
      else{
          $errors = $validator->getErrors();
        }
      }
      if(isset($_POST['delete'])){
        $delete = new Protocol("reger");
        if($delete->delProtocol($id)){
          Session::getInstance()->setFlash('success', "Protocol deleted !");
          App::redirect('reger/protocol/');
          exit();
      }
    }
}
    if($p == 'cat'){
      if(isset($_POST['update'])){
        $validator->isText('category','The category name is not valid.');
        if($validator->isValid()){
          $protocol = new Protocol("reger");
            if($protocol->upCategory($id, $_POST['category'])){
              Session::getInstance('success', 'Category recorded !');
              App::redirect("reger/protocol/");
              exit();
            }
        }
        else{
            $errors = $validator->getErrors();
          }
      }
      elseif(isset($_POST['delete'])){
        $delete = new Protocol("reger");
          if($delete->delCategory($id)){
            Session::getInstance()->setFlash('success', "Category deleted !");
            App::redirect('reger/protocol/');
            exit();
          }
      }
    }
}

//===========================================================
$title = 'ex-REGER';
$titleLink = 'reger/index.php';

echo Header::getHeader($title, $titleLink, $rapidAccess, $menuItem);
if(isset($validator)){echo $validator->afficheErrors($errors,"EN");}

?>

<h1 class="mt-3"><?= $h1;?></h1>
<?php

$form = new Form($_POST);
echo "<form method='post' action='#' enctype='multipart/form-data'>";
if($p == 'pro'){
  echo $form->inputInline('name', 'text', 'Protocol Name : ',$data->name);
  echo $form->protocolCategorySelect(app::getTableRegerProtocolCategories(),"category", "Category : ", "Choose a category",$data->category);
  echo $form->ckeditorText('protocolBody','Protocol :',$data->protocolBody);
  echo $form->submit('primary','update','Update this protocol');
  echo $form->delete();
}
elseif($p == 'cat'){
  echo $form->inputInline('category', 'text', 'Category Name : ',$data->category);
  echo $form->submit('primary','update','Update this category');
  echo $form->delete('opt');
}
echo "</form>";
echo "<script>
CKEDITOR.replace( 'protocolBody',
{
filebrowserBrowseUrl : '/extranet_v2/include/ckfinder/ckfinder.html',
filebrowserImageBrowseUrl : '/extranet_v2/include/ckfinder/ckfinder.html?type=Images',
filebrowserFlashBrowseUrl : '/extranet_v2/include/ckfinder/ckfinder.html?type=Flash',
filebrowserUploadUrl : '/extranet_v2/include/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
filebrowserImageUploadUrl : '/extranet_v2/include/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
filebrowserFlashUploadUrl : '/extranet_v2/include/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
});
</script>";
echo Footer::getFooter();
