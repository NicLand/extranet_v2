<?php

$title = "Label for ".$_POST['sample'];?>

<style type="text/css">
 body {
	margin: 0;
	padding: 0;
	font-family: Arial, Helvetica, sans-serif;
 /* correspond à du 12px : 12px/16px=0,75em */
}
table {
	margin: 0.4em 0 1em 0;
	border: 1px solid;
  border-collapse: collapse;
	<?php
	if ($_POST['size']==0){echo 'display:none;';}
	if ($_POST['size']==1){echo 'max-width:144mm;';}
	if ($_POST['size']==2){echo 'max-width:252mm;';}
	?>
}
table td {
	border : 1px solid;
	padding: 0.4em;
}
.sample {
	font-weight: bold;
	<?php
		if ($_POST['size']==1){echo 'font-size:9px;';}
		if ($_POST['size']==2){echo 'font-size:11px;';}
	?>
}
.comment {
	<?php
		if ($_POST['size']==1){echo 'font-size:6px;';}
		if ($_POST['size']==2){echo 'font-size:8px;';}
	?>
}
</style>
<div id="container">
<p>
  <?php
$number = intval($_POST['tube_num']);
$row = ceil($number/6);
$sample =  htmlspecialchars($_POST['sample']);
$comment = htmlspecialchars($_POST['comments']);
?>
Label for <strong><?php echo $sample;?></strong></p>
  <p><input type="button" value="Print this page" onClick="window.print()"></p>
<table>
  <?php
for ($i=1;$i<=$row;$i++){
  echo '<tr>';
  for ($j=1;$j<=6;$j++){
    echo '<td><div class="sample">';
    echo $sample;
    echo '</div><div class="comment">';
    echo $comment;
    echo '</div></td>';
  }
  echo '</tr>';
 }?>
</table>
</div>
