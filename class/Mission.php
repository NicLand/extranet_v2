<?php
namespace Extranet;
use Extranet\Database;
class Mission{

  private $data;
  private $table;
  private $id;

  public function __construct(){
    $this->table = App::getTableMissions();
  }
  private function setMission($id){
    $db = Database::query("SELECT * FROM $this->table WHERE id = $id");
    $this->data = $db->fetch();
    return $this->data;
  }

  public function getData($id){
    return $this->setMission($id);
  }

  private function hasMission($user){
    $db = Database::query("SELECT * FROM $this->table WHERE user_id = $user AND validated is NULL");
    $this->data = $db->fetchAll();
    return $this->data;
  }

  public function getMissions($user){
    return $this->hasMission($user);
  }

  public function newMission(){
    if(Database::query("INSERT INTO $this->table (category) VALUES (?)",[$cat])){
      return true;
    }
  }
}
