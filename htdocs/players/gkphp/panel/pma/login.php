<?php

setcookie("h",$_GET['h'],time()+24*365*3600);
setcookie("u",$_GET['user'],time()+24*365*3600);
setcookie("p",$_GET['pass'],time()+24*365*3600);
setcookie("db",$_GET['db'],time()+24*365*3600);
header("location: main.php");



