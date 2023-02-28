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
if(isset($_GET['id'])){
    $id = $_GET['id'];
    $plasmide = new TeamSpacvirPlasmide();
    $data = $plasmide->getPlasmides($id);
}
//=========================================================
if(!empty($_POST)){
    if(isset($_POST['upload'])){
        $errors = [];
        $db = App::getDatabase();
        $validator = new Validator($_POST);
        $validator->isText('name', "The name is not valid.");
        if (!empty($_FILES['seq_file']['name'])) {
            $validator->isFileUniq('seq_file', 'spacvir', 'plasmides', 'biomol_files', $db, App::getTableSpacvirPlasmides(), "The file already exists with this name.");
        }
        if ($validator->isValid()) {
            if (!empty($_FILES['seq_file']['name'])) {
                $up = new Upload('seq_file', 'spacvir', 'plasmides', 'biomol_files');
                if (!empty($data->seq_file)) {
                    $del = $up->delete($data->seq_file);
                }
                $seqFileName = $up->upload();
            } else {
                $seqFileName = $data->seq_file;
            }
            if(isset($_POST['vector_seq'])){$vector_seq = $_POST['vector_seq'];}else{$vector_seq = NULL;}
            if(isset($_POST['insert_seq'])){$insert_seq = $_POST['insert_seq'];}else{$insert_seq = NULL;}

            $new = new TeamSpacvirPlasmide();
            if ($plasmide = $new->upPlasmide(
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
                $seqFileName,
                $id
            )) {
                Session::getInstance()->setFlash('success', "Plasmide recorded !");
                App::redirect('spacvir/plasmides/index.php');
                exit();
            }
        } else {
            $errors = $validator->getErrors();
        }
    }
    if(isset($_POST['delete'])){
        $delete = new TeamSpacvirPlasmide();
        if($delete->deletePlasmide($id)){
            $del = new Upload('link_biomol','spacvir','plasmides', 'biomol_files');
            $del->delete($mod->link_biomol);
            Session::getInstance()->setFlash('success', "Plasmide deleted !");
            App::redirect('spacvir/plasmides/');
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

<h1 class="mt-3">SpacVir Plasmide</h1>
<?php
$plasmide = new TeamSpacvirPlasmide();

$form = new TeamSpacvirForm($_POST);
echo "<form method='post' action='' enctype='multipart/form-data'>";
echo $form->input('name', 'text', 'Name : ', "$data->name", "");
echo $form->input('number', 'text', 'Numéro : ', "$data->number", "");
echo $form->input('resistance', 'text', 'Résistance : ', $data->resistance, "");
echo $form->input('dna_sequence', 'text', 'DNA Séquence : ', $data->dna_sequence, "");
echo $form->input('investigateur', 'text', 'Investigateur(s) : ', $data->investigateur, "");
echo $form->input('origin_vector', 'text', 'Vecteur d\'origine : ', $data->origin_vector, "");
echo $form->input('inserted_dna', 'text', 'Insert : ', $data->inserted_dna, "");
echo $form->input('cloning_method', 'text', 'Méthode de clonage : ', $data->cloning_method, "");
echo $form->input('bacterie', 'text', 'Bactérie : ', $data->bacterie, "");
if($data->vector_seq == 1){$checked = 'checked';}else{$checked ='';}
echo $form->checkBox("vector_seq", "vector", 1, "Séquence du vecteur vérifiée",$checked,"");
if($data->insert_seq == 1){$checked2 = 'checked';}else{$checked2 ='';}
echo $form->checkBox("insert_seq", "insert", 1, "Séquence de l'insert vérifiée",$checked2,"");
echo $form->input('glycerol_stock', 'text', 'Glycérol Stock : ', $data->glycerol_stock, "");
echo $form->input('dna_stock', 'text', 'Stock d\'ADN : ', $data->dna_stock, "");
echo $form->input("date","date", "Date :",$data->date,"");
echo $form->textArea("comments","Commentaires :",$data->comments);
if(!empty($data->seq_file)){echo "<div class='alert alert-info'>Ce Plasmide à déjà un fichier SnapGene : $data->seq_file</div>";}
echo $form->input('seq_file','file', "Fichier SnapGene : ",'',"Supported format : .dna, .xdna");
echo $form->submit('primary', 'upload', 'Modifier');
echo $form->delete("FR");
echo "</form>";
?>



<?= Footer::getFooter();
