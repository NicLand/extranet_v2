<?php
namespace Extranet;

class Session{

  static $instance;

  public function __construct(){
    session_cache_limiter('private_no_expire,must-revalidate');
    session_start();
  }

  static function getInstance(){
    if(!self::$instance){
    self::$instance =  new Session();
    }
    return self::$instance;
  }

  public function setFlash($key, $msg){
    $_SESSION['flash'][$key] = $msg;
  }

  public function hasFlashes(){
    return isset($_SESSION['flash']);
  }

  public function getFlashes(){
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $flash;
  }

  public function write($key, $value){
    $_SESSION[$key] = $value;
  }

  public function read($key){
    return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
  }

  public function delete($key){
    unset($_SESSION[$key]);
  }
}
