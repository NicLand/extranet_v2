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
$champs = ['t_insert','c_insert','t_vector','c_vector','q_vector','ratio','v_ligation','v_ligase','buffer'];
$affiche ="";
if(!empty($_POST['calcul'])){
  foreach($_POST as $index => $valeur) {
     $$index = $valeur;
  }
  $errors =[];
  $validator = new Validator($_POST);
  $validator->isNumeric('t_insert',"The insert size is not correct.");
  $validator->isNumeric('c_insert',"The insert concentration is not correct.");
  $validator->isNumeric('t_vector',"The vector size is not correct.");
  $validator->isNumeric('c_vector',"The vector concentration is not correct.");
  $validator->isNumeric('q_vector',"The vector quantity is not correct.");
  $validator->isNumeric('v_ligation',"The ligation volume is not correct.");
  $validator->isNumeric('v_ligase',"The DNA Ligase volume is not correct.");
  $validator->isSelect('buffer',"Please Choose a buffer concentration.  ");

  if($validator->isValid()){
    $q_insert = number_format((($q_vector * $t_insert * $ratio)/$t_vector),2);
    $v_vector = ($q_vector/$c_vector);
    $v_vector = number_format($v_vector,2);
    $v_insert = ($q_insert/$c_insert);
    $v_insert = number_format($v_insert,2);
    $v_buffer = ($v_ligation/$buffer);
    $v_ligase = 1;
    $v_water = number_format(($v_ligation - ($v_vector + $v_insert + $v_buffer + $v_ligase)),2);
    if($v_water <0){$alert = "<p class='bg-warning'>The ligation volume is too small !</p>";$css_water = "class='bg-warning'";}else{$alert="";$css_water="";}
    $affiche ="
    <div class='container w-50'>
    $alert
    <table class='table border'>
      <thead>
        <tr class='table-secondary'>
        <th scope='col'>Product</th>
        <th scope='col'>Volume</th>
        </tr>
      </thead>
      <tr>
      <td>Vector ($q_vector ng)</td>
      <td>$v_vector µL</td>
      </tr>
      <tr>
      <td>Insert ($q_insert ng)</td>
      <td>$v_insert µL</td>
      </tr>
      <tr>
      <td>Buffer ($buffer X)</td>
      <td>$v_buffer µL</td>
      </tr>
      <tr>
      <td>DNA Ligase</td>
      <td>$v_ligase µL</td>
      </tr>
      <tr $css_water>
      <td>Water</td>
      <td>$v_water µL</td>
      </tr>
      <tr class='table-secondary'>
      <td>Total</td>
      <td>$v_ligation µL</td>
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
  $t_insert = "";
  $c_insert = "";
  $t_vector = "";
  $c_vector = "";
  $q_vector = 100;
  $ratio = 3;
  $v_ligation = 10;
  $v_ligase = "";
  $buffer = "";

}

//===========================================================
$rapidAccess = [];
$menuItem = [];
$title = 'WebTools | Ligation';
$titleLink = 'webtools/index.php';
echo Header::getHeader($title, $titleLink, $rapidAccess, $menuItem);
if(isset($validator)){echo $validator->afficheErrors($errors,"EN");}

echo '<a class="btn btn-secondary m-3" href="../index.php" role="button">Back to Extranet</a>';
echo '<h1 class="mt-3">Ligation Tools</h1>';

$form = new Form;
echo $form->openForm();
echo $form->inputCard('t_insert','number','Insert size (in base pair) :',$t_insert,'');
echo $form->inputCard('c_insert','number','Insert concentration (in µg/µL) :',$c_insert,'');
echo $form->inputCard('t_vector','number','Vector size (in base pair) :',$t_vector,'');
echo $form->inputCard('c_vector','number','Vector concentration (in µg/µL): ',$c_vector,'');
echo $form->inputCard('q_vector','number','Vector quantity (in ng) :',$q_vector,'');
echo $form->inputCard('ratio','number','Ratio Insert/Vector :',$ratio,'');
echo $form->inputCard('v_ligation','number','Volume of ligation (µL) :',$v_ligation,'');
echo $form->inputCard('v_ligase','number','Volume of DNA Ligase (µL) :',$v_ligase,'');
echo $form->selectSimpleCard([2=>"2X",5=>"5X",10=>"10X"],'buffer','Buffer :',$buffer);
echo $form->submit('primary','calcul','Ligation !');
echo $form->closeForm();
echo $affiche;
?>

<?= Footer::getFooter();
