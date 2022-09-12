<?php
namespace Extranet;

class Commande{

  private $table_commun;
  private $table_commun_spacvir;
  private $table_commun_reger;
  private $table_commun_paramyc;
  private $table_commande;
  private $table_fournisseur;
  private $link_offre = "document/offre/";
  private $table_nomenclature;
  private $table_teams;
  private $teamColor = [];
  static $rapidAccess = [
    'Commander' => 'commander.php',
    'En cours'=>'encours.php',
    'En Livraison' => 'enlivraison.php',
    'Historique' => 'historique.php',
    'Fournisseurs' => 'fournisseurs.php',
    'Commun ex-REGER' => 'commun.php?t=reger',
    'Commun SpacVir' => 'commun.php?t=spacvir',
    'Commun ParaMyc' => 'commun.php?t=paramyc'
  ];

  public function __construct(){
    $this->table_commande = App::getTableCommande();
    $this->table_fournisseur = App::getTableFournisseur();
    $this->table_nomenclature = App::getTableNomenclature();
    $this->table_teams = App::getTableTeams();
    $this->table_commun_spacvir = App::getTableCommunSpacvir();
    $this->table_commun_reger = App::getTableCommunReger();
    $this->table_commun_paramyc = App::getTableCommunParamyc();
  }

  static function getRapidAccess(){
    return self::$rapidAccess;
  }

//=================== DEBUT FOURNISSEURS =================================================
  public function setDealerData($datas){
    $affiche ="";
    foreach($datas as $data){
      $affiche .= "<td><h6>$data->fournisseur</h6></td>";
      $affiche .= "<td><a href='$data->website'>$data->website</a></td>";
      $affiche .= "<td>$data->offre</td>";
      $affiche .= "<td>$data->frais</td>";
      $affiche .= "<td>$data->revendeur</td>";
      $affiche .= "<td>$data->annee</td>";
      $affiche .= "<td>".self::getLink($data->offre_sheet)."</td>";
      $affiche .= "<td><a  href='modif_dealer.php?id=$data->id' class='btn btn-info table_commande'>Modifier</a></td>";
      $affiche .= "</tr>";
    }
    return $affiche;
  }
  private function getLink($link){
    if(!empty($link)){
      return "<a href='".$this->link_offre.$link."' download='".$link."'><img style='width:40px' >$link</a>";
    }
  }
  public function getSearchList($res,$num){
    if($num>0){
      $affiche  = '<h5 class="m-3">Number of results : '.$num.'</h5>';
      $affiche .= self::getTableDealerHeader();
      $affiche .= self::setDealerData($res);
    }
    $affiche.= self::getTableFooter();
    return $affiche;
  }
  public function getDealer($id){
    return Database::query("SELECT * FROM $this->table_fournisseur WHERE id = $id")->fetch();
  }
  public function setListDealers(){
    return Database::query("SELECT * FROM $this->table_fournisseur ORDER BY fournisseur ASC")->fetchAll();
  }
  public function getListNomenclatures(){
    return Database::query("SELECT * FROM $this->table_nomenclature")->fetchAll();
  }
//=========== FORMULAIRE MODIF =====================
  public function selectDealers($fournisseur){
    $affiche = "<div class='form-group row m-1'><label class='col-sm-3 col-form-label' for='fournisseur'>Fournisseur :</label>";
    $affiche .= "<div class='col-sm-9'><select name='dealer' id='dealer' class='form-control' onChange='detailOffre();'>";
    $affiche .= "<option value='0'> --- </option>";
    $dealers = $this->setListDealers();
      foreach($dealers as $dealer){
        if(!empty($dealer->revendeur)){
          $revend = "(Revends : ".$dealer->revendeur.")";
        }
        else{
          $revend = "";
        }
        if($dealer->fournisseur == $fournisseur){$selected = " selected ";}else{$selected="";}
        $affiche .= "<option $selected id='$dealer->offre' value='$dealer->fournisseur'>$dealer->fournisseur $revend</option>";
      }
    $affiche .= "</select></div></div>";
    return $affiche;
  }

  public function selectDearlerCommun($datas,$default){
    $affiche = "<div class='form-group row m-1'><label class='col-sm-3 col-form-label' for='fournisseur'>Fournisseur :</label>";
    $affiche .= "<div class='col-sm-9'><select name='dealer' id='dealer' class='form-control' onChange='detailOffre();'>";
    $affiche .= "<option value='0'> --- </option>";
    foreach($datas as $data){
      if($data->fournisseur == $default){$selected = " selected ";}else{$selected="";}
      $affiche .= "<option $selected value='$data->fournisseur'>$data->fournisseur</option>";
    }
    $affiche .= "</select></div></div>";
    return $affiche;
  }

  public function setOffre($val){
    return "<div class='form-group row m-1'>
              <label class='col-sm-3 col-form-label' for='nomenclature'>Offre/Devis :</label>
              <div class='col-sm-9'>
                <input value='$val' type='text' name='offre' class='form-control' id='offre' placeholder='Offre/Devis'>
              </div>
            </div>";
  }

  public function selectNomenclatures($nomenclature){
    $affiche = "<div class='form-group row m-1'><label class='col-sm-3 col-form-label' for='nomenclature'>NACRES :</label>";
    $affiche .= "<div class='col-sm-9'><select name='nomenclature' class='form-control'>";
    $affiche .= "<option value='0'> --- </option>";
    $nacres = $this->getListNomenclatures();
      foreach($nacres as $nacre){
        if($nacre->nomenclature == $nomenclature){$selected = " selected ";}else{$selected="";}
        $affiche .= "<option $selected value='$nacre->nomenclature'>$nacre->nomenclature - $nacre->correspondance</option>";
      }
    $affiche .= "</select></div></div>";
    return $affiche;
  }
  public function checkCommande($name,$label,$val){
    if($val == 1){$checked = " checked ";}else{$checked = "";}
    return "<div class='form-group row m-1'>
            <label class='col-form-label form-check-label col-sm-3' for='gridCheck'>$label</label>
          <div class='col-sm-9'>
            <input class='form-check-input mt-2' name='$name' type='checkbox' id='gridCheck' $checked>
          </div></div>";
  }
//====================================================
  public function getTableDealerHeader(){
    return "
    <table class='table table-hover table-sm table-commande'>
      <thead>
        <tr>
          <th scope='col'>Fournisseur</th>
          <th scope='col'>Site internet</th>
          <th scope='col'>Offre</th>
          <th scope='col'>Frais</th>
          <th scope='col'>Revendeur de</th>
          <th scope='col'>Année de l'offre</th>
          <th scope='col'>Offre détaillée</th>
          <th scope='col'>Modifier</th>
          </tr>
      </thead>
      <tbody>
    ";
  }

  public function getListDealers(){
    $affiche = self::getTableDealerHeader();
      $datas = self::setListDealers();
    $affiche .= self::setDealerData($datas);
    $affiche .= self::getTableFooter();
    return $affiche;
  }
  public function newDealer($fournisseur,$representant,$portable,$email_representant,$telephone,$fax,$email_commande,$website,$offre,$frais,$revendeur,$annee,$file){
    if(Database::query("INSERT INTO $this->table_fournisseur (fournisseur,representant,portable,email_representant,telephone,fax,email_commande,website,offre,frais,revendeur,annee,offre_sheet) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)",
    [
      $fournisseur,$representant,$portable,$email_representant,$telephone,$fax,$email_commande,$website,$offre,$frais,$revendeur,$annee,$file
    ])){
      return true;
    }
  }
  public function upDealer($id,$fournisseur,$representant,$portable,$email_representant,$telephone,$fax,$email_commande,$website,$offre,$frais,$revendeur,$annee,$file){
    if(Database::query("UPDATE $this->table_fournisseur SET
      fournisseur = ?,
      representant = ?,
      portable = ?,
      email_representant = ?,
      telephone = ?,
      fax = ?,
      email_commande = ?,
      website = ?,
      offre = ?,
      frais = ?,
      revendeur = ?,
      annee = ?,
      offre_sheet = ?
      WHERE id=?
    ",
    [
      $fournisseur,$representant,$portable,$email_representant,$telephone,$fax,$email_commande,$website,$offre,$frais,$revendeur,$annee,$file,$id
    ])){
      return true;
    }
  }
  public function delDealer($id){
    if(Database::query("DELETE FROM $this->table_fournisseur WHERE id = $id")){
      return true;
    }
  }
  //=================== FIN FOURNISSEURS =================================================

  //=================== DEBUT ACHATS =================================================
  private function setList($type){
    if ($type === "encours" || $type === "commande"){$where =  "WHERE `valide` = 0 AND `livre` = 0 ";}
    elseif ($type === "enlivraison"){$where =  "WHERE `valide` = 1 AND `livre` = 0 ";}
    elseif($type === 'historique'){$where =  "WHERE `valide` = 1 AND `livre` = 1 ";}
    else{$where="";}
    $sql = "SELECT * FROM $this->table_commande $where ORDER BY fournisseur ASC, date_commande DESC";
    return Database::query($sql)->fetchAll();
  }
  private function supCommandeList($type,$data,$search,$opt){
    if($type === "encours"){
      return "
        <form action='".$_SERVER["PHP_SELF"]."' method='post' name='valide' id='valide'>
          <td><input class='form-control form-control-sm' type='text' name='bon_commande' placeholder='Bon de commande'></td>
          <input type='hidden' value='$search' name='search'><input type='hidden' value='$opt' name='search_option'>
          <td><input class='btn btn-primary btn-sm table-commande' value='OK' type='submit' name='validation'></td>
          <input type='hidden' name='ligne' value='$data->id'>
        </form>
      ";
    }
    elseif($type === "enlivraison"){
      return "
        <td>$data->bon_commande</td>
        <form action='".$_SERVER["PHP_SELF"]."' method='post' name='livre' id='livre'>
          <td><input class='form-control form-control-sm' type='text' name='bon_livraison' placeholder='Bon de Livraison'></td>
          <td><input class='form-control form-control-sm' type='text' name ='date_livraison' placeholder='date'></td>
          <td><input class='form-control form-control-sm' type='text' name ='comment_livraison' placeholder='comment_livraison'></td>
          <input type='hidden' value='$search' name='search'><input type='hidden' value='$opt' name='search_option'>
          <td><input class='btn btn-primary btn-sm table-commande' value='OK' type='submit' name='livraison'></td>
          <input type='hidden' name='ligne' value='$data->id'>
        </form>
      ";
    }
    else{
      return "";
    }
  }
  private function setCommandeList($datas,$access,$type,$search,$opt){
    $affiche = '';
    foreach ($datas as $data){
      $color = self::setTeamColor($data->team);
      $affiche .= "<tr $color>";
      $affiche .= "<td>$data->user</td>";
      $affiche .= "<td>$data->fournisseur</td>";
      $affiche .= "<td>$data->nomenclature</td>";
      $affiche .= "<td>$data->quantite</td>";
      $affiche .= "<td>$data->designation</td>";
      $affiche .= "<td>$data->reference</td>";
      $affiche .= "<td>$data->offre</td>";
      $affiche .= "<td>$data->prix_unitaire</td>";
      $affiche .= "<td>$data->remise</td>";
      $affiche .= "<td>$data->prix</td>";
      $affiche .= "<td>$data->date_commande</td>";
      if($type == "enlivraison"){
        $affiche .= "";
      }
      else{
      if(isset($data->commun) && $data->commun == 1){
        $affiche .= "<td>Commun</td>";
      }
      else{
        $affiche .= "<td>Spécifique Equipe</td>";
      }
    }
      $affiche .= "<td>$data->comment</td>";
      $affiche .= "<td><a href='modif.php?id=$data->id' class='btn btn-info btn-sm table-commande'>Modifier</a></td>";
      if(isset($access) && $access == "sa"){
      $affiche .= $this->supCommandeList($type,$data,$search,$opt);
      }
      $affiche .= "</tr>";
    }
    return $affiche;
  }
  private function setCommandeListHisto($datas){
    $affiche = '';
    foreach ($datas as $data){
      $color = self::setTeamColor($data->team);
      $affiche .= "<tr $color>";
      $affiche .= "<td>$data->user</td>";
      $affiche .= "<td>$data->fournisseur</td>";
      $affiche .= "<td>$data->nomenclature</td>";
      $affiche .= "<td>$data->quantite</td>";
      $affiche .= "<td>$data->designation</td>";
      $affiche .= "<td>$data->reference</td>";
      $affiche .= "<td>$data->offre</td>";
      $affiche .= "<td>$data->prix_unitaire</td>";
      $affiche .= "<td>$data->remise</td>";
      $affiche .= "<td>$data->prix</td>";
      $affiche .= "<td>$data->date_commande</td>";
      if(isset($data->commun) && $data->commun == 1){
        $affiche .= "<td>Commun</td>";
      }
      else{
        $affiche .= "<td>Spécifique Equipe</td>";
      }
      $affiche .= "<td>$data->comment</td>";
      $affiche .= "<td>$data->bon_commande</td>";
      $affiche .= "<td>$data->date_valide</td>";
      $affiche .= "<td>$data->bon_livraison</td>";
      $affiche .= "<td>$data->date_livre</td>";
      $affiche .= "<td>$data->reception</td>";
      $affiche .= "<td><a href='commander.php?id=$data->id' class='btn btn-info table-commande'>Recommander</a></td>";
      $affiche .= "<td><a href='modif_histo.php?id=$data->id' class='btn btn-info table-commande'>Modifier</a></td>";
      $affiche .= "</tr>";
    }
    return $affiche;
  }
  public function sortSearchCommandeList($res,$type){
    $sorted =[];
    if ($type === "encours"){
      foreach ($res as $data){
        if ($data->valide == 0){
          array_push($sorted, $data);
        }
      }
    }
    elseif ($type === "enlivraison"){
      foreach ($res as $data){
        if ($data->valide == 1 AND $data->livre == 0){
          array_push($sorted, $data);
        }
      }
    }
    elseif($type === 'historique'){
      foreach ($res as $data){
        if ($data->valide == 1 AND $data->livre == 1){
          array_push($sorted, $data);
        }
      }
    }
    return $sorted;
  }

  public function getSearchCommandeList($res,$num,$type,$access,$search,$opt){
    $sortedRes = self::sortSearchCommandeList($res,$type);
    $sortedResNum = count($sortedRes);
    if($sortedResNum>0){
      $affiche  = '<h5 class="m-3">Number of results : '.$sortedResNum.'</h5>';
      $affiche .= self::getTableHeader($type,$access);
      $affiche .= self::setCommandeList($sortedRes,$access,$type,$search,$opt);
      $affiche.= self::getTableFooter();
      return $affiche;
    }
    else{
      return false;
    }

  }
  public function getSearchCommandeListHisto($res,$num,$type,$access){
    $sortedRes = self::sortSearchCommandeList($res,$type);
    $sortedResNum = count($sortedRes);
    if($sortedResNum>0){
      $affiche  = '<h5 class="m-3">Number of results : '.$sortedResNum.'</h5>';
      $affiche .= self::getTableHeaderHisto($type,$access);
      $affiche .= self::setCommandeListHisto($sortedRes,$access,$type);
    }
    $affiche.= self::getTableFooter();
    return $affiche;
  }

  public function getList($type,$access,$search,$opt){
    $datas = self::setList($type);
    $affiche = self::getTableHeader($type,$access);
    $affiche .= self::setCommandeList($datas,$access,$type,$search,$opt);
    $affiche .= self::getTableFooter();
    return $affiche;
  }
  private function supTableHeader($type){
    if($type == "encours"){return "<th scope='col'>Bon de Commande</th><th scope='col'></th>";}
    elseif($type == "enlivraison"){return "<th scope='col'>Bon de commande</th><th scope='col'>Bon de Livraison</th><th scope='col'>Livré le</th><th scope='col'>Commentaire livraison</th>";}
    else{return "";}
  }
  private function getTableHeader($type,$access){
    $affiche = "
      <table class='table table-hover table-sm table-commande'>
        <thead>
          <tr>
            <th scope='col'>Nom</th>
            <th scope='col'>Fournisseur</th>
            <th scope='col'>Nomenclature</th>
            <th scope='col'>Qté</th>
            <th scope='col'>Désignation</th>
            <th scope='col'>Réf</th>
            <th scope='col'>Offre/devis</th>
            <th scope='col'>Prix U</th>
            <th scope='col'>Remise</th>
            <th scope='col'>Prix</th>
            <th scope='col'>Date</th>";
      if($type == "enlivraison"){
        $affiche .= "";
      }
      else{
        $affiche .= "<th scope='col'>Type</th>";
      }
    $affiche .="<th scope='col'>Comment.</th>
            <th scope='col'>Modif</th>";
            if(isset($access) && $access == "sa"){
              $affiche .= $this->supTableHeader($type);
            }else{}
    $affiche .= "</tr>
        </thead>
        <tbody>";
    return $affiche;
  }
  private function getTableHeaderHisto(){
    return "
      <table class='table table-hover table-sm table-commande'>
        <thead>
          <tr>
            <th scope='col'>Nom</th>
            <th scope='col'>Fournisseur</th>
            <th scope='col'>NACRES</th>
            <th scope='col'>Qté</th>
            <th scope='col'>Désignation</th>
            <th scope='col'>Réf</th>
            <th scope='col'>Offre/devis</th>
            <th scope='col'>Prix U</th>
            <th scope='col'>Remise</th>
            <th scope='col'>Prix</th>
            <th scope='col'>Date</th>
            <th scope='col'>Type</th>
            <th scope='col'>Comment.</th>
            <th scope='col'>N° BC</th>
            <th scope='col'>Validé le</th>
            <th scope='col'>N° BL</th>
            <th scope='col'>Livré le</th>
            <th scope='col'>Récep par</th>
            <th scope='col'>Recommander</th>
            <th scope='col'>Modifier</th>
            </tr>
        </thead>
        <tbody>";
  }
  private function getTableFooter(){
    return "</tbody></table></div>";
  }

  public function setTeamColor($team){
    if(!empty($team)){
      if($color = Database::query("SELECT commande_color FROM $this->table_teams WHERE id = $team")->fetch()){
        return "class='table-$color->commande_color'";
      }
      else{
        return "";
      }
    }
    return "";
  }
  //=================== FIN ACHATS =================================================
  //=================== DEBUT HISTORIQUE =================================================

  public function getLastYear(){
    return Database::query("SELECT DISTINCT year(`date_commande`) as y FROM $this->table_commande WHERE valide=1 AND livre=1 ORDER BY year(`date_commande`) DESC LIMIT 1")->fetch();
  }
  public function getLastMonth(){
    $y= self::getLastYear();
    return Database::query("SELECT DISTINCT month(`date_commande`) as m FROM $this->table_commande WHERE valide=1 and livre=1 and  year(`date_commande`) = $y->y ORDER BY month(`date_commande`) DESC LIMIT 1")->fetch();
  }
  public function getYears(){
    return Database::query("SELECT DISTINCT year(`date_commande`) as y FROM $this->table_commande WHERE valide=1 AND livre=1 ORDER BY year(`date_commande`) ASC")->fetchAll();
  }
  public function getMonths($y){
    return Database::query("SELECT DISTINCT month(`date_commande`) as m FROM $this->table_commande WHERE valide=1 and livre=1 and year(`date_commande`) = $y ORDER BY month(`date_commande`) ASC")->fetchAll();
  }
  public function setHistorique($y,$m){
    return Database::query("SELECT * FROM $this->table_commande WHERE valide=1 AND livre=1 AND year(`date_commande`)=$y AND month(`date_commande`)=$m ORDER BY `date_commande` ASC")->fetchAll();
  }
  public function afficheHistorique($y,$m){
    $affiche ='';
    $years = self::getYears();
    $affiche .= "<nav aria-label='annee'>";
    $affiche .= "<ul class='pagination pagination-sm'>";
    foreach ($years as $year) {
      if($year->y == $y){
        $affiche .= "<li class='page-item active'>
                      <a class='page-link' aria-current='page' href=historique.php?y=$year->y>
                        ".$year->y."
                          <span class='sr-only'>
                        </span>
                      </a>
                    </li>";
      }
      else{
        $affiche .= "<li class='page-item'><a class='page-link' href=historique.php?y=$year->y>".$year->y."</a></li>";
      }
    }
    $affiche .= "</ul></nav>";
    $affiche .= "<br/>";
    $months = self::getMonths($y);
    $affiche .= "<nav aria-label='mois'>";
    $affiche .= "<ul class='pagination pagination-sm'>";
    foreach($months as $month){
      if($month->m == $m){
        $affiche .= "<li class='page-item active'>
                      <a class='page-link' aria-current='page' href=historique.php?y=$y&m=$month->m>".Str::monthLetters($month->m)."
                          <span class='sr-only'>
                        </span>
                      </a>
                    </li>";
      }
      else{
        $affiche .= "<li class='page-item'><a class='page-link' href=historique.php?y=$y&m=$month->m>".Str::monthLetters($month->m)."</a></li>";
      }
    }
    $affiche .= "</ul></nav>";
    $affiche .= self::getTableHeaderHisto();
    $datas = self::setHistorique($y,$m);
    //var_dump($datas);
    $affiche .= self::setCommandeListHisto($datas);
    $affiche .= self::getTableFooter();

    return $affiche;
  }
  //=================== FIN HISTORIQUE =================================================

  //=================== DEBUT COMMANDER ================================================
  public function commander($acheteur,$team,$dealer,$nomenclature,$quantite,$designation,$reference,$offre,$prix_u,$remise,$prix,$date,$commun,$commentaire){
    if(Database::query("INSERT INTO $this->table_commande (user, team, fournisseur, nomenclature, quantite, designation, reference, offre, prix_unitaire, remise, prix, date_commande, commun, comment) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)",
    [
      $acheteur,$team, $dealer,$nomenclature,$quantite,$designation,$reference,$offre,$prix_u,$remise,$prix,$date,$commun,$commentaire
    ])){
      return true;
    }
  }
  public function upCommande($id,$dealer,$nomenclature,$quantite,$designation,$reference,$offre,$prix_u,$remise,$prix,$date,$commun,$commentaire){
    if(Database::query("UPDATE $this->table_commande SET
      fournisseur = ?,
      nomenclature = ?,
      quantite = ?,
      designation = ?,
      reference = ?,
      offre = ?,
      prix_unitaire = ?,
      remise = ?,
      prix = ?,
      date_commande = ?,
      commun = ?,
      comment = ?
      WHERE id =?",
      [$dealer,$nomenclature,$quantite,$designation,$reference,$offre,$prix_u,$remise,$prix,$date,$commun,$commentaire,$id])){
        return true;
      }
  }
  public function upCommandeHisto($id,$dealer,$offre,$nomenclature,$quantite,$designation,$reference,$prix_u,$remise,$prix,$commun,$comment,$valide,$bon_commande,$livre,$bon_livraison,$reception,$comment_livraison){
    if(Database::query("UPDATE $this->table_commande SET
      fournisseur=?,
      offre=?,
      nomenclature=?,
      quantite=?,
      designation=?,
      reference=?,
      prix_unitaire=?,
      remise=?,
      prix=?,
      commun=?,
      comment=?,
      valide=?,
      bon_commande=?,
      livre=?,
      bon_livraison=?,
      reception=?,
      comment_livraison=?
      WHERE id=?",
      [
        $dealer,$offre,$nomenclature,$quantite,$designation,$reference,$prix_u,$remise,$prix,$commun,$comment,$valide,$bon_commande,$livre,$bon_livraison,$reception,$comment_livraison,$id
      ])){
        return true;
      }
  }
  public function validCommande($id,$bon_commande,$date_valide,$valide){
    if(Database::query("UPDATE $this->table_commande SET
    valide = ?,
    bon_commande =?,
    date_valide = ?
    WHERE id = ?",
    [
      $valide,$bon_commande,$date_valide,$id
    ])){
      return true;
    }
  }
  public function livreCommande($id,$bon_livraison,$date_livre,$livre,$comment_livraison){
    if(Database::query("UPDATE $this->table_commande SET
    livre = ?,
    bon_livraison =?,
    date_livre = ?,
    comment_livraison = ?
    WHERE id = ?",
    [
      $livre,$bon_livraison,$date_livre,$comment_livraison,$id
    ])){
      return true;
    }
  }

  public function delCommande($id){
    if(Database::query("DELETE FROM $this->table_commande WHERE id = $id")){
      return true;
    }
  }

  public function getCommande($id){
    return Database::query("SELECT * FROM $this->table_commande WHERE id = $id")->fetch();
  }
  private function countLigne($lignes){
    $keys =[];
    foreach($lignes as $key=>$ligne){
      if(!empty($ligne)){
        $keys[]=$key;
      }
    }
    return $keys;
  }
  public function splitLigne($datas){
    return $this->countLigne($datas['reference']);
  }


  //=================== FIN COMMANDER =================================================

  //=================== DEBUT COMMANDER EN COMMUN======================================
  private function setTableCommun($team){
    if($team == "spacvir"){$this->table_commun = $this->table_commun_spacvir;}
    elseif($team == "reger"){$this->table_commun = $this->table_commun_reger;}
    elseif($team == 'paramyc'){$this->table_commun = $this->table_commun_paramyc;}
    return $this->table_commun;
  }
  public function distinctDealer($team){
    $tab = $this->setTableCommun($team);
    return Database::query("SELECT DISTINCT fournisseur FROM $tab")->fetchAll();
  }

  private function getHeaderCommun(){
    return "
    <table class='table table-hover table-sm table-commande'>
      <thead>
        <tr>
          <th scope='col'>Désignation</th>
          <th scope='col'>Réf</th>
          <th scope='col'>GAUSS</th>
          <th scope='col'>Nomenclature</th>
          <th scope='col'>Cond</th>
          <th scope='col'>Prix/cond</th>
          <th scope='col'>Quantité</th>
          <th scope='col'>Total</th>
          <th scope='col'>Total HT</th>
          <th scope='col'>Modification</th>
        </tr>
      </thead>
    <tbody>
    ";
  }
  private function getFooterCommun(){
    return "</tbody></table>";
  }
  public function getSingleCommun($team,$id){
    $tab = $this->setTableCommun($team);
    return Database::query("SELECT * FROM $tab WHERE id = $id")->fetch();
  }
  public function upSingleCommun($t,$id,$dealer,$nacres,$designation,$reference,$prix_u,$gauss,$cond){
    $tab = $this->setTableCommun($team);
    if(Database::query("UPDATE $tab SET
      fournisseur = ?,
      designation = ?,
      reference = ?,
      gauss = ?,
      conditionnement = ?,
      prix_u = ?,
      nomenclature = ?
      WHERE id = ?",
      [
        $dealer,$designation,$reference,$gauss,$cond,$prix_u,$nacres,$id
      ])){
        return true;
      }
  }
  public function delSingleCommun($t,$id){
    $tab = $this->setTableCommun($team);
      if(Database::query("DELETE FROM $tab WHERE id = $id")){
        return true;
      }
  }

  public function getCommandeCommun($team,$dealer){
    $tab = $this->setTableCommun($team);
    $datas = Database::query("SELECT * FROM $tab WHERE fournisseur = '$dealer'")->fetchAll();

    $affiche = "<h1 class='mt-3'>$dealer</h1>";
    $affiche .= $this->getHeaderCommun();
    $total =0;
    $affiche .="<form method='post' action='#'>";
    foreach($datas as $data){
      $affiche .= "<tr>";
      $affiche .= "<td>$data->designation</td>";
      $affiche .= "<td>$data->reference</td>";
      $affiche .= "<td>$data->gauss</td>";
      $affiche .= "<td>$data->nomenclature</td>";
      $affiche .= "<td>$data->conditionnement</td>";
      $affiche .= "<td>$data->prix_u €</td>";
      $affiche .= "<td>
                      <input class='form-control-sm form-control' type='number' name='quantite[$data->id]' value=$data->quantite>
                  </td>";
      $affiche .= "<td>$data->quantite</td>";
      $affiche .= "<td>".floatval($data->prix_u) * intval($data->quantite)." €</td>";
      $total += floatval($data->prix_u) * floatval($data->quantite);
      $affiche .= "<td><a class='btn btn-primary btn-sm' href='commun_modif.php?t=$team&id=$data->id'>Modifier</a>";
      $affiche .= "</tr>";
    }
      $affiche .= "<tr>
                    <td colspan='7'></td>
                    <td>Total</td>
                    <td>$total €</td>
                    <td></td>
                  </tr>
                  <tr>
                    <td colspan='6'></td>
                    <td><input type='submit' class='btn btn-success btn-sm' name='validation' value='Valider'></td>
                    <td><input type='submit' class='btn btn-info btn-sm' name='final_validation' value='Validation finale'></td>
                    <td><input type='submit' class='btn btn-danger btn-sm' name='effacer' value='Remise à zéro'></td>
                    <td></td>
                  </tr>";
    $affiche .= "</form>";
    $affiche .= $this->getFooterCommun();
    return $affiche;
  }
  public function newSingleCommun($t,$dealer,$nomenclature,$designation,$reference,$prix_u,$gauss,$conditionnement){
    $tab = $this->setTableCommun($t);
    if(Database::query("INSERT INTO $tab (fournisseur,designation,reference,gauss,conditionnement,prix_u,nomenclature) VALUES (?,?,?,?,?,?,?)",
    [
      $dealer,$designation,$reference,$gauss,$conditionnement,$prix_u,$nomenclature
    ])){
      return true;
    }
  }
  public function upCommandeCommun($team,$id,$q){
    $tab = $this->setTableCommun($team);
    if(Database::query("UPDATE $tab SET quantite = ? WHERE id = ?", [$q,$id])){
      return true;
    }
  }
  public function razCommun($team,$dealer){
    $tab = $this->setTableCommun($team);
    if(Database::query("UPDATE $tab SET quantite = ? WHERE fournisseur = ?",[0,$dealer])){
      return true;
    }
  }
}
