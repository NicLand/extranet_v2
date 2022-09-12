<?php
namespace Extranet;

class Strain {

  private $table;

  public function __construct(){
    $this->table = App::getTableStrains();
  }

  public function getSearchList($res,$num){
    if($num>0){
      $affiche  = '<h5 class="m-3">Number of results : '.$num.'</h5>';
      $affiche .= self::getTableHead();
      foreach($res as $strain){
        $affiche .= "<tr style='cursor:pointer' onClick=\"document.location='details.php?id=".$strain->id."'\">
                      <th class='text-center align-middle'>$strain->groupe</th>
                      <td class='text-center align-middle'>$strain->name</td>
                      <td class='text-center align-middle'>$strain->origin</td>
                      <td class='text-center align-middle'>$strain->paper</td>
                      <td class='text-center align-middle'>$strain->comment</td>
                    </tr>";
      }
      $affiche .= self::getTableClose();
    }
    return $affiche;
  }
  public function getStrainList(){
    $affiche = self::getTableHead();
    foreach(self::getStrains() as $strain){
      $affiche .= "<tr style='cursor:pointer' onClick=\"document.location='details.php?id=".$strain->id."'\">
                    <th class='text-center align-middle'>$strain->groupe</th>
                    <td class='text-center align-middle'>$strain->name</td>
                    <td class='text-center align-middle'>$strain->origin</td>
                    <td class='text-center align-middle'>$strain->paper</td>
                    <td class='text-center align-middle'>$strain->comment</td>
                  </tr>";
    }
    $affiche .= self::getTableClose();
    return $affiche;
  }
  public function getStrain($id){
    $affiche ="";
    $data = self::getSingleStrain($id);
    $affiche .="<div class='card mt-3'>
        <h5 class='card-header'>".$data->name."</h5>
        <div class='card-body'>
          <table class='table table-bordered'>
          <tr>
            <th>Group</th>
            <td>".$data->groupe."</td>
          </tr>
            <tr>
              <th>Origin</th>
              <td>".$data->origin."</td>
            </tr>
            <tr>
              <th>Related Paper</th>
              <td>".$data->paper."</td>
            </tr>
            <tr>
              <th>Comments</th>
              <td>".$data->comment."</td>
            </tr>
          </table>
          <a href='modif.php?id=$data->id' class='btn btn-primary'>Modify the plasmide's data</a>
        </div>
      </div>";
    return $affiche;
  }

  private function getStrains(){
    return Database::query("SELECT * FROM $this->table")->fetchAll();
  }
  public function getSingleStrain($id){
    return Database::query("SELECT * FROM $this->table WHERE id = $id")->fetch();
  }
  private function getTableHead(){
    return '
    <table class="table table-hover table-sm ">
      <thead>
        <tr>
          <th class="text-center" scope="col">Group</th>
          <th class="text-center" scope="col">Name</th>
          <th class="text-center" scope="col">Origin</th>
          <th class="text-center" scope="col">Related paper</th>
          <th class="text-center" scope="col">Comment</th>
          </tr>
      </thead>
      <tbody>
    ';
  }
  private function getTableClose(){
    return "</tbody></table>";
  }
  public function newStrain($name,$origin,$paper,$groupe,$comment){
    if(Database::query("INSERT INTO $this->table (name,origin,paper,groupe,comment) VALUES (?,?,?,?,?)",[$name,$origin,$paper,$groupe,$comment])){
      return true;
    }
  }
  public function upStrain($id,$name,$origin,$paper,$groupe,$comment){
    //var_dump($name);
    if(Database::query("UPDATE $this->table SET
      name = ?,
      origin = ?,
      paper = ?,
      groupe = ?,
      comment = ?
      WHERE id = ?
    ",[$name,$origin,$paper,$groupe,$comment,$id])){
      return true;
    }
  }
  public function delStrain($id){
    if(Database::query("DELETE FROM $this->table WHERE id = $id")){
      return true;
    }
  }
}
