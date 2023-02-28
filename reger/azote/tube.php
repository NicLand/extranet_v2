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
  Session::getInstance()->setFlash('danger', "Vous n'�tes pas autoris� � acc�der � cette page.");
  App::redirect('../index.php');
  exit();
}
//===========================================================
if(!empty($_GET['id'])){
  $id = $_GET['id'];
}
if(!empty($_POST)){
  if(isset($_POST['update'])){
    $validator = new Validator($_POST);
    if($validator->isValid()){
      $data = new TeamReger;
      if($data->upAzote(
        $id,
        $_POST['name'],
        $_POST['date'],
        $_POST['num_tube'],
        $_POST['description']
      )){
        Session::getInstance()->setflash('success',"Tube modifie !");
        App::redirect('reger/azote/index.php');
        exit();
        }
      }
    else{
        $errors = $validator->getErrors();
      }
    }
    if(isset($_POST['delete'])){
      $del = new TeamReger;
      if($del->upAzote($id,NULL,NULL,NULL,NULL)){
        Session::getInstance()->setflash('success',"Tube decongele !");
        App::redirect('reger/azote/index.php');
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
echo '<a class="btn btn-secondary m-3" href="index.php" role="button">Back to Cuve</a>';

?>

<h1 class="mt-3 mb-3">Details du tube</h1>

<?php
$azote = new TeamReger;
echo $azote->afficheTube($id);
?>
<?php echo Footer::getFooter();?>
