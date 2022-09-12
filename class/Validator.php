<?php
namespace Extranet;
use Extranet\Database;
use Extranet\Session;
use \DateTime;

class Validator{

  private $data;
  private $errors = [];
  private $formatDate = "Y-m-d";

  public function __construct($data){
    $this->data = $data;
  }

  public function getProtectedData(){
    return ProtectForm::protectData($this->data);
  }
  public function getField($field){
    if(!isset($this->data[$field])){
      return null;
    }
    return $this->data[$field];
}

  public function isDNA($field, $errorMsg){
    $var = $this->getField($field);
  if(!isset($this->data[$field]) || !preg_match('/^[atgcATGC]+$/', $var)){
    $this->errors[$field] = $errorMsg;
  }
  return $errorMsg;
  }

  public function isText($field, $errorMsg){
    $var = $this->getField($field);
    if(!isset($this->data[$field]) || !is_string($var) || empty($var)){
      $this->errors[$field] = $errorMsg;
      return $errorMsg;
    }
  }
  public function isAlpha($field, $errorMsg){
    $var = $this->getField($field);
    if(!isset($this->data[$field]) || !preg_match('/^[a-zA-Z0-9_-]+$/', $var)){
      $this->errors[$field] = $errorMsg;
      return $errorMsg;
    }
  }

  public function isNumeric($field, $errorMsg){
    $var = $this->getField($field);
    if(!isset($this->data[$field]) || !is_numeric($var)){
      $this->errors[$field] = $errorMsg;
      return $errorMsg;
    }
  }

  public function isCheck($field, $errorMsg, $checked=false){
      if(!isset($this->data[$field])){
        $this->errors[$field] = $errorMsg;
        return $errorMsg;
      }
  }

  public function isYearValid($field, $errorMsg){
    $value = (int)$this->data[$field];
    if(!isset($value) || $value > date('Y') || $value < date('Y')-15){
      $this->errors[$field] = $errorMsg;
      return $errorMsg;
    }
  }

  public function isYear($field, $errorMsg){
    if(!isset($this->data[$field]) || strlen($this->data[$field]) !=4){
      $this->errors[$field] = $errorMsg;
    }
    return $errorMsg;
  }

  public function isYearChrono($field, $field2, $errorMsg){
    if($this->data[$field] > $this->data[$field2]){
      $this->errors[$field] = $errorMsg;
    }
    return $errorMsg;
  }

  public function isSelect($field, $errorMsg){
      if(!isset($this->data[$field]) || $this->data[$field] == "0"){
        $this->errors[$field] = $errorMsg;
        return $errorMsg;
      }
  }

  public function isUniqPmid($field, $equipe, $table, $errorMsg){
    $record = DatabaseWP::query("SELECT id FROM $table WHERE $field =? AND equipe =? " , [$this->getField($field) , $equipe])->fetch();
    if($record){
      $this->errors[$field] = $errorMsg;
    }
  }
  public function isUniq($field, $db, $table, $errorMsg){
    $record = $db->query("SELECT id FROM $table WHERE $field =?" , [$this->getField($field)])->fetch();
    if($record){
      $this->errors[$field] = $errorMsg;
    }
  }

  public function isEmail($field, $errorMsg){
    $var = $this->getField($field);
    if(!filter_var($var, FILTER_VALIDATE_EMAIL)){
      $this->errors[$field] = $errorMsg;
    }
  }

  public function isConfirmed($field, $errorMsg=''){
    $value = $this->getField($field);
    $value_confirm = $this->getField($field."_confirm");
    if(empty($value) || $value != $value_confirm){
      $this->errors[$field] = $errorMsg;
    }
  }
  public function isDate($field, $errorMsg){
    $format = 'Y-m-d';
    $var = $this->getField($field);
    $dt = DateTime::createFromFormat($format,$var);
    $var = $this->getField($field);
    if(empty($var) || $dt = false){
      $this->errors[$field] = $errorMsg;
    }
  }

  public function isAllerRetour($aller, $retour, $errorMsg){
    $aller1 = new DateTime ($this->getField($aller));
    $retour1 = new DateTime ($this->getField($retour));
    if($aller1 > $retour1){
      $this->errors[$aller] = $errorMsg;
    }
  }
  public function isFileUniq($field, $team, $type, $folder, $db, $table, $errorMsg){
    $new = new Upload($field, $team, $type,$folder);
    $record = $db->query("SELECT id FROM $table WHERE $field = ?", [$new->setName()])->fetch();
    if($record){
      $this->errors[$field] = $errorMsg;
    }
  }
  public function isFile($field, $errorMsg){
    if($_FILES[$field]['error'] != 0){
      $this->errors[$field] = $errorMsg;
    }
  }

  public function isValid(){
    return empty($this->errors);
  }

  public function getErrors(){
    return $this->errors;
  }
  public function afficheErrors($errors,$lang=false){
    if(isset($lang) && $lang == "EN"){$errorTitle = "You did not fill out the form correctly : ";}
    else {$errorTitle = "Vous n'avez pas rempli le formulaire correctement : ";}
    $affiche = "";
    if(!empty($errors)){
      $affiche .= '<div class="alert alert-danger">';
      $affiche .= '<p>'.$errorTitle.'</p><ul>';
      foreach($errors as $error){
        $affiche .= '<li>'.$error.'</li>';
      }
      $affiche .= '</ul>';
      $affiche .= "<a href='javascript:history.go(-1)' class='btn btn-warning'>Corriger le formulaire</a></div>";
    }
    return $affiche;
  }
}
