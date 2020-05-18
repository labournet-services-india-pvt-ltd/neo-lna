function getQP(){
	var subsector = $("#id_subsector").val();
	var occupation = $("#id_occupation").val();
	$.ajax({
		type:"POST",
		url:M.cfg.wwwroot+"/local/assessment_test/ajax.php",
		data:{
			get_qp:'get_qp',
			subsector:subsector,
			occupation:occupation,
		},
		success: function( data ) {
			/*var list1 = '';
			list1 += '<option value="'+'select-all'+'">'+'Select All'+'</option>';
			for(var i in data){	
				list1 += '<option value="'+data[i].id+'">'+data[i].fullname+'</option>';
			}       
			$('#id_qpname').html(list1);*/
			var list1 = '';
			var objs=JSON.parse(data);
			list1 += '<option value="'+'selectqp'+'">'+'Select QP'+'</option>';
			//var dd = '<select name="dd" id="dd">'
			$.each(objs, function (index,item) {
				console.log(objs);
				list1 += '<option value=' + item.id + '>' + item.fullname + '</option>';
			});
			//dd+="</select>"
			$("#id_qpname").html(list1);
		},
	});
}
