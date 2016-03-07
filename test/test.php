<?php
error_reporting(E_ALL);

include '../src/koru.php';

$array = ['Hello' => 'Hibow',
          'Moon'  => 'Dalan'];
          
$array = Koru::build($array);          

 
          
exit(var_dump($array->Hello));

?>