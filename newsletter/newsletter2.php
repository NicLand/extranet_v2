<?php
namespace Extranet;
require '../class/Autoloader.php';
Autoloader::register();



$nl = new Newsletter;
$newsletter = $nl->getNL(20);
echo $newsletter;
