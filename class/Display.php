<?php

namespace Extranet;

class Display{

  static function TableauHead($fields){
    $affiche = '<table class="table table-hover table-sm">
      <thead>
        <tr>';
    foreach($fields as $field){
      $affiche.='<th class="text-center" scope="col">'.$field.'</th>';
    }
    $affiche .='</tr>
                  </thead>
                    <tbody>';
    return $affiche;
  }

  static function TableauTD($value){
    return "<td class='text-center align-middle'>$value</td>";
  }

  static function TableauTR($value){
    return "<tr>$value</tr>";
  }

  static function TableauFoot(){
    return '</tbody></table>';
  }

}
