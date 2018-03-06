<?php

include ('../info.php');
if(isset($_COOKIE['admin'])){$cadmin = @$_COOKIE['admin'];}else{$cadmin = @$_SESSION['admin'];}
if(isset($_COOKIE['pass'])){$cpass = @$_COOKIE['pass'];}else{$cpass = @$_SESSION['pass'];}
if($setadmin !== $cadmin || $setpass !== $cpass){
header("location: ../index.php"); exit;
}

echo $head;