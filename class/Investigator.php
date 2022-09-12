<?php
namespace Extranet;

class Investigator{

  private $table_invest;
  private $table_old;

  public function __construct(){
    $this->table_current = App::getTableUsers();
    $this->table_old = App::getTablePastMembers();

  }

  public function Investigator($id=false){
    if($id){
      return Database::query("SELECT * FROM SELECT * FROM $this->table_current WHERE id=?",[$id])->fetch();
    }
    else{
      return Database::query("SELECT * FROM $this->table_current WHERE proparacyto = 1 AND admin_validate = 1 ORDER BY name ASC")->fetch();
    }
  }

  public function getPastInvestigator($id=false){
    if($id){
      return Database::query("SELECT * FROM $this->table_old WHERE id=?",[$id])->fetch();
    }
    else{
      return Database::query("SELECT * FROM $this->table_old ORDER BY name ASC")-fetch();

    }
  }

  public function getInvestigator($id){
    if(isset($id)){
      if($invest = Database::query("SELECT * FROM $this->table_current WHERE id = ?",[$id])->fetch()){
        $affiche = $invest->firstname;
        $affiche .= " ";
        $affiche .= $invest->name;
      }
      elseif($old = Database::query("SELECT * FROM $this->table_current WHERE id = ?",[$id])->fetch()){
        $affiche = $old->firstname;
        $affiche .= " ";
        $affiche .= $old->name;
      }
    else{
      $affiche ="";
    }
    }
    return $affiche;
  }

}
