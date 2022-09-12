<?php
namespace Extranet;

class TeamSpacvirVirus {

  private $table;
  private $imgFolder = "virus_prep/";

  public function __construct(){
    $this->table = App::getTableSpacvirVirus();
  }

  public function getVirus($id=false){
    if($id){
      return Database::query("SELECT * FROM $this->table WHERE id = $id")->fetch();
    }
    else{
      return Database::query("SELECT * FROM $this->table ORDER BY purif_date DESC")->fetchAll();
    }
  }
  public function afficheInvestigator($id){
    if(isset($id)){
      $invest = Database::query("SELECT name, firstname FROM mfp_extranet_users WHERE id = '$id'")->fetch();
      if($invest){
      $affiche = $invest->firstname." ".$invest->name;
    }
    else{
      $affiche ="";
    }
    }
    return $affiche;
  }

    public function AfficheAllVirus(){
    $data = self::getVirus();
    $affiche = self::openTable();
    foreach($data as $virus){
      $affiche .= "<tr style='cursor: pointer;' onclick='document.location.href=\"../virus/virus.php?id=".$virus->id."\"'>
                    <th class='align-middle'>".$virus->name."</th>
                    <td class='text-center align-middle'>".$virus->purif_date."</td>
                    <td class='text-center align-middle'>".self::afficheInvestigator($virus->investigator)."</td>
                    <td class='text-center align-middle'>".$this->calculPP($virus->OD_260)."/uL</td>
                  </tr>";
                }
      $affiche .= self::closeTable();
      return $affiche;
    }
    private function getImg($img=false){
      if ($img){
        return "<img src='".$this->imgFolder."/".$img."' width='300px'>";
      }
      else{
        return false;
      }
    }

    public function afficheSingleVirus($id){
      $data = self::getVirus($id);
      if (isset($data->gradient)){
        $gradient = "";
      }
      else{
        $gradient = "";
      }
      if (isset($data->gel)){
        $gradient = "";
      }
      else{
        $gradient = "";
      }
      $affiche .="<div class='card mt-3'>
          <h4 class='card-header'>".$data->name."</h4>
          <div class='card-body'>
            <table class='table table-bordered'>
              <tr>
                <th>Purification date</th>
                <td>".$data->purif_date."</td>
              </tr>
              <tr>
                <th>Investigator</th>
                <td>".self::afficheInvestigator($data->investigator)."</td>
              </tr>
              <tr>
                <th>Cells</th>
                <td>".$data->cells."</td>
              </tr>
              <tr>
                <th>Plate number</th>
                <td>".$data->plate."</td>
              </tr>
              <tr>
                <th>[Physical particules]</th>
                <td>".self::calculPP($data->OD_260)."/uL (OD 260nm = ".$data->OD_260.")</td>
              </tr>
              <tr>
                <th>[PFU]</th>
                <td>".$data->pfu."</td>
              </tr>
              <tr>
                <th>Storage</th>
                <td>".$data->storage."</td>
              </tr>
              <tr>
                <th>Comments</th>
                <td>".$data->comment."</td>
              </tr>
              <tr>
                <th>Gradient</th>
                <td>".self::getImg($data->gradient)."</td>
              </tr>
              <tr>
                <th>SDS-PAGE</th>
                <td>".self::getImg($data->gel)."</td>
              </tr>
            </table>
            <a href='modif.php?id=$data->id' class='btn btn-primary'>Modify the data</a>
          </div>
        </div>";
      return $affiche;
    }

    private function openTable(){
      $affiche ='<table class="table table-hover table-sm">
        <thead>
          <tr>
            <th class="text-center align-middle" scope="col">Virus Name</th>
            <th class="text-center align-middle" scope="col">Purification date</th>
            <th class="text-center align-middle" scope="col">Investigator</th>
            <th class="text-center align-middle" scope="col">[Physical particules]</th>
          </tr>
        </thead>
        <tbody>';
    return $affiche;
    }

    private function closeTable(){
      return '</tbody></table></div></div>';
    }

    private function calculPP($OD){
      $num = floatval($OD)*1.16*10000000000;
      return sprintf("%.2e", $num);
    }

    public function newSingleVirus($name, $investigator, $purif_date, $cells, $plate, $OD_260, $pfu, $storage, $comment, $gel, $gradient){
      if(Database::query("INSERT INTO $this->table (name, investigator, purif_date, cells, plate, OD_260, pfu, storage, comment, gel, gradient) VALUES (?,?,?,?,?,?,?,?,?,?,?)",
      [
        $name, $investigator, $purif_date, $cells, $plate, $OD_260, $pfu, $storage, $comment, $gel, $gradient
      ])){
        return true;
      }
    }

    public function upSingleVirus($name, $investigator, $purif_date, $cells, $plate, $OD_260, $pfu, $storage, $comment, $gel, $gradient, $id){
      if(Database::query("UPDATE $this->table SET
        name = ?,
        investigator = ?,
        purif_date = ?,
        cells = ?,
        plate = ?,
        OD_260 = ?,
        pfu = ?,
        storage = ?,
        comment = ?,
        gel = ?,
        gradient = ?
        WHERE id  = ?
      ",[$name, $investigator, $purif_date, $cells, $plate, $OD_260, $pfu, $storage, $comment, $gel, $gradient, $id])){
        return true;
      }
    }

    public function delSingleVirus($id){
      if(Database::query("DELETE FROM $this->table WHERE id = $id")){
        return true;
      }
    }
}
