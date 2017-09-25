function show_goods(id,my){
	if(my == 1){
		$("#goods_name"+id).show();
		$("#goods_name"+id).focus(); //获取光标
		$("#goods_names"+id).hide();
	}else if(my == 2){
		$("#goods_sn"+id).show();
		$("#goods_sn"+id).focus(); //获取光标
		$("#goods_sns"+id).hide();
	}else if(my == 3){
		$("#market_price"+id).show();
		$("#market_price"+id).focus(); //获取光标
		$("#market_prices"+id).hide();
	}else if(my == 4){
		$("#sort_order"+id).show();
		$("#sort_order"+id).focus(); //获取光标
		$("#sort_orders"+id).hide();
	}else{
		$("#goods_number"+id).show();
		$("#goods_number"+id).focus(); //获取光标
		$("#goods_numbers"+id).hide();
	}
}
function update_goods(goods_id,my){
	if(my == 1){
		var father=$("#goods_name"+goods_id).val();
		var son=$("#goods_names"+goods_id).html();
		if(father==son){
			$("#goods_name"+goods_id).hide();
			$("#goods_names"+goods_id).show();
			return false;
		}
	}else if(my == 2){
		var father=$("#goods_sn"+goods_id).val();
		var son=$("#goods_sns"+goods_id).html();
		if(father==son){
			$("#goods_sn"+goods_id).hide();
			$("#goods_sns"+goods_id).show();
			return false;
		}
	}else if(my == 3){
		var father=$("#market_price"+goods_id).val();
		var son=$("#market_prices"+goods_id).html();
		if(father==son){
			$("#market_price"+goods_id).hide();
			$("#market_prices"+goods_id).show();
			return false;
		}
	}else if(my == 4){
		var father=$("#sort_order"+goods_id).val();
		var son=$("#sort_orders"+goods_id).html();
		if(father==son){
			$("#sort_order"+goods_id).hide();
			$("#sort_orders"+goods_id).show();
			return false;
		}
	}else{
		var father=$("#goods_number"+goods_id).val();
		var son=$("#goods_numbers"+goods_id).html();
		if(father==son){
			$("#goods_number"+goods_id).hide();
			$("#goods_numbers"+goods_id).show();
			return false;
		}
	}

	$.ajax({
		url:"/manage/goods/updateNumber",
		data:{"goods_id":goods_id,"my":my,"father":father},
		type:"post",
		success:function(e){
			if(e==1){
				$("#goods_names"+goods_id).html(father);
				$("#goods_names"+goods_id).show();
				$("#goods_name"+goods_id).hide();
			}else if(e==2){
				$("#goods_sns"+goods_id).html(father);
				$("#goods_sns"+goods_id).show();
				$("#goods_sn"+goods_id).hide();
			}else if(e==0){
				alert("商品编号不可重复！！！");
				$("#goods_sns"+goods_id).html(son);
				$("#goods_sns"+goods_id).show();
				$("#goods_sn"+goods_id).hide();
			}else if(e==3){
				$("#market_prices"+goods_id).html(father);
				$("#market_prices"+goods_id).show();
				$("#market_price"+goods_id).hide();
			}else if(e==4){
				$("#sort_orders"+goods_id).html(father);
				$("#sort_orders"+goods_id).show();
				$("#sort_order"+goods_id).hide();
			}else if(e==5){
				$("#goods_numbers"+goods_id).html(father);
				$("#goods_numbers"+goods_id).show();
				$("#goods_number"+goods_id).hide();
			}else{
				alert("出现问题，请联系NB管理员！！！");
			}
		}
	});

}

function update_price(goods_id,now,my,role,rec){
	if(role ==1 && now == 2){
		alert('正在审核中，请耐心等待1-2天');
		return;
	}
    if(now == 3){
        alert(rec);
        return;
    }
	if(my == 1){
		if($("#best"+goods_id).attr("class")=="menu-icon glyphicon glyphicon-ok"){
			$("#best"+goods_id).attr("class","menu-icon glyphicon glyphicon-remove");
		}else{
			$("#best"+goods_id).attr("class","menu-icon glyphicon glyphicon-ok");
		}
	}else if(my == 2){
		if($("#new"+goods_id).attr("class")=="menu-icon glyphicon glyphicon-ok"){
			$("#new"+goods_id).attr("class","menu-icon glyphicon glyphicon-remove");
		}else{
			$("#new"+goods_id).attr("class","menu-icon glyphicon glyphicon-ok");
		}
	}else if(my == 3){
		if($("#hot"+goods_id).attr("class")=="menu-icon glyphicon glyphicon-ok"){
			$("#hot"+goods_id).attr("class","menu-icon glyphicon glyphicon-remove");
		}else{
			$("#hot"+goods_id).attr("class","menu-icon glyphicon glyphicon-ok");
		}
	}else if(my == 6){
		if($("#examine"+goods_id).attr("class")=="menu-icon glyphicon glyphicon-ok"){
			$("#examine"+goods_id).attr("class","menu-icon glyphicon glyphicon-remove");
		}else{
			$("#examine"+goods_id).attr("class","menu-icon glyphicon glyphicon-ok");
		}
	}else{
		if($("#sale"+goods_id).attr("class")=="menu-icon glyphicon glyphicon-ok"){
			$("#sale"+goods_id).attr("class","menu-icon glyphicon glyphicon-remove");
		}else{
			$("#sale"+goods_id).attr("class","menu-icon glyphicon glyphicon-ok");
		}
	}
   $.ajax({
		url:"/manage/goods/updateStatus",
		data:{"goods_id":goods_id,"now":now,"my":my},
		type:"get"
	})
}
