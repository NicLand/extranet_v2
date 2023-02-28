<?php
namespace Extranet;

class TeamSpacvirFreezer {

  private $table;
  private $table_freezer_name = "extranet_spacvir_freezer_name";

  public function __construct(){
    $this->table = App::getTableSpacvirFreezer();
  }

  public function addRack(){
    $sql = "INSERT INTO $this->table (stage ,rack, box, content) VALUES";
    $val="";
    for($lettre='e'; $lettre!='i';$lettre++){
        for($j=1;$j<=15;$j++){
          $val .= ",('3','$lettre','$j','')";
        }
        $val .="";
    }
    $val = ltrim($val,',');
    $req = $sql.$val;
    return $req;
  }

  public function getFreezer(){
    return Database::query("SELECT DISTINCT freezer FROM $this->table")->fetchAll();
  }
  public function getFreezerName($id){
    return Database::query("SELECT name FROM $this->table_freezer_name WHERE id = $id")->fetch();
  }
  public function getStage($freezer){
    return Database::query("SELECT DISTINCT stage from $this->table WHERE freezer = $freezer")->fetchAll();
  }
  public function getRack($freezer,$stage){
    return Database::query("SELECT DISTINCT rack from $this->table WHERE freezer = $freezer AND stage = '$stage'")->fetchAll();
  }
  public function getBoxes($freezer,$stage,$rack){
    return Database::query("SELECT * FROM $this->table WHERE freezer = $freezer AND stage = '$stage' AND rack = $rack")->fetchAll();
  }
  public function getBox($id){
    return Database::query("SELECT * FROM $this->table WHERE id = $id")->fetch();
  }

  public function topFreezer($freezer){
    return '<div class="card mb-3 text-center">
              <div class="card-body">
                <h1 class="card-title text-center">'.$freezer->name.'</h1>';
  }

  public function bottomFreezer(){
    return '</div>
            </div>';
  }

  public function topStage($stage){
    return '<div class="card mb-3 bg-danger bg-opacity-10">
              <div class="row g-0">
                <div class="col-md-2">
                  <h1 class="display-1 text-center">'.$stage.'</h1>
                </div>';
  }
  public function bottomStage(){
    return '</div>
            </div>';
  }
  public function topRack($rack){
      $affiche = '<div class="col">';
      $affiche .= '<div class="card m-1">
                    <div class="card-header bg-primary bg-opacity-25">
                      <h4>Rack '.$rack->rack.'</h4>
                    </div>';
    return $affiche;
  }

  public function bottomRack(){
    return '</div></div>';
  }

  public function Boxes($boxes){
    $affiche = '<div class="list-group list-group-flush">';
    foreach($boxes as $box){
      if(!empty($box->name)){$name = $box->name;}else{$name="";}
      $affiche .= '<a href="box.php?id='.$box->id.'" class="list-group-item list-group-item-action"><h5>Box '.$box->box.'</h5> '.$name.'</a>';
    }
    $affiche .= '</div>';
    return $affiche;

  }

  public function afficheFreezer(){
    $freezers = self::getFreezer();
    $affiche ="";
    foreach($freezers as $freezer){
      $freezerName = self::getFreezerName($freezer->freezer);
      $affiche .= self::topFreezer($freezerName);
      $stages = self::getStage($freezer->freezer);
        foreach($stages as $stage){
          $affiche .= self::topStage($stage->stage);
            $racks = self::getRack($freezer->freezer,$stage->stage);
            foreach($racks as $rack){
              $affiche .= self::topRack($rack);

                $boxes = self::getBoxes($freezer->freezer,$stage->stage,$rack->rack);
                $affiche .= self::Boxes($boxes);

              $affiche .= self::bottomRack();
            }
          $affiche .= self::bottomStage();
        }
      $affiche .= self::bottomFreezer();
    }
    return $affiche;
  }

  public function afficheBox($id){
    $box = self::getBox($id);
    $freezer = self::getFreezerName($box->freezer);
    $affiche ="<div class='card mt-3'>
    <h5 class='card-header'>Details</h5>
        <div class='card-body'>
          <table class='table table-bordered'>
            <tr>
              <th>Position</th>
              <td>Freezer : ".$freezer->name." | Stage : ".$box->stage." | Rack : ".$box->rack." | Box : ".$box->box."</td>
            </tr>
            <tr>
              <th>Name</th>
              <td>".$box->name."</td>
            </tr>
            <tr>
              <th>Content</th>
              <td>".$box->content."</td>
            </tr>
          </table>

          <a href='modif.php?id=$id' class='btn btn-primary'>Modify</a>
        </div>
      </div>";
    return $affiche;
  }

  public function upFreezer($id,$name,$content){
    if(Database::query("UPDATE $this->table SET
      name = ?,
      content = ?
      WHERE id  = ?
    ",[$name, $content, $id])){
      return true;
    }
  }
}
