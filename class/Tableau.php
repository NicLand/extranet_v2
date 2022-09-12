<?php
namespace Extranet;

class Tableau{

  private function setTableHeader($items){
    if(isset($items) AND $items != NULL){
      $affiche ='<table class="table table-hover table-sm">';
      $affiche .= '<thead><tr>';
      foreach($items as $item){
        $affiche .= '<th class="text-center" scope="col">'.$item.'</th>';
      }
      $affiche .= '</tr></thead><tbody>';
    }
    return $affiche;
  }

  private function setTableBody($content){
      $affiche="";
      foreach($content as $rows){
        $affiche .= "<tr>";
      foreach($rows as $row){
        $affiche .= "<td class='text-center align-middle'>$row</td>";
      }
      $affiche .="</tr>";
      }
    return $affiche;
  }

  private function setTableFooter(){
    return "</tbody></table>";
  }

  public function displayTableau($items,$content){
    $affiche = self::setTableHeader($items);
    $affiche .= self::setTableBody($content);
    $affiche .= self::setTableFooter();
    return $affiche;
  }
}
