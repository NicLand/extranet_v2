<?php
namespace Extranet;

class ProtectForm{

    static function protectData($data){
      //var_dump($data);
      if (is_array($data)) {
              foreach ($data AS $cle => $valeur) {
                  if (is_array($data)) {
                      $data[$cle] = self::protectData($data[$cle]);
                  } else {
                      if (is_numeric($valeur)) {
                          //cast pour les nombres
                          $data[$cle] = intval($valeur);
                      } else {
                          //protection des chaines
                          $data[$cle] = htmlspecialchars($valeur, ENT_QUOTES);
                      }
                  }
              }
          } else {
              $data = htmlspecialchars($data, ENT_QUOTES);
          }
          //var_dump($data);
          return $data;
    }

    static function decodeData($data){
      if (is_array($data)) {
              foreach ($data AS $cle => $valeur) {
                  if (is_array($data)) {
                      $data[$cle] = self::decodeData($data[$cle]);
                  } else {
                      if (is_numeric($valeur)) {
                          //cast pour les nombres
                          $data[$cle] = intval($valeur);
                      } else {
                          //protection des chaines
                          $data[$cle] = htmlspecialchars_decode($valeur);
                      }
                  }
              }
          } else {
              $data = htmlspecialchars_decode($data);
          }
          //var_dump($data);
          return $data;
    }
}
