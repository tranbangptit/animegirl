<?php
// wap phpmyadmin
// ionutvmi@gmail.com
// master-land.net

setcookie("host","localhost",time()+24*365*3600);
echo $_COOKIE['host'];
if(!$link) :

echo $_err ? "<div class='notice'>".pma_img('s_error.png')." $lang->Error : $_msg !</div>" : ""; 
?>
<form action='?' class='center'>
<b> <?php echo $lang->Database_Server;?>: </b> <br/> <input type='text' name='host' value='<? echo $_COOKIE['h'];?>'/><br/>
<b> <?php echo $lang->Database_User;?>: </b> <br/> <input type='text' name='user' value='<? echo $_COOKIE['u'];?>'><br/>
<b> <?php echo $lang->Database_Password;?>: </b> <br/> <input type='password' name="pass" value='<? echo $_COOKIE['p'];?>'><br/>
<b> <?php echo $lang->Database_Name;?>: </b> <br/> <input type='text' name="db" value='<? echo $_COOKIE['db'];?>'><br/>
<br/><input type='submit' value='<?php echo $lang->Go;?>'>
</form>
<?php 
else: 

echo pma_img('s_success.png')."<br/>".$lang->WELCOME." <b><i>".strtoupper($_SESSION['user']); ?></i></b><br/><br/>

&#187; <a href='login.php?h=<? echo $_GET['host'];?>&user=<? echo $_GET['user'];?>&pass=<? echo $_GET['pass'];?>&db=<? echo $_GET['db'];?>'> <?php echo $lang->ENTER; ?> </a> &#171;
<br/><br/>
<?php
echo $lang->Bookmark;
 endif ?>