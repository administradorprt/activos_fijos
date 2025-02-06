@extends('layouts.admin')
@section('contenido')
<div class="row">
	<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
		<h3>Editar Herramienta: {{$Herramientas->id}}</h3>
		@if(count($errors)>0)
			<div class="alert alert-danger">
				<ul>
					@foreach($errors ->all() as $error)
						<li>{{$error}}</li>
					@endforeach
				</ul>
			</div>
		@endif
		<form action="{{route('Herramientas.update',$Herramientas->id)}}" method="post" autocomplete="off" enctype="multipart/form-data">
			@method('PATCH')
			@csrf
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<label for="estado">Estado</label>
					<select name="estado" class="form-control" disabled="" ">
						@if($Herramientas->estado== 1)
							<option selected value="1">Activo </option>
							<option  value="0">Inactivo </option>
						@else
							<option  value="1">Activo </option>
							<option  selected value="0">Inactivo </option>
						@endif
					</select>
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<label for="descripcion">Descripción</label>
					<input type="text" name="descripcion" class="form-control" placeholder="Descripcion" value="{{$Herramientas->descripcion}}">
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<label for="marca">Marca</label>
					<input type="text" name="marca" class="form-control" placeholder="Marca" value="{{$Herramientas->marca}}">
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<label for="codigo">Código</label>
					<input type="text" name="codigo" class="form-control" placeholder="codigo" maxlength="17" required  value="{{$Herramientas->serie}}">
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<label for="modelo">Modelo</label>
					<input type="text" name="modelo" class="form-control" placeholder="Modelo" value="{{$Herramientas->modelo}}">
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<label for="medida">Medida</label>
					<input type="text" name="medida" class="form-control" placeholder="Medida" value="{{$Herramientas->medida}}">
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<label for="color">Color</label>
					<input type="text" name="color" class="form-control" placeholder="Color" value="{{$Herramientas->color}}">
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<label for="tipo">Tipo</label>
					<select name="tipo" class="form-control" value="8">
						@foreach($tipos as $tip )
							@if($tip->id_tipo== $Herramientas->tipo_id)
								<option selected value="{{$tip->id_tipo}}">{{$tip->nombre}} </option>
							@else
								<option value="{{$tip->id_tipo}}">{{$tip->nombre}} </option>
							@endif
						@endforeach
					</select>
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<label for="costo">Costo (sin IVA)</label>
					<input type="text" name="costo" class="form-control" placeholder="costo" value="{{$Herramientas->costo}}">
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<label for="nombre_provedor">Nombre del Proveedor</label>
					<input type="text" name="nombre_provedor" class="form-control" placeholder="nombre_provedor" value="{{$Herramientas->nombre_provedor}}">
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<label for="id_proveedor">ID del Proveedor</label>
					<input type="text" name="id_proveedor" class="form-control" placeholder="id_proveedor" value="{{$Herramientas->id_proveedor}}">
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<label for="numero_de_pedido">Número de pedido</label>
					<input type="text" name="numero_de_pedido" class="form-control" placeholder="numero_de_pedido" value="{{$Herramientas->numero_de_pedido}}">
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<label for="num_factura">Número de Factura</label>
					<input type="text" name="num_factura" class="form-control" placeholder="num_factura" value="{{$Herramientas->num_factura}}">
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<label for="fecha_compra">Fecha de Compra</label>
					<input type="date" name="fecha_compra" class="form-control" placeholder="fecha_compra" value="{{$Herramientas->fecha_compra}}">
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<label for="fecha_vida_util_inicio">fecha de inicio de vida util</label>
					<input type="date" name="fecha_vida_util_inicio" class="form-control" placeholder="fecha_vida_util_inicio" value="{{$Herramientas->fecha_vida_util_inicio}}">
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<label for="fecha_depreciacion_inicio">fecha de inicio de depreciación</label>
					<input type="date" name="fecha_depreciacion_inicio" class="form-control" placeholder="fecha_depreciacion_inicio" value="{{$Herramientas->fecha_depreciacion_inicio}}">
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<label for="sucursal_origen">Sucursal origen</label>
					<select name="sucursal_origen" class="form-control" >
						@foreach($sucursales as $sucursal )
							@if($sucursal->id_sucursal== $Herramientas->sucursal_id)
								<option selected value="{{$sucursal->id_sucursal}}">{{$sucursal->empresa->alias}} - {{$sucursal->nombre}} </option>
							@else
								<option value="{{$sucursal->id_sucursal}}">{{$sucursal->empresa->alias}} - {{$sucursal->nombre}} </option>
							@endif
						@endforeach
					</select>
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<label for="area_destinada">Área Destinada</label>
					<select name="area_destinada" class="form-control" >
						@foreach($Departamento as $dep )
							@if($dep->id_departamento== $Herramientas->departamento_id)
								<option selected value="{{$dep->id_departamento}}">{{$dep->nombre}} </option>
							@else
								<option value="{{$dep->id_departamento}}">{{$dep->nombre}} </option>
							@endif
						@endforeach
					</select>
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<label for="puesto">Puesto</label>
					<select name="puesto" class="form-control" >
						@foreach($Puesto as $pues )
							@if($pues->id_puesto== $Herramientas->puesto_id)
								<option selected value="{{$pues->id_puesto}}">{{$pues->nombre}} </option>
							@else
								<option value="{{$pues->id_puesto}}">{{$pues->nombre}} </option>
							@endif
						@endforeach
						
					</select>
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<label for="nombre_responsable">Nombre del Responsable</label>
					<select name="nombre_responsable" class="form-control" >
						@foreach($Empleado as $emp )
							@if($emp->id_empleado== $Herramientas->responsable_id)
								<option selected value="{{$emp->id_empleado}}"> {{$emp->apellido_p}} {{$emp->apellido_m}} {{$emp->nombres}}</option>
							@else
								<option value="{{$emp->id_empleado}}"> {{$emp->apellido_p}} {{$emp->apellido_m}} {{$emp->nombres}}</option>
							@endif
						@endforeach
						
					</select>
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<label for="carta_responsiva">Carta Responsiva</label>
					<input type="file" name="carta_responsiva" class="form-control" accept="application/pdf" >
					@if($Herramientas->carta_responsiva!="")
						<A HREF="{{asset('storage/archivos/inventario/Herramientas/carta_responsiva/'.$Herramientas->carta_responsiva)}}" target="_blank"><input type="button"  class="form-control" value="ver carta_responsiva"></A>
					@else
						<input type="text" class="form-control" value="no hay carta_responsiva" readonly="">
					@endif
				</div>
			</div>
			@if(auth()->user()->role_id == 1)
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<label for="tasa_depreciacion">Tasa de Depreciación</label>
					<input type="text" name="tasa_depreciacion" class="form-control" placeholder="tasa_depreciacion" value="{{$Herramientas->tasa_depreciacion}}">
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<label for="vida_util">Meses de Vida Util</label>
					<input type="text" name="vida_util" class="form-control" placeholder="vida_util" value="{{$Herramientas->vida_util}}">
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<label for="precio_venta">Precio de venta</label>
					<input type="text" name="precio_venta" class="form-control" placeholder="precio_venta" value="{{$Herramientas->precio_venta}}">
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<label for="fecha_baja">Fecha de Baja</label>
					<input type="date" name="fecha_baja" class="form-control" placeholder="fecha_baja" value="{{$Herramientas->fecha_baja}}">
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<label for="motivo_baja">Motivo de Baja</label>
					<input type="text" name="motivo_baja" class="form-control" placeholder="motivo_baja" value="{{$Herramientas->motivo_baja}}">
				</div>
			</div>
			@endif
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<label for="observaciones">Observaciones</label>
					<input type="text" name="observaciones" class="form-control" placeholder="observaciones" value="{{$Herramientas->observaciones}}">
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<label for="garantia">Garantia</label>
					<input type="text" name="garantia" class="form-control" placeholder="garantia" value="{{$Herramientas->garantia}}">
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<label for="estatus_depreciacion">Estado depreciación</label>
					<select name="estatus_depreciacion" class="form-control" >
						@foreach($Depreciacion as $estado_dep )
							@if($estado_dep->id_status== $Herramientas->estatus_depreciacion)
								<option selected value="{{$estado_dep->id_status}}">{{$estado_dep->nombre}} </option>
							@else
								<option value="{{$estado_dep->id_status}}">{{$estado_dep->nombre}} </option>
							@endif
						@endforeach
						
					</select>
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<label for="xml">Xml</label>
					<input type="file" name="xml" class="form-control" accept="application/xml" >
					@if($Herramientas->xml!="")
						<A HREF="{{asset('storage/archivos/inventario/Herramientas/xml/'.$Herramientas->xml)}}" target="_blank"><input type="button"  class="form-control" value="ver xml"></A>
					@else
					<input type="text"  class="form-control" value="no hay xml" readonly="">
					@endif
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<label for="pdf">Pdf</label>
					<input type="file" name="pdf" class="form-control" accept="application/pdf">
					@if($Herramientas->pdf!="")
						<A HREF="{{asset('storage/archivos/inventario/Herramientas/pdf/'.$Herramientas->pdf)}}" target="_blank"><input type="button"  class="form-control" value="ver pdf"></A>
					@else
					<input type="text" class="form-control" value="no hay pdf" readonly="">
					@endif
				</div>
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12	 col-xs-12">
				<div class="form-group">
					<label for="foto">Foto</label>
					<input type="file" name="foto[]" multiple="multiple" class="form-control" accept="image/jpeg,image/jpg,image/bmp,image/png">
				</div>
			</div>
			<div class="box-footer clearfix">
				<button type="button" class="pull-left btn btn-danger pr-2 btn_delete_img" id="btn_del_img_seminuevo"> Eliminar imagenes Selecionadas <i class="fa fa-times"></i></button>
			</div>
			<div class="alert alert-warning" role="alert" id="alert_del_img" hidden="">Procesando	...</div>	
			<div class="container">
				<div class="row ">
					@foreach($Imagen as $img )
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
							<div class="form-group">
								<div class="" style="margin-bottom: 1em; margin-right: 2em;">
									<div class="img_galery">
										<label class="checkeablee">
											<img class="upd_img figure-img img-fluid rounded img-thumbnail"  onClick="reply_click()" height="100px" width="100px" src="{{asset('storage/imagenes/inventario/Herramientas/img/'.$img->imagen)}}"/>
										</label>
										<input type="checkbox" class="chk_img_del_equip" value="{{$img->id_imagen}}" />
										
									</div>
								</div>
							</div>
						</div>
					@endforeach
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<button class="btn btn-primary" type="submit" >Actualizar</button>
					<a href="/admin/inventario/Herramientas"><button class="btn btn-danger" type="button" >Cancelar</button></a>
				</div>
			</div>
		</form>
	</div>
</div>
<div id="myModal" class="modal">
  <span class="close">&times;</span>
  <img class="modal-content" id="img01">
  <div id="caption"></div>
</div>
<style>
.checkeablee img {
  width: 100px;
  border: 5px solid transparent;
}
.img_galery{
  width: 120px;
  
}

/* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  padding-top: 100px; /* Location of the box */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.9); /* Black w/ opacity */
}

/* Modal Content (image) */
.modal-content {
  margin: auto;
  display: block;
  width: 80%;
  max-width: 700px;
}

/* Caption of Modal Image */
#caption {
  margin: auto;
  display: block;
  width: 80%;
  max-width: 700px;
  text-align: center;
  color: #ccc;
  padding: 10px 0;
  height: 150px;
}

/* Add Animation */
.modal-content, #caption {  
  -webkit-animation-name: zoom;
  -webkit-animation-duration: 0.6s;
  animation-name: zoom;
  animation-duration: 0.6s;
}

@-webkit-keyframes zoom {
  from {-webkit-transform:scale(0)} 
  to {-webkit-transform:scale(1)}
}

@keyframes zoom {
  from {transform:scale(0)} 
  to {transform:scale(1)}
}

/* The Close Button */
.close {
  position: absolute;
  top: 15px;
  right: 35px;
  color: #f1f1f1;
  font-size: 40px;
  font-weight: bold;
  transition: 0.3s;
}

.close:hover,
.close:focus {
  color: #bbb;
  text-decoration: none;
  cursor: pointer;
}

/* 100% Image Width on Smaller Screens */
@media only screen and (max-width: 700px){
  .modal-content {
    width: 100%;
  }
}
</style>


<script>/*
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

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks on <span> (x), close the modal
span.onclick = function() { 
    modal.style.display = "none";
}*/
</script>

@endsection 