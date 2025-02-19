	var modal = document.getElementById('myModal');
	var modalImg = document.getElementById("img01");
	var captionText = document.getElementById("caption");
	var reply_click = function()
	{
		//alert(event.srcElement.src);
		modal.style.display = "block";
		modalImg.src = event.srcElement.src;
		//captionText.innerHTML = this.alt;
		//alert("Button clicked, id "+this.id+", text"+this.innerHTML);
	}
	function recargar(){
		location.reload(true); 
	}
	let span = document.getElementsByClassName("close")[0];
	if (typeof span === 'undefined') {
	} 
	else {
		span.onclick = function() { 
			modal.style.display = "none";
		}
	} 
	//function para cerrar el modal en los nuevos modulos
	const closeModal=()=>{
		modal.style.display = "none";
	};
	
//borrar imagenes Herramientas
$('.btn_delete_img').click(function(){
	let _token= document.getElementsByName('_token');
	total = $(".chk_img_del_equip:checkbox:checked").length;
	if(total>0){
		formData = new FormData();
		$(".chk_img_del_equip:checkbox:checked").each(function( index ) {
			id=$(this).attr("value");
			formData.append('_token',_token[0].value); 
			formData.append('id_imagen_'+index,id); 
		});
		alertify.confirm('Eliminar Imagenes ', 'Estas a punto de eliminar completamente las imagenes seleccionadas ¿deseas continuar? ', function(){
			$("#alert_del_img").removeAttr("hidden");
			$.ajax({
				url: '/admin/inventario/Herramientas/borrar_img',
				data: formData,
				type: 'POST',
				cache: false,
				contentType: false,
				processData: false
			}).done(function(data){
				var rs =data.trim();
				console.log(rs);
				if(rs==true){
					$("#alert_del_img").attr("hidden", "");
					alertify.success("Se ha eliminado correctamente");
					setTimeout(recargar, 1300);
				}else{
					alertify.error(rs);
					$("#alert_del_img").attr("hidden", "");
					console.log(rs);
				}
			}).fail(function(data){
				var ss =data;
				console.log(ss);
				$("#alert_del_img").attr("hidden", "");
			});
		}, function(){
		});
	}else{
		alertify.alert('Error', 'Debe seleccionar al menos una imagen');
		
	}
});
//borrar imagenes transporte
$('.btn_delete_img_tra').click(function(){
	let _token= document.getElementsByName('_token');
	total = $(".chk_img_del_equip:checkbox:checked").length;
	if(total>0){
		formData = new FormData();
		$(".chk_img_del_equip:checkbox:checked").each(function( index ) {
			id=$(this).attr("value");
			formData.append('_token',_token[0].value); 
			formData.append('id_imagen_'+index,id); 
		});
		alertify.confirm('Eliminar Imagenes ', 'Estas a punto de eliminar completamente las imagenes seleccionadas ¿deseas continuar? ', function(){
			$("#alert_del_img").removeAttr("hidden");
			$.ajax({
				url: '/admin/inventario/EquipoTransporte/borrar_img',
				data: formData,
				type: 'POST',
				cache: false,
				contentType: false,
				processData: false
			}).done(function(data){
				var rs =data.trim();
				console.log(rs);
				if(rs==true){
					$("#alert_del_img").attr("hidden", "");
					alertify.alert("Se ha eliminado correctamente");
					setTimeout(recargar, 1300);
				}else{
					alertify.alert(rs);
					$("#alert_del_img").attr("hidden", "");
					console.log(rs);
				}
			}).fail(function(data){
				var ss =data;
				console.log(ss);
				$("#alert_del_img").attr("hidden", "");
			});
		}, function(){
		});
	}else{
		alertify.alert('Error', 'Debe seleccionar al menos una imagen');
		
	}
});
//borrar imagenes EquipoComputo
$('.btn_delete_img_comp').click(function(){
	let _token= document.getElementsByName('_token');
	total = $(".chk_img_del_equip:checkbox:checked").length;
	if(total>0){
		formData = new FormData();
		$(".chk_img_del_equip:checkbox:checked").each(function( index ) {
			id=$(this).attr("value");
			formData.append('_token',_token[0].value); 
			formData.append('id_imagen_'+index,id); 
		});
		alertify.confirm('Eliminar Imagenes ', 'Estas a punto de eliminar completamente las imagenes seleccionadas ¿deseas continuar? ', function(){
			$("#alert_del_img").removeAttr("hidden");
			$.ajax({
				url: '/admin/inventario/EquipoComputo/borrar_img',
				data: formData,
				type: 'POST',
				cache: false,
				contentType: false,
				processData: false
			}).done(function(data){
				var rs =data.trim();
				console.log(rs);
				if(rs==true){
					$("#alert_del_img").attr("hidden", "");
					alertify.success("Se ha eliminado correctamente");
					setTimeout(recargar, 1300);
				}else{
					alertify.error(rs);
					$("#alert_del_img").attr("hidden", "");
					console.log(rs);
				}
			}).fail(function(data){
				var ss =data;
				console.log(ss);
				$("#alert_del_img").attr("hidden", "");
			});
		}, function(){
		});
	}else{
		alertify.alert('Error', 'Debe seleccionar al menos una imagen');
		
	}
});
//borrar imagenes MobiliarioEquipo
$('.btn_delete_img_mob').click(function(){
	let _token= document.getElementsByName('_token');
	total = $(".chk_img_del_equip:checkbox:checked").length;
	if(total>0){
		formData = new FormData();
		$(".chk_img_del_equip:checkbox:checked").each(function( index ) {
			id=$(this).attr("value");
			formData.append('_token',_token[0].value); 
			formData.append('id_imagen_'+index,id); 
		});
		alertify.confirm('Eliminar Imagenes ', 'Estas a punto de eliminar completamente las imagenes seleccionadas ¿deseas continuar? ', function(){
			$("#alert_del_img").removeAttr("hidden");
			$.ajax({
				url: '/admin/inventario/MobiliarioEquipo/borrar_img',
				data: formData,
				type: 'POST',
				cache: false,
				contentType: false,
				processData: false
			}).done(function(data){
				var rs =data.trim();
				console.log(rs);
				if(rs==true){
					$("#alert_del_img").attr("hidden", "");
					alertify.success("Se ha eliminado correctamente");
					setTimeout(recargar, 1300);
				}else{
					alertify.error(rs);
					$("#alert_del_img").attr("hidden", "");
					console.log(rs);
				}
			}).fail(function(data){
				var ss =data;
				console.log(ss);
				$("#alert_del_img").attr("hidden", "");
			});
		}, function(){
		});
	}else{
		alertify.alert('Error', 'Debe seleccionar al menos una imagen');
		
	}
});
//borrar imagenes MaquinariaEquipo
$('.btn_delete_img_maq').click(function(){
	let _token= document.getElementsByName('_token');
	total = $(".chk_img_del_equip:checkbox:checked").length;
	if(total>0){
		formData = new FormData();
		$(".chk_img_del_equip:checkbox:checked").each(function( index ) {
			id=$(this).attr("value");
			formData.append('_token',_token[0].value); 
			formData.append('id_imagen_'+index,id); 
		});
		alertify.confirm('Eliminar Imagenes ', 'Estas a punto de eliminar completamente las imagenes seleccionadas ¿deseas continuar? ', function(){
			$("#alert_del_img").removeAttr("hidden");
			$.ajax({
				url: '/admin/inventario/MaquinariaEquipo/borrar_img',
				data: formData,
				type: 'POST',
				cache: false,
				contentType: false,
				processData: false
			}).done(function(data){
				var rs =data.trim();
				console.log(rs);
				if(rs==true){
					$("#alert_del_img").attr("hidden", "");
					alertify.success("Se ha eliminado correctamente");
					setTimeout(recargar, 1300);
				}else{
					alertify.error(rs);
					$("#alert_del_img").attr("hidden", "");
					console.log(rs);
				}
			}).fail(function(data){
				var ss =data;
				console.log(ss);
				$("#alert_del_img").attr("hidden", "");
			});
		}, function(){
		});
	}else{
		alertify.alert('Error', 'Debe seleccionar al menos una imagen');
		
	}
});
//Subir excel de carga activos transporte
$('#btn_subir_excel_masivo_trans').click(function(){
	var formData = new FormData(document.getElementById("frm_up_xls_multi_trans"));	
	formData.append("dato", "valor");
	var dato_archivo = $('#archivo_excel').prop("files")[0];
	if(dato_archivo==undefined){
		alertify.alert('Error', 'Debe seleccionar un archivo');
	}else{
		$('#btn_subir_excel_masivo_trans').hide();
		alertify.confirm('Subir archivo', 'Estas a punto de cargar activos mediante un archivo ¿deseas continuar? ', function(){
			$.ajax({
				url: '/admin/inventario/EquipoTransporte/importar_transportes',
				data: formData,
				type: 'POST',
				cache: false,
				contentType: false,
				processData: false
			}).done(function(data){
				console.log(data[0]);
				if(data[0]==true){
					response_titulo='Archivo Procesado';
					document.querySelector("#archivo_excel").value='';
				}else{
					console.log(data[1]);
					response_titulo='Error';
				}
				alertify.alert(response_titulo, data[1]);
				$('#btn_subir_excel_masivo_trans').show();
			}).fail(function(data){
				var ss =data;
				console.log(ss);
				$('#btn_subir_excel_masivo_trans').show();
			});
		}, function(){
			$('#btn_subir_excel_masivo_trans').show();
		});
	}
});
//Subir excel de carga activos computo
$('#btn_subir_excel_masivo_compu').click(function(){
	var formData = new FormData(document.getElementById("frm_up_xls_multi_compu"));	
	formData.append("dato", "valor");
	var dato_archivo = $('#archivo_excel').prop("files")[0];
	if(dato_archivo==undefined){
		alertify.alert('Error', 'Debe seleccionar un archivo');
	}else{
		$('#btn_subir_excel_masivo_compu').hide();
		alertify.confirm('Subir archivo', 'Estas a punto de cargar activos mediante un archivo ¿deseas continuar? ', function(){
			$.ajax({
				url: '/admin/inventario/EquipoComputo/importar_computo',
				data: formData,
				type: 'POST',
				cache: false,
				contentType: false,
				processData: false
			}).done(function(data){
				console.log(data[0]);
				if(data[0]==true){
					response_titulo='Archivo Procesado';
					document.querySelector("#archivo_excel").value='';
				}else{
					console.log(data[1]);
					response_titulo='Error';
				}
				alertify.alert(response_titulo, data[1]);
				$('#btn_subir_excel_masivo_compu').show();
			}).fail(function(data){
				var ss =data;
				console.log(ss);
				$('#btn_subir_excel_masivo_compu').show();
			});
		}, function(){
			$('#btn_subir_excel_masivo_compu').show();
		});
	}
});
//Subir excel de carga activos mobiliario
$('#btn_subir_excel_masivo_mobiliario').click(function(){
	var formData = new FormData(document.getElementById("frm_up_xls_multi_mobiliario"));	
	formData.append("dato", "valor");
	var dato_archivo = $('#archivo_excel').prop("files")[0];
	if(dato_archivo==undefined){
		alertify.alert('Error', 'Debe seleccionar un archivo');
	}else{
		$('#btn_subir_excel_masivo_mobiliario').hide();
		alertify.confirm('Subir archivo', 'Estas a punto de cargar activos mediante un archivo ¿deseas continuar? ', function(){
			$.ajax({
				url: '/admin/inventario/EquipoMobiliario/importar_mobiliario',
				data: formData,
				type: 'POST',
				cache: false,
				contentType: false,
				processData: false
			}).done(function(data){
				console.log(data[0]);
				if(data[0]==true){
					response_titulo='Archivo Procesado';
					document.querySelector("#archivo_excel").value='';
				}else{
					console.log(data[1]);
					response_titulo='Error';
				}
				alertify.alert(response_titulo, data[1]);
				$('#btn_subir_excel_masivo_mobiliario').show();
			}).fail(function(data){
				var ss =data;
				console.log(ss);
				$('#btn_subir_excel_masivo_mobiliario').show();
			});
		}, function(){
			$('#btn_subir_excel_masivo_mobiliario').show();
		});
	}
});
//Subir excel de carga activos maquinaria
$('#btn_subir_excel_masivo_maquinaria').click(function(){
	var formData = new FormData(document.getElementById("frm_up_xls_multi_maquinaria"));	
	formData.append("dato", "valor");
	var dato_archivo = $('#archivo_excel').prop("files")[0];
	if(dato_archivo==undefined){
		alertify.alert('Error', 'Debe seleccionar un archivo');
	}else{
		$('#btn_subir_excel_masivo_maquinaria').hide();
		alertify.confirm('Subir archivo', 'Estas a punto de cargar activos mediante un archivo ¿deseas continuar? ', function(){
			$.ajax({
				url: '/admin/inventario/EquipoMaquinaria/importar_maquinaria',
				data: formData,
				type: 'POST',
				cache: false,
				contentType: false,
				processData: false
			}).done(function(data){
				console.log(data[0]);
				if(data[0]==true){
					response_titulo='Archivo Procesado';
					document.querySelector("#archivo_excel").value='';
				}else{
					console.log(data[1]);
					response_titulo='Error';
				}
				alertify.alert(response_titulo, data[1]);
				$('#btn_subir_excel_masivo_maquinaria').show();
			}).fail(function(data){
				var ss =data;
				console.log(ss);
				$('#btn_subir_excel_masivo_maquinaria').show();
			});
		}, function(){
			$('#btn_subir_excel_masivo_maquinaria').show();
		});
	}
});
//Subir excel de carga activos herramientas
$('#btn_subir_excel_masivo_herramientas').click(function(){
	var formData = new FormData(document.getElementById("frm_up_xls_multi_herramientas"));	
	let _token= document.getElementsByName('_token');
	formData.append("_token", _token[0].value);
	formData.append("dato", "valor");
	var dato_archivo = $('#archivo_excel').prop("files")[0];
	if(dato_archivo==undefined){
		alertify.alert('Error', 'Debe seleccionar un archivo');
	}else{
		$('#btn_subir_excel_masivo_herramientas').hide();
		alertify.confirm('Subir archivo', 'Estas a punto de cargar activos mediante un archivo ¿deseas continuar? ', function(){
			$.ajax({
				url: '/admin/inventario/EquipoHeramienta/importar_heramienta',
				data: formData,
				type: 'POST',
				cache: false,
				contentType: false,
				processData: false
			}).done(function(data){
				console.log(data[0]);
				if(data[0]==true){
					response_titulo='Archivo Procesado';
					document.querySelector("#archivo_excel").value='';
				}else{
					console.log(data[1]);
					response_titulo='Error';
				}
				alertify.alert(response_titulo, data[1]);
				$('#btn_subir_excel_masivo_herramientas').show();
			}).fail(function(data){
				var ss =data;
				console.log(ss);
				$('#btn_subir_excel_masivo_herramientas').show();
			});
		}, function(){
			$('#btn_subir_excel_masivo_herramientas').show();
		});
	}
});