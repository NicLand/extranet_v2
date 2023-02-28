<?php
//===========================================================
//Charge automatiquemebnt les Class utilisées dans les pages
// Dans le bon __NAMESPACE__
namespace Extranet;

require '../class/Autoloader.php';
Autoloader::register();

$auth = App::getAuth();
$user = $auth->user();

if(!$user){
  App::redirect('login.php');
  exit();
}
$access = new Access("commande", $user);
if(!$access->accessCommande()){
  Session::setFlash('danger', "Vous n'êtes pas autorisé à accéder à cette page.");
  App::redirect('index.php');
  exit();
}
//========== Préparation du formulaire de commande ===============
  $newC = new Commande;
  $dealers = $newC->setListDealers();
  $nomenclatures = $newC->getListNomenclatures();
//================================================================
if(isset($_GET['id']) && $_GET['id'] !=""){
  $id_recommander = $_GET['id'];
  $recommander = new Commande;
  $datas = $recommander->getCommande($id_recommander);
    $nomenclature_rec = $datas->nomenclature;
    $reference_rec = $datas->reference;
    $designation_rec = $datas->designation;
    $fournisseur_rec = $datas->fournisseur;
    $offre_rec = $datas->offre;
}
else{
  $nomenclature_rec = "";
  $reference_rec = "";
  $designation_rec = "";
  $fournisseur_rec = "";
  $offre_rec = "";
}
//========== Validation du formulaire ============================
if(!empty($_POST)){

$lignes = $newC->splitLigne($_POST);
  //foreach($lignes as $ligne){
    foreach($_POST as $field=>$line){
      echo $field;
      echo "</br>";
    }
    echo $_POST['reference'][$ligne];
//  }
}

//===========================================================

$title = 'Extranet MFP';
$titleLink = 'index.php';

$rapidAccess = Commande::getRapidAccess();

$menuItem = [];
echo Header::getHeader($title, $titleLink, $rapidAccess, $menuItem);
if(isset($validator)){
  echo $validator->afficheErrors($errors);
}
?>
    <h1 class="m-3">Commander</h1>
    <div class="row mt-2">
    <form id='formCommande' method="post" action="#">
      <div class='row'>
      <div class="col-3">
        <input type="text" name="acheteur" class="form-control form-control-sm" id="acheteur" value="<?= $user->firstname." ".$user->name;?>" readonly>
      </div>
      <div class="col-4">
        <select name="dealer" id="dealer" class="form-control form-control-sm" aria-label="Default select example" onChange="detailOffre();">
          <option value="0">Fournisseurs</option>
          <?php
          foreach($dealers as $dealer){
            if($fournisseur_rec === $dealer->fournisseur){$selected = " selected ";}else{$selected = "";}
            if(!empty($dealer->revendeur)){
              $revend = "(Revends : ".$dealer->revendeur.")";
            }
            else{
              $revend = "";
            }
            echo "<option id='$dealer->offre' value='$dealer->fournisseur' $selected>$dealer->fournisseur $revend</option>";
          }
          ?>
        </select>
      </div>
      <div class="col-3">
        <input type="text" name="offre" class="form-control form-control-sm" id="offre" placeholder="Offre/Devis" value="<?=$offre_rec;?>">
      </div>
      <div class="col-1">
        <button type="submit" class="btn btn-warning btn-sm" id="commander">Commander</button>
      </div>
      <div class="col-1">
      <button type="button" class="btn btn-success btn-sm" onClick="ajoutLigneCommande();">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
          <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2Z"/>
        </svg>
    </button>
    </div>
  </div>
      <div id="commande2" class="row mt-1">
        <div class="col">
          <select name="nomenclature[]" id="nomenclature" class="form-control form-control-sm" aria-label="Default select example">
            <option value="0">Nomenclature</option>
            <?php
            foreach($nomenclatures as $nomenclature){
              if($nomenclature_rec === $nomenclature->nomenclature){$selected = " selected ";}else{$selected = "";}
              if(isset($nomenclature))
              echo "<option value='$nomenclature->nomenclature' $selected>$nomenclature->nomenclature - $nomenclature->correspondance</option>";
            }
            ?>
          </select>
        </div>
        <div class="col-1">
          <input type="number" name="quantite[]" class="form-control form-control-sm" id="quantity" placeholder="Quantité">
        </div>
        <div class="col">
          <input type="text" name="reference[]" class="form-control form-control-sm" id="reference" placeholder="Référence" value="<?=$reference_rec ;?>">
        </div>
        <div class="col">
          <input type="text" name="designation[]" class="form-control form-control-sm" id="designation" placeholder="Désignation" value="<?=$designation_rec ;?>">
        </div>
        <div class="col">
          <input type="number" name="prix_u[]" step="0.01" class="form-control form-control-sm" id="prixu" placeholder="Prix U">
        </div>
        <div class="col-1">
          <input type="number" name="remise[]" class="form-control form-control-sm" id="remise" placeholder="Remise %">
        </div>
        <div class="col">
          <select name="commun[]" id="commun" class="form-control form-control-sm" aria-label="Default select example">
              <option value="0">Destination</option>
              <option value="commun">Commun</option>
              <option value="equipe">Équipe</option>
          </select>
        </div>
      </div>
      <div id="formToInsert">
      </div>
      <div id="commentaire" class="row mt-1">
        <div class="col-12">
        <textarea  class="form-control form-control-sm" name="commentaire" placeholder="Commentaire sur la commande"></textarea>
        </div>
    </div>
    </form>
  </div>

<?php
//===========Affichage commande en cours===========
echo $newC->getList("encours");
 ?>
<?= Footer::getFooter();
