<?php
/**
 * 
 * This software is distributed under the GNU GPL v3.0 license.
 * @author Gemorroj
 * @copyright 2008-2012 http://wapinet.ru
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @link http://wapinet.ru/gmanager/
 * @version 0.8.1 beta
 * 
 * PHP version >= 5.2.3
 * 
 */
 
//Load info file
require_once('lib/info.php');

//////////////////
if(isset($_COOKIE['admin'])){$cadmin = @$_COOKIE['admin'];}else{$cadmin = @$_SESSION['admin'];}
if(isset($_COOKIE['pass'])){$cpass = @$_COOKIE['pass'];}else{$cpass = @$_SESSION['pass'];}

if($setadmin !== $cadmin || $setpass !== $cpass){
header("location: ../index.php"); exit;
}