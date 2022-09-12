<?php

namespace Extranet;
use Extranet\Navbar;

class Header{

  public static function getHeader($title, $titleLink, $rapidAccess, $menuItem){

    if (!empty($title)){$titre = $title;}else{$titre = 'MFP Extranet';}
    $affiche ="";
     $affiche .= header('Content-Type: text/html; charset=utf-8');
     //ini_set( 'default_charset', 'ISO-8859-1').
     $affiche .='<!doctype html>
                  <html lang="en">
                    <head>
                      <!-- Required meta tags -->
                      <meta http-equiv="content-type" content="text/html; charset=utf-8" />
                      <meta name="viewport" content="width=device-width, initial-scale=1">
                      <script src="'.App::getRoot().'/include/ckeditor/ckeditor.js"></script>
                      <!-- Bootstrap CSS -->
                      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
                      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
                      <link rel="stylesheet" href="'.App::getRoot().'/inc/css/extranet.css">
                      <title>'.$titre.'</title>
                    </head>
                    <body>';
      //==========================================================
      // Navbar

      $nav = new Navbar($title, $titleLink, $rapidAccess, $menuItem);

      $affiche.= $nav->getNavbar();
      //==========================================================
      $affiche.= '<div class="container">';
      //================== Affichage des alertes==================
        if(Session::getInstance()->hasFlashes()){
          foreach(Session::getInstance()->getFlashes() as $type => $message){
            $affiche .='<div class="alert alert-'. $type .'">'. $message .'</div>';
      //================== Fin Affichage des alertes==============

          }
        }
        return $affiche;
  }

}
