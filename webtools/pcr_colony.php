<?php

//===========================================================
//Charge automatiquemebnt les Class utilisées dans les pages
// Dans le bon __NAMESPACE__
namespace Extranet;

require '../class/Autoloader.php';
Autoloader::register();

$auth = App::getAuth();
$user = $auth->user();

if(!$user){
  App::redirect('login.php');
  exit();
}
//===========================================================
$affiche ="";

if(!empty($_POST['calcul'])){
  foreach($_POST as $index => $valeur) {
     $$index = $valeur;
  }
  $errors =[];
  $validator = new Validator($_POST);
  $validator->isNumeric('nb_colony',"The number of colonies is not correct.");
  $validator->isSelect('buffer',"Please Choose a buffer concentration.  ");
  $validator->isSelect('dntp',"Please Choose a dNTPs concentration.  ");
  $validator->isSelect('primer1',"Please Choose the Primer1 concentration.  ");
  $validator->isSelect('primer2',"Please Choose the Primer2 concentration.  ");

  $validator->isnumeric('volume', "The volume is not correct.");
  if($validator->isValid()){
    $v_final = ($nb_colony*$volume)*1.1;
    $v_buffer = $v_final / $buffer;
    $v_dntp = (0.2 * $v_final) / $dntp;
    $v_primer1 = ($v_final * 0.5) / $primer1;
    $v_primer2 = ($v_final * 0.5) / $primer2;
    $v_taq = ($v_pol*$nb_colony)*1.1;
    $v_water = ($v_final -($v_buffer + $v_dntp + $v_primer1 + $v_primer2 + $v_taq));
    $affiche ="
    <div class='container w-50'>
    <table class='table border'>
      <thead>
        <tr class='table-secondary'>
        <th scope='col'>Product</th>
        <th scope='col'>Volume</th>
        <th scope='col'>Final concentration</th>
        </tr>
      </thead>
      <tr>
      <td>Water</td>
      <td>$v_water µL</td>
      <td>-</td>
      </tr>
      <tr>
      <td>Buffer ($buffer X)</td>
      <td>".number_format($v_buffer,1)." µL</td>
      <td>1 X</td>
      </tr>
      <tr>
      <td>dNTP's ($dntp mM)</td>
      <td>".number_format($v_dntp,1)." µL</td>
      <td>200 µM</td>
      </tr>
      <tr>
      <td>Primer 1 ($primer1 µM)</td>
      <td>".number_format($v_primer1,1)." µL</td>
      <td>0,5 µM</td>
      </tr>
      <tr>
      <td>Primer 2 ($primer2 µM)</td>
      <td>".number_format($v_primer2,1)." µL</td>
      <td>0,5 µM</td>
      </tr>
      <tr>
      <td>DNA Polymerase</td>
      <td>".number_format($v_taq,1)." µL</td>
      <td>$v_pol µL/reaction</td>
      <tr class='table-secondary'>
      <td>Total</td>
      <td>".number_format($v_final,1)." µL</td>
      <td></td>
      </tr>
      <tbody>

      </tbody>
    </table>
    ";
  }
  else{
    $errors = $validator->getErrors();

  }
}
else{
  $nb_colony = "";
  $buffer = "";
  $dntp = "";
  $primer1 = "";
  $primer2 = "";
  $volume = "10";
  $v_pol = "0,2";
}

//===========================================================

$rapidAccess = [];
$menuItem = [];
$title = 'Webtools | PCR on Single';
$titleLink = 'webtools/index.php';
echo Header::getHeader($title, $titleLink, $rapidAccess, $menuItem);
if(isset($validator)){echo $validator->afficheErrors($errors,"EN");}

echo '<a class="btn btn-secondary m-3" href="../index.php" role="button">Back to Extranet</a>';
echo '<h1 class="mt-3">PCR on single colony</h1>';

$form = new Form;
echo $form->openForm();
echo $form->inputCard('nb_colony','number','Number of colonies :',$nb_colony,'');
echo $form->selectSimpleCard([2=>"2X",5=>"5X",10=>"10X"],'buffer','Buffer :',$buffer);
echo $form->selectSimpleCard([2=>"2 mM",5=>"5 mM",10=>"10 mM",20=>"20 mM"],'dntp','dNTP concentration',$dntp);
echo $form->selectSimpleCard([2=>"2 µM",5=>"5 µM",10=>"10 µM",20=>"20 µM", 50=>"50 µM",100=>"100 µM"],'primer1','Primer1 concentration',$primer1);
echo $form->selectSimpleCard([2=>"2 µM",5=>"5 µM",10=>"10 µM",20=>"20 µM", 50=>"50 µM",100=>"100 µM"],'primer2','Primer2 concentration',$primer2);
echo $form->inputCard('volume','number','Volume of each PCR reaction :',$volume,'');
echo $form->inputCard('v_pol','number','Volume of DNA Polymerase / reaction :',$v_pol,'');
echo $form->submit('primary','calcul','PCR !');
echo $form->closeForm();
echo $affiche;
?>

<?= Footer::getFooter();
