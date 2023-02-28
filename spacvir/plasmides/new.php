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
  Session::setFlash('danger', "Vous n'êtes pas autorisé à accéder à cette page.");
  App::redirect('index.php');
  exit();
}
$arr = [310,311,322];
if(in_array($user->id,$arr)){
    $super = true;
}
else{
    $super = false;
    App::redirect('index.php');
    exit();
}
//===========================================================
// verification des datas du formulaire
if(!empty($_POST)){
    $errors =[];
    $db = App::getDatabase();
    $validator = new Validator($_POST);
    $validator->isText('name', "The name is not valid.");
    if(!empty($_FILES['seq_file']['name'])){
        $validator->isFileUniq('seq_file','spacvir','plasmides','biomol_files',$db, App::getTableSpacvirPlasmides(), "The file already exists with this name.");
    }
    if($validator->isValid()){
        if(!empty($_FILES['seq_file']['name'])){
            $up = new Upload('seq_file','spacvir','plasmides','biomol_files');
            $seqFileName = $up->upload();
        }
        else{
            $seqFileName = NULL;
        }
        if(isset($_POST['vector_seq'])){$vector_seq = $_POST['vector_seq'];}else{$vector_seq= NULL;}
        if(isset($_POST['insert_seq'])){$insert_seq = $_POST['insert_seq'];}else{$insert_seq = NULL;}
        $new = new TeamSpacvirPlasmide();
        if($plamide = $new->newPlasmide(
            $_POST['name'],
            $_POST['dna_sequence'],
            $_POST['number'],
            $_POST['resistance'],
            $_POST['investigateur'],
            $_POST['origin_vector'],
            $_POST['inserted_dna'],
            $_POST['cloning_method'],
            $_POST['bacterie'],
            $vector_seq,
            $insert_seq,
            $_POST['glycerol_stock'],
            $_POST['dna_stock'],
            $_POST['date'],
            $_POST['comments'],
            $seqFileName
        )){
            Session::getInstance()->setFlash('success', "Plasmide recorded !");
            App::redirect('spacvir/plasmides/index.php');
            exit();
        }
    }
    else{
        $errors = $validator->getErrors();
    }
}
//=========================================================

$rapidAccess = TeamSpacvir::getRapidAccess();
$menuItem = [];

$title = 'SpacVir';
$titleLink = 'spacvir/index.php';

echo Header::getHeader($title, $titleLink, $rapidAccess, $menuItem);
if(isset($validator)){echo $validator->afficheErrors($errors,"EN");}

?>

<h1 class="mt-3">SpacVir Plasmide</h1>
<?php

$form = new TeamSpacvirForm($_POST);
echo "<form method='post' action='' enctype='multipart/form-data'>";
echo $form->input('name', 'text', 'Name : ', '', "");
echo $form->input('number', 'text', 'Numéro : ', '', "");
echo $form->input('resistance', 'text', 'Résistance : ', '', "");
echo $form->textArea("dna_sequence","DNA Séquence :","");
echo $form->input('investigateur', 'text', 'Investigateur(s) : ', '', "");
echo $form->input('origin_vector', 'text', 'Vecteur d\'origine : ', '', "");
echo $form->input('inserted_dna', 'text', 'Insert : ', '', "");
echo $form->input('cloning_method', 'text', 'Méthode de clonage : ', '', "");
echo $form->input('bacterie', 'text', 'Bactérie : ', '', "");
echo $form->checkBox("vector_seq", "vector", 1, "Séquence du vecteur vérifiée","","");
echo $form->checkBox("insert_seq", "insert", 1, "Séquence de l'insert vérifiée","","");
echo $form->input('glycerol_stock', 'text', 'Glycérol Stock : ', '', "");
echo $form->input('dna_stock', 'text', 'Stock d\'ADN : ', '', "");
echo $form->input("date","date", "Date :","","");
echo $form->textArea("comments","Commentaires :","");
echo $form->input('seq_file','file', "Fichier SnapGene : ",'',"Supported format : .dna, .xdna");
echo $form->submit('primary', 'new', 'Ajouter');
echo "</form>";
?>



<?= Footer::getFooter();
