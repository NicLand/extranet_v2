<?php
namespace Extranet;
class Str{

  static function random($length){
    $alpha = "0123456789abcdefghijklemnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    return substr(str_shuffle(str_repeat($alpha, $length)), 0 , $length);
  }

  static function wrapWord($str, $length, $cut = true){
    return wordwrap($str, $length, "<br />", $cut);
  }

  static function strLength($str){
    return strlen($str);
  }

  static function fileNameUpload($str){
    $str = strtr($str,
    '����������������������������������������������������',
    'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
    $str = preg_replace('/([^.a-z0-9]+)/i', '-', $str);
    $espace = " ";
    $str = str_replace ($espace, "-", $str);
    return $str;
  }

  static function arrayToStr($array, $separator){
    $list="";
    foreach($array as $value){
      $list .= $separator.$value;
    }
    return ltrim($list,$separator);
  }
  static function monthLetters($m){
	   $month = [
       1=>'Jan',
       2=>'Fev',
       3=>'Mar',
       4=>'Avr',
       5=>'Mai',
       6=>'Juin',
       7=>'Juil',
       8=>'Aou',
       9=>'Sept',
       10=>'Oct',
       11=>'Nov',
       12=>'Dec'
   ];
    return $month[$m];

  }
}
