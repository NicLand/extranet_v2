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
$errors =[];
$validator = new Validator($_POST);
  $validator->isText('acheteur',"Veuillez saisir votre nom.");
  $validator->isSelect('dealer',"Veuillez choisir un fournisseur.");
  $validator->isSelect('nomenclature','Veuillez saisir une nomenclature.');
  $validator->isNumeric('quantite', "Veuillez saisr un quantité.");
  $validator->isText('reference', "Veuillez saisir une référence.");
  $validator->isText('designation', "Veuillez saisir une désignation.");
  $validator->isNumeric('prix_u', "Veuillez saisir un Prix Unitaire.");
  if($validator->isValid()){
    foreach($_POST as $index => $valeur) {
       $$index = $valeur;
    }
    if(isset($commun)){$commun = 1;}else{$commun = 0;}
    $equipe = $user->team_id;
    $prix_u = floatval($prix_u);
    $remise = floatval($remise);
    $prix = floatval(($prix_u-(($prix_u * $remise)/100)) * $quantite);
    $date = date("Y-m-d");
    if($newC->commander($acheteur,$equipe,$dealer,$nomenclature,$quantite,$designation,$reference,$offre,$prix_u,$remise,$prix,$date,$commun,$commentaire)){
      Session::getInstance()->setFlash('success',"La commande est enregistrée !");
      App::redirect('commande/commander.php');
      exit();
    }
  }
  else{
    $errors = $validator->getErrors();
  }
}

//===========================================================

$title = 'Extranet MFP';
$titleLink = 'index.php';

$rapidAccess = [];
$menuItem = Commande::getRapidAccess();

echo Header::getHeader($title, $titleLink, $rapidAccess, $menuItem);
if(isset($validator)){echo $validator->afficheErrors($errors);}
?>
    <h1 class="m-3">Commander</h1>
    <form id='formCommande' method="post" action="commander.php" class="row mt-2">
      <div class="col">
        <input type="text" name="acheteur" class="form-control form-control-sm" id="acheteur" value="<?= $user->firstname." ".$user->name;?>">
      </div>
      <div class="col">
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
      <div class="col">
        <input type="text" name="offre" class="form-control form-control-sm" id="offre" placeholder="Offre/Devis" value="<?=$offre_rec;?>">
      </div>
      <div class="col">
        <button type="submit" class="btn btn-warning btn-sm" id="commander">Commander</button>
      </div>
      <div id="commande2" class="row mt-2">
        <div class="col">
          <select name="nomenclature" id="nomenclature" class="form-control form-control-sm" aria-label="Default select example">
            <option value="0">Nomenclature</option>
            <?php
            foreach($nomenclatures as $nomenclature){
              if($nomenclature_rec === $nomenclature->nomenclature){$selected = " selected ";}else{$selected = "";}
              if(isset($nomenclature))
              echo "<option value='$nomenclature->nomenclature' $selected>$nomenclature->nomenclature - $nomenclature->correspondance</option>";
            }
            ?>
          </select>      </div>
        <div class="col-1">
          <input type="number" name="quantite" class="form-control form-control-sm" id="quantity" placeholder="Quantité">
        </div>
        <div class="col">
          <input type="text" name="reference" class="form-control form-control-sm" id="reference" placeholder="Référence" value="<?=$reference_rec ;?>">
        </div>
        <div class="col">
          <input type="text" name="designation" class="form-control form-control-sm" id="designation" placeholder="Désignation" value="<?=$designation_rec ;?>">
        </div>
        <div class="col">
          <input type="number" name="prix_u" step="0.01" class="form-control form-control-sm" id="prixu" placeholder="Prix U">
        </div>
        <div class="col-1">
          <input type="number" name="remise" class="form-control form-control-sm" id="remise" placeholder="Remise %">
        </div>
        <div class="col">
          <div class="form-check form-switch mt-2">
            <input class="form-check-input" name="commun" type="checkbox" id="gridCheck">
            <label class="form-check-label" for="gridCheck">
              Commun
            </label>
          </div>
        </div>
      </div>
      <div class="row mt-1">
        <div class="col">
        <textarea  class="form-control form-control-sm" name="commentaire" placeholder="Commentaire sur la commande"></textarea>
      </div>
    </div>
    </form>

<?php
//===========Affichage commande en cours===========
echo $newC->getList("commande",$user->commande);
 ?>
<?= Footer::getFooter();
