<?php
//===========================================================
//Charge automatiquemebnt les Class utilisées dans les pages
// Dans le bon __NAMESPACE__
namespace Extranet;

require 'class/Autoloader.php';
Autoloader::register();

//===========================================================
App::getAuth()->logout();
Session::getInstance()->setFlash('success', "Vous êtes déconnecté.e !");
App::redirect('login.php');
exit();
