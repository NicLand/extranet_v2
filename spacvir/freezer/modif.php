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
$access = new Access("spacvir", $user);
if(!$access->access()){
  Session::setFlash('danger', "Vous n'�tes pas autoris� �� acc�der � cette page.");
  App::redirect('index.php');
  exit();
}
//===========================================================
if(isset($_GET['id']) && $_GET['id']!=""){
  $id = $_GET['id'];
  $freezer = new TeamSpacvirFreezer;
  $data = $freezer->getbox($id);
  $freezer_name = $freezer->getFreezerName($data->freezer);

}
else{
  App::redirect('spacvir/index.php');
  exit();
}

//===========================================================

if(!empty($_POST)){
  if(isset($_POST['update'])){
    $freezer = new TeamSpacvirFreezer;
    if($freezer->upFreezer($id,$_POST['name'],$_POST['content'])){
      Session::getInstance('success', 'Box updated !');
      App::redirect("spacvir/freezer/");
      exit();
    }
  }
}

//=========================================================

$rapidAccess = TeamSpacvir::getRapidAccess();
$menuItem = [];

$title = 'SpacVir';
$titleLink = 'spacvir/index.php';

echo Header::getHeader($title, $titleLink, $rapidAccess, $menuItem);
?>
<h1 class="mt-3">-80°C Freezer</h1>

<?php
$form = new cskForm($_POST);
echo "<h4>Position : $freezer_name->name | Stage :$data->stage | Rack : $data->rack | Box : $data->box</h4>";
echo "<form method='post' action='#' enctype='multipart/form-data'>";
echo $form->inputInline('name', 'text', 'Box Name : ',$data->name);
echo $form->ckeditorText('content','Content :',$data->content);
echo $form->submit('primary','update','Update this box');
echo "</form>";

echo "<script>
CKEDITOR.replace( 'content',
{
filebrowserBrowseUrl : '/extranet_v2/include/ckfinder/ckfinder.html',
filebrowserImageBrowseUrl : '/extranet_v2/include/ckfinder/ckfinder.html?type=Images',
filebrowserFlashBrowseUrl : '/extranet_v2/include/ckfinder/ckfinder.html?type=Flash',
filebrowserUploadUrl : '/extranet_v2/include/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
filebrowserImageUploadUrl : '/extranet_v2/include/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
filebrowserFlashUploadUrl : '/extranet_v2/include/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
});
</script>";
?>

<?= Footer::getFooter();
