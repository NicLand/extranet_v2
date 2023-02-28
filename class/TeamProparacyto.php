<?php
namespace Extranet;

class TeamProparacyto{

  static $rapidAccess = [];
  public $table_investigator;
  public $table_past_investigator;

  public function __construct(){
    $this->table_investigator = App::getTableUsers();
    $this->table_past_investigator = App::getTablePastMembers();
  }

  static function getTable(){
    return App::getTableProparacytoItems();
  }

  static function getRapidAccess(){
    $table = self::getTable();
    $items = Database::query("SELECT name , link from $table ORDER BY ordre ASC")->fetchall();
    foreach($items as $item){
      $link = App::getRoot().'/proparacyto/'.$item->link;
      self::$rapidAccess[$item->name] = $link;
    }
    return self::$rapidAccess ;
  }

  public function getInvestigator($id){
    return Database::query("SELECT name, firstname FROM $this->table_investigator WHERE id = '$id'")->fetch();
  }

  public function getPastInvestigator($id){
    return Database::query("SELECT name, firstname FROM $this->table_past_investigator WHERE id = '$id'")->fetch();
  }

  public function displayInvestigator($id){

      $investigator = $this->getInvestigator($id);
      $old = $this->getPastInvestigator($id);
      
      if($investigator == true){
        return $investigator;
      }
      elseif($old == true){
          return $old;

    }
  }

}
