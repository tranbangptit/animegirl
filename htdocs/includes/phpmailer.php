<?php
require_once "PHPMailer-master/PHPMailerAutoload.php";
$mail = new PHPMailer;
$mail->From = EMAIL_REPLY;
//Address to which recipient will reply
$mail->addReplyTo(EMAIL_REPLY, "Reply");
$mail->isHTML(true);

?>