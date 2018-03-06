var ischecked = false;
function CheckAll(chk)
{
if(ischecked == false){
for (i = 0; i < chk.length; i++)
chk[i].checked = true ;
ischecked = true;
}else {
for (i = 0; i < chk.length; i++)
chk[i].checked = false ;
ischecked = false;
}
}




function docheck(status,from_){
	var alen=document.media_list.checkbox.length;
	cb = document.media_list.checkbox;
	if (alen>0)
	{
		for(var i=0;i<alen;i++)
			if(document.media_list.checkbox[i].disabled==false)
				document.media_list.checkbox[i].checked=status;
	}
	else
		if(cb.disabled==false)
			cb.checked=status;
	if(from_>0)
		document.media_list.chkall.checked=status;
}

function docheckone(id){
	var alen=document.media_list.checkbox.length;
	var isChecked=true;
	if (alen>0){
		for(var i=0;i<alen;i++){
			if(document.media_list.checkbox[i].checked==false){
				isChecked=false;
			}
		}
	}else{
		if(document.media_list.checkbox.checked==false){
			isChecked=false;
		}
	}				
	document.media_list.chkall.checked=isChecked;
}
function check_checkbox()
{
	var alen=document.media_list.checkbox.length;
	var isChecked=false;
	if (alen>0) {
		for(var i=0;i<alen;i++)
			if(document.media_list.checkbox[i].checked==true) isChecked=true;
	}
	else {
		if(document.media_list.checkbox.checked==true) isChecked=true;
	}
	if (!isChecked){
		alert("Bạn chưa chọn");
	}
	else if (confirm('Bạn có chắc chắn muốn thực hiện không ?')) return true;
		else return false;
	return isChecked;
}
function film_publish(id)
{
	if(id=="") return;
	$("#publish_"+id).val("...");
	$.ajax({
			url:'publish.php',type:"GET",cache:true,dataType:'json',data:{"film_id":id},
			success:function(res)
			{
				if (res.message)  alert(res.message)
				else alert("Số lượng Blog đã publish được: "+ res.count+"\r\n"+"Những địa chỉ blog chưa post được: "+ res.cantpost);
				$("#publish_"+id).val("Done");
			},
			error:function(error){
				alert("Lỗi hệ thống. Xin thử lại");
			}
		});
		return false;
}
