<?php

namespace Extranet;

class Auth{

  private $options = ['restriction_msg' => "Vous n'etes pas autorise a aller sur cette page."];
  private $session;
  private $table;

  public function __construct($session, $options){
    $this->options = array_merge($this->options, $options);
    $this->session = $session;
    $this->table = App::getTableUsers();
  }

  public function hashPassword($password){
    return md5("MFP" . $password . "commande");
  }

  public function hashLogin($username){
    return md5($username);
  }

  public function register($db, $name, $firstname, $username, $password, $email, $team){
    $password = $this->hashPassword($password);
    $username = $this->hashLogin($username);
    $token = Str::random(60);
    $db->query("INSERT INTO $this->table SET name = ? , firstname = ? , username = ?, password = ?, email = ? , team_id = ?, confirmation_token = ?", [
      $name,
      $firstname,
      $username,
      $password,
      $email,
      $team,
      $token
    ]);
    $user_id = $db->lastInsertId();
    mail(
      $email,
      "Confirmation de votre inscription",
      "Cliquez sur ce lien pour valider votre compte :\n\n ".App::getRoot()."/user/confirm.php?id=$user_id&token=$token",
      'From: no-reply@mfp.cnrs.fr' . "\r\n"
    );
  }

  public function confirm($db, $user_id, $token){
    $user = $db->query("SELECT * FROM $this->table WHERE id =?", [$user_id])->fetch();
    if ($user && $user->confirmation_token == $token){
      $db->query("UPDATE $this->table SET confirmation_token = NULL, confirmation_date = NOW() WHERE id =?", [$user_id]);
      $this->session->write('auth', $user);

      mail(
        'nicolas.landrein@u-bordeaux.fr',
        'New extranet user',
        'Cliquez sur ce lien pour valider le compte : '.App::getRoot()."/user/admin_confirmed.php?id=$user_id",
        'From : no-reply@mfp.cnrs.fr'. "\r\n"

      );
      return true;
    }
    else{
      return false;
    }
  }

  public function restrict(){
    if(!$this->session->read('auth')){
      $this->session->setFlash('danger' ,  $this->options['restriction_msg']);
      App::redirect('login.php');
      exit();
    }
  }

  public function user(){
    if(!$this->session->read('auth')){
      return false;
    }
    return $this->session->read('auth');
  }

  public function connect($user){
    $this->session->write('auth', $user);
  }

  public function connectFromCookie($db){
    if(isset($_COOKIE['remember']) && !$this->user()){
      $remember_token = $_COOKIE['remember'];
      $parts = explode('//', $remember_token);
      $user_id = $parts[0];
      $user = $db->query("SELECT * FROM $this->table WHERE id = ?",[$user_id])->fetch();
      if ($user){
        $expected = $user_id . '//' . $user->remember_token .sha1($user->id.'extranet_mfp');
        if($expected == $remember_token){
          $this->connect($user);
          $this->session->write('auth', $user);
          $this->session->setFlash('success', "Vous êtes connecté !");
          setcookie('remember', $remember_token, time() + 60 * 60 * 24);
        }
        else{
          setcookie('remember', null, -1);
        }
      }
      else{
        setcookie('remember', null, -1);
      }
    }
  }

  public function login($db, $username, $password, $remember = false){
      $username = $this->hashLogin($username);
      $user = $db->query("SELECT * FROM $this->table WHERE (username = :username OR email = :username) AND confirmation_date IS NOT NULL AND admin_validate = 1" , ['username' => $username])->fetch();
      if($user){
        $pass = $this->hashPassword($password);
        if($pass == $user->password){
        //if(password_verify($password, $user->password)){
          $this->connect($user);
            if($remember){
              $this->remember($db, $user->id);
            }
            $this->session->setFlash('success', "Vous êtes logué !");
       }
     }
  }

  public function remember($db, $user_id ){
      $remember_token = Str::random(250);
      $db->query("UPDATE $this->table SET remember_token =? WHERE id = ?" , [$remember_token,$user_id]);
      setcookie('remember', $user_id . '//' . $remember_token .sha1($user_id.'extranet_mfp'), time() + 60 * 60 * 24 * 7);
    }

  public function logout(){
      setcookie('remember', NULL, -1);
      $this->session->delete('auth');
    }

  public function resetPassword($db, $email){
      $user = $db->query("SELECT * FROM $this->table WHERE (email = ?) AND confirmation_date IS NOT NULL" , [$email])->fetch();
      if($user){
        $reset_token = Str::random(60);
        $db->query("UPDATE $this->table SET reset_token =?, reset_date=NOW() WHERE id=?" , [$reset_token,$user->id]);
        if(mail($email, "R�initialisation de votre mot de passe", "Cliquez sur ce lien pour changer votre mot de passe :\n\n ".App::getRoot()."/user/reset.php?id={$user->id}&token=$reset_token")){
          return $user;
        }
        return false;
        }
  }

  public function checkResetToken($db, $user_id, $token){
    return $db->query("SELECT * FROM $this->table WHERE id=? AND reset_token IS NOT NULL AND reset_token=? AND reset_date > DATE_SUB(NOW(), INTERVAL 30 MINUTE)", [$user_id, $token])->fetch();
  }

  public function changePass($db, $password, $user_id){
    $password = $this->hashPassword($password);
    $user = $db->query("UPDATE $this->table SET password =? WHERE id=?", [$password,$user_id]);
    return "OK";
  }

}
