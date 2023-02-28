<?php

namespace Extranet;
use Extranet\Database;

class ButtonIndex{

  private $titre;
  private $url;
  private $text;

  public function getButton($table){
    return Database::query("SELECT * FROM $table ORDER BY ordre ASC");
  }

  public function buildButton($table){
    $affiche="";
    $data = self::getButton($table);
    foreach ($data as $key=>$bouton){
      $affiche .='<div class="col mb-4">';
      $affiche .= '<a href="'.$bouton->link.'" class="text-decoration-none">';
      $affiche .= '<div class="card bg-'.$bouton->background.' text-'.$bouton->color.'">';
      $affiche .= '<div class="card-header h4">'.$bouton->name.'</div>';
      $affiche .= '<div class="card-body bg-light text-dark">';
      $affiche .= '<div class="card-text">'.$bouton->text.'</div>';
      $affiche .= '</div></div></a></div>';
    }
    return $affiche;

  }

  public function afficheButton($table){

    $affiche  = '<div class="row row-cols-1 row-cols-md-4 m-3">';
    $affiche .= self::buildButton($table);
    $affiche .= '</div>';
    return $affiche;
  }

  public function manualButton($name, $link, $text, $background, $color){

    $affiche = "";
    $affiche .='<div class="col mb-4">';
    $affiche .= '<a href="'.$link.'" class="text-decoration-none">';
    $affiche .= '<div class="card bg-'.$background.' text-'.$color.'">';
    $affiche .= '<div class="card-header h4 text-capitalize">'.$name.'</div>';
    $affiche .= '<div class="card-body bg-light text-dark">';
    $affiche .= '<div class="card-text">'.$text.'</div>';
    $affiche .= '</div></div></a></div>';

    return $affiche;

  }
}
