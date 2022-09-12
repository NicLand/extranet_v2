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

//===========================================================
$rapidAccess = TeamProparacyto::getRapidAccess();
$menuItem = [];

$title = 'ProParaCyto';
$titleLink = 'proparacyto/index.php';

echo Header::getHeader($title, $titleLink, $rapidAccess, $menuItem);
?>

<h1 class="mt-3">Y2H interactome</h1>
<?php
echo '<a href="'.App::getRoot().'/proparacyto/y2h/new.php" class="btn btn-primary m-1" role="button">Add a new AD or BK vector</a>';
echo '<a href="'.App::getRoot().'/proparacyto/y2h/choice.php" class="btn btn-success m-1" role="button">Choose proteins/constructions for interactions</a>';

if(!empty($_POST)){
/* Sélection des vecteurs AD & BD */
$listVectAD='';
 foreach ($_POST['AD'] as $vectAD => $indexAD){

$listVectAD .= ','.$indexAD;
 }

$listVectAD = ltrim($listVectAD, ',');
/* =============================== */
$listVectBD='';
 foreach ($_POST['BD'] as $vectBD => $indexBD){

$listVectBD .= ','.$indexBD;
 }

$listVectBD = ltrim($listVectBD, ',');

/* =============================== */


$statement_AD = Database::query('SELECT * FROM rob_y2h_ad WHERE id IN ('.$listVectAD.') ORDER BY id_prot, id ASC');
$statement_BD = Database::query('SELECT * FROM rob_y2h_bd WHERE id IN ('.$listVectBD.') ORDER BY id_prot, id ASC');
$vecteurs_AD  = $statement_AD->fetchAll();
$vecteurs_BD  = $statement_BD->fetchAll();


/* Définition du tableau & affichage de l'en-tête (vecteurs AD) */
echo '<table id="interactome" border="1" cellspacing="0" cellpadding="5"><tr><td>&nbsp;</td>';
foreach($vecteurs_AD as $vecteur_AD) {
    echo '<td><strong>' . $vecteur_AD->ad_vector . '</strong></td>';
}
echo '</tr>';

/* Affichage des données du tableau */
foreach($vecteurs_BD as $vecteur_BD) {
	if ($vecteur_BD->autoactiv == "1"){$style_auto= "class='bg-warning'";}
	elseif ($vecteur_BD->bd_control == "1"){$style_auto= 'class="bd_control"';}
	else {$style_auto= '';}



    echo '<tr '.$style_auto.'><td><strong><a href="modif_vector.php?bd_id=' . $vecteur_BD->id. '">' . $vecteur_BD->bd_vector . '</a></strong></td>';

    // Intersections
    foreach($vecteurs_AD as $vecteur_AD) {
        $statement_inter = Database::query('SELECT interaction FROM rob_y2h_inter WHERE id_ad_vector = ' . $vecteur_AD->id . ' AND id_bd_vector = ' . $vecteur_BD->id);
        $intersection     = $statement_inter->fetchColumn();

        // si interaction existante ou pas
		if ($vecteur_AD->ad_control =='1'){$style_control= 'class="ad_control"';}
		else {$style_control= '';}

		//coloration de la cellule avec interaction pos ou weak
		$array  = array('yes', 'Yes', 'YES', 'weak', 'Weak', 'WEAK', 'positive', 'positif', 'Positive', 'Positif');
		//if(strposa($intersection, $array)==true){$style_inter = 'class="interPos"';}else{$style_inter='';} //
$style_inter = "class='bg-primary'";

        echo !empty($intersection) ? '<td '.$style_inter.' '.$style_control.' align="center" onClick="javascript:update(this,' . $vecteur_AD->id . ',' . $vecteur_BD->id . ');" title="'. $vecteur_AD->ad_vector . ' X '. $vecteur_BD->bd_vector .'">' . $intersection . '</td>' : '<td '.$style_control.' align="center"  onclick="javascript:ajout(this,' . $vecteur_AD->id . ',' . $vecteur_BD->id. ');" title="'. $vecteur_AD->ad_vector . ' X '. $vecteur_BD->bd_vector .'"> </td>';
    }
    echo '</tr>';
}

echo '</table>';
?></div>


<?php
}
echo Footer::getFooter();?>
