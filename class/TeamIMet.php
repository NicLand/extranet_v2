<?php
namespace Extranet;

class TeamIMet {

  static $rapidAccess = [];
  public $table_investigator;
  public $table_souchier;
  public $table_souchier_souche;
  public $table_souchier_antibio;

  public $table_azote;
  public $table_azote_forme;
  public $table_azote_souche;
  public $table_azote_log;
  private $containers = ['big1','big2'];
  private $rackCapacity = 8;


  public function __construct(){
    $this->table_investigator = App::getTableUsers();
    $this->table_souchier = App::getTableIMetSouchier();
    $this->table_souchier_souche = App::getTableIMetSouchierSouche();
    $this->table_souchier_antibio = App::getTableIMetSouchierAntibio();
    $this->table_azote = App::getTableIMetAzote();
    $this->table_azote_forme = App::getTableIMetAzoteForme();
    $this->table_azote_souche = App::getTableIMetAzoteSouche();
    $this->table_azote_log = App::getTableIMetAzoteLog();
  }

  static function getTable(){
    return App::getTableIMetItems();
  }

  static function getRapidAccess(){
    $table = self::getTable();
    $items = Database::query("SELECT name , link from $table ORDER BY ordre ASC")->fetchall();
    foreach($items as $item){
      $link = App::getRoot().'/imet/'.$item->link;
      self::$rapidAccess[$item->name] = $link;
    }
    return self::$rapidAccess ;
  }

  public function afficheSoucheList(){
    $affiche = self::getSouchierHead();
    $souches = self::getSoucheList();
    foreach($souches as $souche){
      $affiche.= "<tr style='cursor:pointer'>
                    <th class='text-center align-middle'>$souche->numero</th>
                    <td class='text-center align-middle'>$souche->boite</td>
                    <td class='text-center align-middle'>$souche->plasmide</td>
                    <td class='text-center align-middle'>$souche->fragment</td>
                    <td class='text-center align-middle'>".self::getSouchierSouche($souche->souche_id)."</td>
                    <td class='text-center align-middle'>$souche->date_souche</td>
                    <td class='text-center align-middle'>$souche->investigateur</td>
                    <td class='text-center align-middle'>".self::getSouchierAntibio($souche->antibio_id)."</td>
                    <td class='text-center align-middle'>$souche->commentaire</td>
                  </tr>";
    }
    $affiche.= self::getTableClose();
    return $affiche;
  }

  public function afficheSoucheSearchList($res,$num){
    if($num>0){
      $affiche  = '<h5 class="m-3">Nombre de resultats : '.$num.'</h5>';
      $affiche .= self::getSouchierHead();
      foreach($res as $souche){
        $affiche.= "<tr style='cursor:pointer'>
                      <th class='text-center align-middle'>$souche->numero</th>
                      <td class='text-center align-middle'>$souche->boite</td>
                      <td class='text-center align-middle'>$souche->plasmide</td>
                      <td class='text-center align-middle'>$souche->fragment</td>
                      <td class='text-center align-middle'>".self::getSouchierSouche($souche->souche_id)."</td>
                      <td class='text-center align-middle'>$souche->date_souche</td>
                      <td class='text-center align-middle'>$souche->investigateur</td>
                      <td class='text-center align-middle'>".self::getSouchierAntibio($souche->antibio_id)."</td>
                      <td class='text-center align-middle'>$souche->commentaire</td>
                    </tr>";
      }
      $affiche .= self::getTableClose();
    }
    return $affiche;
  }

  private function getSoucheList(){
    return Database::query("SELECT * FROM $this->table_souchier")->fetchAll();
  }

  private function getSouchierAntibio($id){
  if(isset($id) AND $id!=0){
    $antibio = Database::query("SELECT * FROM $this->table_souchier_antibio WHERE id_antibio = $id")->fetch();
    return $antibio->antibio;
  }
  else{
    return "";
  }
  }

  private function getSouchierSouche($id){
    if(isset($id) AND $id!=0){
    $souche =  Database::query("SELECT * FROM $this->table_souchier_souche WHERE id_souche = $id")->fetch();
    return $souche->souche;
  }
  else{
    return "";
  }
  }

  private function getSouchierHead(){
    return '
    <table class="table table-hover table-sm ">
      <thead>
        <tr>
          <th class="text-center" scope="col">Numero</th>
          <th class="text-center" scope="col">Boite</th>
          <th class="text-center" scope="col">Plasmide</th>
          <th class="text-center" scope="col">Insert (bp)</th>
          <th class="text-center" scope="col">Souche</th>
          <th class="text-center" scope="col">Date</th>
          <th class="text-center" scope="col">Investigateur</th>
          <th class="text-center" scope="col">Antibiotique</th>
          <th class="text-center" scope="col">Commentaires</th>
          </tr>
      </thead>
      <tbody>
    ';
  }
  private function getTableClose(){
    return "</tbody></table>";
  }

  public function newSouche($boite,$numero,$plasmide,$insert,$souche,$date,$investigateur,$antibio,$commentaire){
    if(Database::query("INSERT INTO $this->table_souchier (boite,numero,plasmide,fragment,souche_id,date_souche,investigateur,antibio_id,commentaire) VALUES (?,?,?,?,?,?,?,?,?)",[$boite,$numero,$plasmide,$insert,$souche,$date,$investigateur,$antibio,$commentaire])){
      return true;
    }
  }
  public function newS($souche){
    if(Database::query("INSERT INTO $this->table_souchier_souche (souche) VALUES (?)",[$souche])){
    return true;
    }
  }
  public function newA($antibio){
    if(Database::query("INSERT INTO $this->table_souchier_antibio (antibio) VALUES (?)",[$antibio])){
    return true;
    }
  }
//========================================================
//=================== AZOTE ==============================
//========================================================
  public function getAzote(){
    $racks = self::getUniqRack();
    $affiche ="";
    foreach($racks as $cuve => $rack){
      $affiche .= "<h2 class='text-capitalize'>\"$cuve\" Container</h2>";
      $affiche .= "<div class='row row-cols-1 row-cols-md-6'>";
      foreach($rack as $tige){
        $affiche .= "<div class='col mb-4'>";
        $affiche .= "<div class='card'>";
        $affiche .= "<div class='card-header text-center'><h5>Rack $tige->tige</h5></div>";
          $affiche .= '<ul class="list-group list-group-flush">';
            $boxes = self::getBox($cuve, $tige->tige);
            foreach($boxes as $box){
              $free = self::getFreePlaces($cuve,$tige->tige, $box->boite);
              $link = "box.php?c=$cuve&r=$tige->tige&b=$box->boite";
              $affiche .= "<li class='list-group-item text-center list-group-item-action'><a class='stretched-link text-decoration-none' href='$link'>$box->boite </a>($free)</li>";
            }
          $affiche .= '</ul>';
        $affiche .= "</div></div>";
      }
      $affiche .= "</div>";
    }
    return $affiche;
  }

  public function getAzoteSearch($res,$num){
    if($num>0){
      $affiche  = '<h5 class="m-3">Nombre de resultat de la recherche : '.$num.'</h5>';
      $affiche .= '<table class="table table-hover table-sm">
        <thead>
          <tr>
            <th class="text-center" scope="col">Position</th>
            <th class="text-center" scope="col">Forme</th>
            <th class="text-center" scope="col">Souche</th>
            <th class="text-center" scope="col">Modification</th>
            <th class="text-center" scope="col">Date</th>
            <th class="text-center" scope="col">Manipulateur</th>
            <th class="text-center" scope="col">Commentaires</th>
            </tr>
        </thead>
        <tbody>';
      foreach($res as $tube){
        $affiche .= "<tr style='cursor:pointer' onClick=\"document.location='tube.php?id=".$tube->id."'\">
                      <th class='text-center align-middle'>Cuve ".$tube->container." | Tige ".$tube->tige." | Boite ".$tube->boite." | Position ".$tube->place."</th>
                      <td class='text-center align-middle'>".self::getAzoteForme($tube->forme)."</td>
                      <td class='text-center align-middle'>".self::getAzoteSouche($tube->strain)."</td>
                      <td class='text-center align-middle'>".$tube->modification."</td>
                      <td class='text-center align-middle'>".$tube->date1."</td>
                      <td class='text-center align-middle'>".$tube->manipulateur."</td>
                      <td class='text-center align-middle'>".$tube->commentaire."</td>
                    </tr>";
      }
      $affiche .= "</tbody></table>";
      return $affiche;
    }
  }

  private function getUniqRack(){
    foreach($this->containers as $container){
      $uniq[$container] = Database::query("SELECT DISTINCT tige FROM  $this->table_azote WHERE container = '$container' ORDER BY tige ASC")->fetchAll();
    }
    return $uniq;
  }
  private function getBox($cuve, $tige){
      $req = Database::query("SELECT DISTINCT boite FROM $this->table_azote WHERE container = '$cuve' AND tige = $tige ORDER BY boite DESC");
      $boxes = $req->fetchAll();
      return $boxes;
  }
  public function getFreePlaces($cuve, $tige, $box){
    $req = Database::query("SELECT id FROM $this->table_azote WHERE container = '$cuve' AND tige = $tige AND boite = $box AND (`modification` = '' OR `modification` IS NULL) AND (strain ='' OR strain IS NULL)");
    return $req->rowCount();
  }
  private function getAzoteSouche($id){
    if(isset($id) && $id !=''){
    $souche = Database::query("SELECT * FROM $this->table_azote_souche WHERE id_souche = $id")->fetch();
    return $souche->genre." ".$souche->souche_texte;
    }
    else{
      return '';
    }
  }
  private function getAzoteForme($id){
    if(isset($id) && $id !=''){
    $forme = Database::query("SELECT * FROM $this->table_azote_forme WHERE id_forme = $id")->fetch();
    return $forme->forme_text;
    }
    else{
      return '';
    }
  }

  public function getAzoteBox($cuve,$tige,$box){
    $req = Database::query("SELECT * FROM $this->table_azote WHERE container = '$cuve' AND tige = '$tige' AND boite = $box ORDER BY place ASC");
    $boxes = $req->fetchAll();
    //var_dump($boxes);
    $affiche = "<h2 class='text-capitalize m-3'>Cuve $cuve , Rack $tige, Boite $box</h2>";
    $affiche .= '<table class="table table-hover table-sm">
      <thead>
        <tr>
          <th class="text-center" scope="col">Place</th>
          <th class="text-center" scope="col">Forme</th>
          <th class="text-center" scope="col">Souche</th>
          <th class="text-center" scope="col">Modification</th>
          <th class="text-center" scope="col">Date</th>
          <th class="text-center" scope="col">Manipulateur</th>
          <th class="text-center" scope="col">Commentaires</th>
          </tr>
      </thead>
      <tbody>';
    foreach($boxes as $tube){
      $affiche .= "<tr style='cursor:pointer' onClick=\"document.location='tube.php?id=".$tube->id."'\">
                    <th class='text-center align-middle'>".$tube->place."</th>
                    <td class='text-center align-middle'>".self::getAzoteForme($tube->forme)."</td>
                    <td class='text-center align-middle'>".self::getAzoteSouche($tube->strain)."</td>
                    <td class='text-center align-middle'>".$tube->modification."</td>
                    <td class='text-center align-middle'>".$tube->date1."</td>
                    <td class='text-center align-middle'>".$tube->manipulateur."</td>
                    <td class='text-center align-middle'>".$tube->commentaire."</td>
                  </tr>";
    }
    $affiche .= "</tbody></table>";
    return $affiche;
  }

  public function getAzoteTube($id){
    return Database::query("SELECT * FROM $this->table_azote WHERE id = $id")->fetch();
  }

  public function newAzote($id,$souche,$forme,$modification,$date,$manipulateur,$commentaire){
    if(Database::query("UPDATE $this->table_azote SET
      forme = ?,
      strain =?,
      modification =?,
      date1 =?,
      manipulateur =?,
      commentaire =?
      WHERE id = ?"
      ,[$forme,$souche,$modification,$date,$manipulateur,$commentaire,$id])){
        return true;
      }
  }
  public function delAzote($id){
    if(Database::query("UPDATE $this->table_azote SET
    forme=?,
    strain=?,
    modification =?,
    date1 = ?,
    manipulateur =?,
    commentaire = ?
    WHERE id = ?
    ",[NULL,NULL,NULL,NULL,NULL,NULL,$id])){
      return true;
    }
  }
  public function newLog($id,$user){
    $tube = self::getAzoteTube($id);
    $log = "Cuve ".$tube->container." | Tige ".$tube->tige." | Boite ".$tube->boite." | Position ".$tube->place.",
     forme :".self::getAzoteForme($tube->forme).",
     souche :".self::getAzoteSouche($tube->strain).",
     modification: ".$tube->modification."
     du ".$tube->date1."
     manipulateur:".$tube->manipulateur.",
     commentaire:".$tube->commentaire.",
     decongele par :".$user;
     if(Database::query("INSERT INTO $this->table_azote_log (textLog, dateLog, userLog) VALUES (?,?,?)",[$log,date("Y-m-d"),$user])){
       return true;
     }
  }

  public function getAzoteLog(){
    $datas = Database::query("SELECT * FROM $this->table_azote_log ORDER BY dateLog DESC");
    $affiche ='<table class="table table-hover table-sm">
      <thead>
        <tr>
          <th class="text-center" scope="col">Log</th>
          <th class="text-center" scope="col">Date</th>
          <th class="text-center" scope="col">Decongeler par</th>
          </tr>
      </thead>
      <tbody>';
    foreach($datas as $data){
      $affiche .= "<tr style='cursor:pointer'>
                    <th class='text-center align-middle'>".$data->textLog."</th>
                    <td class='text-center align-middle'>".$data->dateLog."</td>
                    <td class='text-center align-middle'>".$data->userLog."</td>
                  </tr>";
    }
    $affiche .= "</tbody></table>";
    return $affiche;
  }
  public function getAzoteLogSearch($res,$num){
    if($num>0){
      $affiche  = '<h5 class="m-3">Nombre de resultat de la recherche : '.$num.'</h5>';
      $affiche .= '<table class="table table-hover table-sm">
      <thead>
        <tr>
          <th class="text-center" scope="col">Log</th>
          <th class="text-center" scope="col">Date</th>
          <th class="text-center" scope="col">Decongeler par</th>
          </tr>
      </thead>
      <tbody>';
      foreach($res as $data){
        $affiche .= "<tr style='cursor:pointer'>
                      <th class='text-center align-middle'>".$data->textLog."</th>
                      <td class='text-center align-middle'>".$data->dateLog."</td>
                      <td class='text-center align-middle'>".$data->userLog."</td>
                    </tr>";
      }
      $affiche .= "</tbody></table>";
      return $affiche;
    }
  }
}
