<?php
namespace Extranet;

class TeamSpacvir {

  static $rapidAccess = [];
  public $table_investigator;
  public $table_past_investigator;

  public function __construct(){
    $this->table_investigator = App::getTableUsers();
    $this->table_past_investigator = App::getTablePastMembers();
  }

  static function getTable(){
    return App::getTableSpacvirItems();
  }

  static function getRapidAccess(){
    $table = self::getTable();
    $items = Database::query("SELECT name , link from $table ORDER BY ordre ASC")->fetchall();
    foreach($items as $item){
      $link = App::getRoot().'/spacvir/'.$item->link;
      self::$rapidAccess[$item->name] = $link;
    }
    return self::$rapidAccess ;
  }

}
