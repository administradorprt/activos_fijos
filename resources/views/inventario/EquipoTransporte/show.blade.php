@extends('layouts.admin')
@section('contenido')
<div class="row">
	<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
		<h3>Equipo de Transporte: {{$EquipoTransporte->id}}</h3>
		@if(count($errors)>0)
			<div class="alert alert-danger">
				<ul>
					@foreach($errors ->all() as $error)
					<li>{{$error}}</li>
					@endforeach
				</ul>
			</div>
		@endif
		<form action="{{route('EquipoTransporte.show',$EquipoTransporte->id)}}" method="get">
			@csrf
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<label for="estado">Estado</label>
					<input type="text" name="estado" class="form-control" placeholder="estado" 
					value="@if($EquipoTransporte->estado==1)Activo
					@else Inactivo
					@endif" readonly="">
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<label for="descripcion">Descripción</label>
					<textarea class="form-control" name="descripcion"  cols="30" rows="5" readonly="">{{$EquipoTransporte->descripcion}}</textarea>
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<label for="marca">Marca</label>
					<input type="text" name="marca" class="form-control" placeholder="Marca" value="{{$EquipoTransporte->marca}}" readonly="">
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<label for="vin">Vin</label>
					<input type="text" name="vin" class="form-control" placeholder="Vin" maxlength="17" required  value="{{$EquipoTransporte->serie}}" readonly="">
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<label for="modelo">Modelo</label>
					<input type="text" name="modelo" class="form-control" placeholder="Modelo" value="{{$EquipoTransporte->modelo}}" readonly="">
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<label for="num_motor">Numero de Motor</label>
					<input type="text" name="num_motor" class="form-control" placeholder="Numero de Motor" value="{{$EquipoTransporte->num_motor}}" readonly="">
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<label for="color">Color</label>
					<input type="text" name="color" class="form-control" placeholder="Color" value="{{$EquipoTransporte->color}}" readonly="">
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<label for="tipo">Tipo</label>
					<select name="tipo" class="form-control"  disabled="">
						@foreach($tipos as $tip )
							@if($tip->id_tipo== $EquipoTransporte->tipo_id)
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
					<input type="text" name="costo" class="form-control" placeholder="costo" value="{{$EquipoTransporte->costo}}" readonly="">
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<label for="nombre_provedor">Nombre del Provedor</label>
					<input type="text" name="nombre_provedor" class="form-control" placeholder="nombre_provedor" value="{{$EquipoTransporte->nombre_provedor}}" readonly="">
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<label for="id_proveedor">id proveedor</label>
					<input type="text" name="id_proveedor" class="form-control" placeholder="id_proveedor" value="{{$EquipoTransporte->id_proveedor}}" readonly="">
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<label for="numero_de_pedido">Número de pedido</label>
					<input type="text" name="numero_de_pedido" class="form-control" placeholder="numero_de_pedido" value="{{$EquipoTransporte->numero_de_pedido}}" readonly="">
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<label for="num_factura">Número de Factura</label>
					<input type="text" name="num_factura" class="form-control" placeholder="num_factura" value="{{$EquipoTransporte->num_factura}}" readonly="">
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<label for="fecha_compra">Fecha de Compra</label>
					<input type="date" name="fecha_compra" class="form-control" placeholder="fecha_compra" value="{{$EquipoTransporte->fecha_compra}}" readonly="">
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<label for="fecha_vida_util_inicio">fecha de inicio de vida util</label>
					<input type="date" name="fecha_vida_util_inicio" class="form-control" placeholder="fecha_vida_util_inicio" value="{{$EquipoTransporte->fecha_vida_util_inicio}}" readonly="">
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<label for="fecha_depreciacion_inicio">fecha de inicio de depreciacion</label>
					<input type="date" name="fecha_depreciacion_inicio" class="form-control" placeholder="fecha_depreciacion_inicio" value="{{$EquipoTransporte->fecha_depreciacion_inicio}}" readonly="">
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<label for="xml">Xml</label>
					<!--<input type="file" name="xml" class="form-control" >-->
					@if($EquipoTransporte->xml!="")
						<A HREF="{{asset('storage/archivos/inventario/EquipoTransporte/xml/'.$EquipoTransporte->xml)}}" target="_blank"><input type="button" name="xml" class="form-control" value="ver xml"></A>
					@else
					<input type="text" name="xml" class="form-control" value="no hay xml" readonly="">
					@endif
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<label for="pdf">Pdf</label>
					@if($EquipoTransporte->pdf!="")
						
						<A HREF="{{asset('storage/archivos/inventario/EquipoTransporte/pdf/'.$EquipoTransporte->pdf)}}" target="_blank"><input type="button" name="pdf" class="form-control" value="ver PDF"></A>
					@else
					<input type="text" name="pdf" class="form-control" value="no hay pdf" readonly="">
					@endif
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<label for="tasa_depreciacion">Tasa de Depreciacion</label>
					<input type="text" name="tasa_depreciacion" class="form-control" placeholder="tasa_depreciacion" value="{{$EquipoTransporte->tasa_depreciacion}}" readonly="">
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<label for="vida_util">Meses de Vida Util</label>
					<input type="text" name="vida_util" class="form-control" placeholder="vida_util" value="{{$EquipoTransporte->vida_util}}" readonly="">
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<label for="sucursal_origen">Sucursal origen</label>
					<input type="text" name="sucursal_origen" class="form-control" placeholder="sucursal_origen" value="{{$EquipoTransporte->sucursal->empresa->alias}} - {{$EquipoTransporte->sucursal->nombre}}" readonly="">
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<label for="area_destinada">Area Destinada</label>
					@foreach($Departamento as $dep )
						@if($dep->id_departamento== $EquipoTransporte->departamento_id)
						<input type="text" name="area_destinada" class="form-control" placeholder="area_destinada" value="{{$dep->nombre}}" readonly="">
						@endif
					@endforeach
						
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<label for="puesto">Puesto</label>
					@foreach($Puesto as $pues )
						@if($pues->id_puesto== $EquipoTransporte->puesto_id)
						<input type="text" name="puesto" class="form-control" placeholder="puesto" value="{{$pues->nombre}} " readonly="">
						
						@endif
					@endforeach
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<label for="nombre_responsable">Nombre del Responsable</label>
					@foreach($Empleado as $Emp )
						@if($Emp->id_empleado== $EquipoTransporte->responsable_id)
						<input type="text" name="nombre_responsable" class="form-control" placeholder="nombre_responsable" value="{{$Emp->nombres}} {{$Emp->apellido_p}} {{$Emp->apellido_m}}" readonly="">
						@endif
					@endforeach
				</div>
			</div>		
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<label for="fecha_baja">Fecha de Baja</label>
					<input type="date" name="fecha_baja" class="form-control" placeholder="fecha_baja" value="{{$EquipoTransporte->fecha_baja}}" readonly="">
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<label for="motivo_baja">Motivo de Baja</label>
					<input type="text" name="motivo_baja" class="form-control" placeholder="motivo_baja" value="{{$EquipoTransporte->motivo_baja}}" readonly="">
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<label for="observaciones">Observaciones</label>
					<input type="text" name="observaciones" class="form-control" placeholder="observaciones" value="{{$EquipoTransporte->observaciones}}" readonly="">
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<label for="garantia">Garantía</label>
					<input type="text" name="garantia" class="form-control" placeholder="garantia" value="{{$EquipoTransporte->garantia}}" readonly="">
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<label for="carta_responsiva">Carta Responsiva</label>
					@if($EquipoTransporte->carta_responsiva!="")	
						<A HREF="{{asset('storage/archivos/inventario/EquipoTransporte/carta_responsiva/'.$EquipoTransporte->carta_responsiva)}}" target="_blank"><input type="button" name="carta_responsiva" class="form-control" value="ver carta responsiva"></A>
					@else
					<input type="text" name="carta_responsiva" class="form-control" value="no hay carta responsiva" readonly="">
					@endif
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<label for="precio_venta">precio de venta</label>
					<input type="text" name="precio_venta" class="form-control" placeholder="precio_venta" value="{{$EquipoTransporte->precio_venta}}" readonly="">
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<label for="estatus_depreciacion">estado de depreciacion</label>
					<input type="text" name="estatus_depreciacion" class="form-control" placeholder="estatus_depreciacion" value="{{$EquipoTransporte->estadodepreciacion->nombre}}" readonly="">
				</div>
			</div>
			<div class="container">
				<div class="row ">
					@foreach($Imagen as $img )
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
							<div class="form-group">
								<div class="" style="margin-bottom: 1em; margin-right: 2em;">
									<div class="img_galery">
										<label class="checkeablee">
											<img class="upd_img figure-img img-fluid rounded img-thumbnail"  onClick="reply_click()" height="100px" width="100px" src="{{asset('imagenes/inventario/EquipoTransporte/img/'.$img->imagen)}}"/>
										</label>
									</div>
								</div>
							</div>
						</div>
					@endforeach
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<a href="/admin/inventario/EquipoTransporte"><button class="btn btn-danger" type="button" >Atras</button></a>
				</div>
				<div class="form-group">
					<a href="/admin/inventario/EquipoTransporte/Responsiva/{{$EquipoTransporte->id}}" target="_blank"><button class="btn btn-success" type="button" >Imprimir Responsiva</button></a>
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
@endsection 