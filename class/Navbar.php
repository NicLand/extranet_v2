<?php

namespace Extranet;

class Navbar{

  private $title = "Extranet MFP";
  private $titleLink = 'index.php';
  private $rapidAccess = [
    'SpacVir'=>'https://www.mfp.cnrs.fr/extranet_v2/spacvir/',
    'ProParaCyto'=>'https://www.mfp.cnrs.fr/extranet_v2/proparacyto/',
    'iMET' => 'https://www.mfp.cnrs.fr/extranet_v2/imet/',
    'ex-REGER' => 'https://www.mfp.cnrs.fr/extranet_v2/reger/'];
  private $menuItem = [
    'Réservation MFP'=>'http://www.mfp.cnrs.fr/grr/',
    'Achats'=>'https://www.mfp.cnrs.fr/mfp/commande/',
    'Missions'=> 'https://www.mfp.cnrs.fr/extranet/administration/mission/note.php'
  ];

public function __construct($title, $titleLink, $rapidAccess, $menuItem){
  if(!empty($title)){$this->title = $title;}
  if(!empty($titleLink)){$this->titleLink = $titleLink;}
  if(!empty($rapidAccess)){$this->rapidAccess = $rapidAccess;}
  if(!empty($menuItem)){$this->menuItem = $menuItem;}
}

private function afficheParamDebut(){
  return '<nav class="navbar navbar-expand-lg sticky-top navbar-dark bg-primary indigo">
          <div class="container-fluid">
          <a class="navbar-brand" href="'.App::getRoot().'/'.$this->titleLink.'">'.$this->title.'</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span></button>';
}

private function afficheSousMenu(){
  $affiche = "";
    if(!empty($this->rapidAccess)){
    $affiche ='<li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Rapid Access
              </a>
              <ul class="dropdown-menu" aria-labelledby="navbarDropdown">';

    foreach($this->rapidAccess as $item => $link){
      $affiche .='<li><a class="dropdown-item" href="'.$link.'">'.$item.'</a></li>';
  }
  $affiche.='</ul></li>';
}
else{}

return $affiche;
}

private function afficheButton(){
    $affiche='<div class="collapse navbar-collapse" id="navbarSupportedContent">
              <ul class="navbar-nav mr-auto">';
  foreach ($this->menuItem as $item => $link){
    $affiche .= '<li class="nav-item"><a class="nav-link" href="'.$link.'">'.$item.'</a></li>';
  }
    $affiche .= self::afficheSousMenu();
    $affiche .='</ul></div>';
  return $affiche;
}

private function afficheMenuUser(){
  $affiche = "";
  $auth = App::getAuth();
  $user = $auth->user();
  if($user){
  $affiche .= '<ul class="navbar-nav mr-auto"><li class="nav-item dropdown">';
  $affiche .= '<button type="button" class="btn btn-warning dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
     Mon menu</button>';
  $affiche .= '<ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">';
  $affiche .= '<li><a class="dropdown-item" href="'.App::getRoot().'/user/account.php">Mon compte</a></li>';
  $affiche .= '<li><hr class="dropdown-divider"></li>';
  $affiche .= '<li><a class="dropdown-item" href="'.App::getRoot().'/logout.php">Deconnexion</a></li>';
  $affiche .= '</ul></li></ul>';
}
  return $affiche;
}

private function afficheParamFin(){
  return '</nav>';
}

  public function getNavbar(){
    $affiche = self::afficheParamDebut();
    $affiche .= self::afficheButton();
    $affiche .= self::afficheMenuUser();
    $affiche .= self::afficheParamFin();
    return $affiche;
  }

}
