<?php
namespace Extranet;
use \PDO;

class DatabaseWP{

  private static $bdd;

  private static function DBNAME(){
    return App::getDbNameWP();
  }
  private static function DBHOST(){
    return App::getDbHost();
  }
  private static function DBUSER(){
    return App::getDbUserWP();
  }
  private static function DBPASS(){
    return App::getDbPassWP();
  }

  private static function getPDO(){
    if(self::$bdd === null){
      $bdd = new PDO('mysql:host='.self::DBHOST().';dbname='.self::DBNAME().';',self::DBUSER(),self::DBPASS());
      $bdd -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $bdd -> setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    self::$bdd = $bdd;
    }
    return self::$bdd;
  }

  public static function query($query, $params = false){
      if($params){
        $req = self::getPDO()->prepare($query);
        $req->execute($params);
      }
      else{
        $req = self::getPDO()->query($query);
      }
        return $req;
    }

  public static function lastInsertId(){
    return self::$bdd->lastInsertId();
  }
}
