<?php
namespace Extranet;

class Access{

  public $extranet;
  public $user;

  public function __construct($extranet, $user){
    $this->extranet = $extranet;
    $this->user = $user;
  }

  public function access(){
    $extra = $this->extranet;
    if($this->user->super_admin == 1 || $this->user->$extra == 1){
      return true;
    }
    else{
      return false;
    }
  }

  public function accessCommande(){
    if($this->user->commande == NULL){
      return false;
    }
    return true;
  }

  public function accessCommandeSpacvir(){
    if($this->user->commande == NULL || $this->user->spacvir != 1){
      return false;
    }
    return true;
  }
  public function accessCommandeReger(){
    if($this->user->commande == NULL || $this->user->reger != 1){
      return false;
    }
    return true;
  }
}
