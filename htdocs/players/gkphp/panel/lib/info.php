<?php
//////////////////////////////////////////////////////
//			Gmanager mod by Tmc
//			File này lưu trử thông tin đăng nhập
//			Sửa cận thận
//			Mật khẩu phải mã hoá md5 2 lần trước khi viết vào
//////////////////////////////////////////////////////
//Tên đăng nhập
$setadmin = "Tmc";
//Mật khẩu được mã hoá
$setpass = "14e1b600b1fd579f47433b88e8d85291";
//số dòng chỉnh sửa
$setdong = "50";
//Công cụ sửa văn bản
$setedit = "t";
//Thời gian thực thi sửa văn bản
setcookie("edit",$setedit,time()+24*365*3600);
//Tmc - fb.com/xtmc9x
