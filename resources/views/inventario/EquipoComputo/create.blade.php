@extends('layouts.admin')
@section('contenido')
<div class="row">
	<div class="col-lg-10 col-md-10 col-sm-6 col-xs-12">
		<h3>Nuevo Equipo de Computo</h3>
		@if(count($errors)>0)
		<div class="alert alert-danger">
			<ul>
				@foreach($errors ->all() as $error)
				<li>{{$error}}</li>
				@endforeach
			</ul>
		</div>
		@endif
		<form action="{{route('EquipoComputo.store')}}" method="post" autocomplete="off" enctype="multipart/form-data">
			@csrf
			<div class="row">
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<div class="form-group">
						<label for="descripcion">Descripción</label>
						<input type="text" name="descripcion" class="form-control" placeholder="Descripcion" required value="{{old('descripcion')}}">
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<div class="form-group">
						<label for="marca">Marca</label>
						<input type="text" name="marca" class="form-control" placeholder="Marca" required value="{{old('marca')}}">
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<div class="form-group">
						<label for="serie">Número de serie</label>
						<input type="text" name="serie" class="form-control" placeholder="serie"  required value="{{old('serie')}}">
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<div class="form-group">
						<label for="modelo">Modelo</label>
						<input type="text" name="modelo" class="form-control" placeholder="Modelo" required value="{{old('modelo')}}">
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<div class="form-group">
						<label for="color">Color</label>
						<input type="text" name="color" class="form-control" placeholder="Color" required value="{{old('color')}}">
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<div class="form-group">
						<label for="tipo">Tipo</label>
						<select name="tipo" class="form-control" >
							@foreach($tipos as $tip )
								<option value="{{$tip->id_tipo}}">{{$tip->nombre}} </option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<div class="form-group">
						<label for="costo">Costo (sin IVA)</label>
						<input type="text" name="costo" class="form-control" placeholder="costo" value="{{old('costo')}}" required>
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<div class="form-group">
						<label for="nombre_provedor">Nombre del Proveedor</label>
						<input type="text" name="nombre_provedor" class="form-control" placeholder="nombre del provedor" required value="{{old('nombre_provedor')}}">
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<div class="form-group">
						<label for="id_proveedor">ID del Proveedor</label>
						<input type="text" name="id_proveedor" class="form-control" placeholder="id del provedor" required value="{{old('id_proveedor')}}">
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<div class="form-group">
						<label for="numero_de_pedido">Número de pedido</label>
						<input type="text" name="numero_de_pedido" class="form-control" placeholder="Número de pedido" required value="{{old('numero_de_pedido')}}">
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<div class="form-group">
						<label for="num_factura">Número de Factura</label>
						<input type="text" name="num_factura" class="form-control" placeholder="numero de factura" required value="{{old('num_factura')}}">
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<div class="form-group">
						<label for="fecha_compra">Fecha de compra</label>
						<input type="date" name="fecha_compra" class="form-control" placeholder="fecha de compra" required value="{{old('fecha_compra')}}">
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<div class="form-group">
						<label for="fecha_vida_util_inicio">fecha de inicio de vida util</label>
						<input type="date" name="fecha_vida_util_inicio" class="form-control" placeholder="fecha de inicio de vida util"  value="{{old('fecha_vida_util_inicio')}}">
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<div class="form-group">
						<label for="fecha_depreciacion_inicio">fecha de inicio de depreciación</label>
						<input type="date" name="fecha_depreciacion_inicio" class="form-control" placeholder="fecha de inicio de depreciacion"  value="{{old('fecha_depreciacion_inicio')}}">
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<div class="form-group">
						<label for="xml">Xml</label>
						<input type="file" name="xml" class="form-control" accept="application/xml">
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<div class="form-group">
						<label for="pdf">Pdf</label>
						<input type="file" name="pdf" class="form-control" accept="application/pdf" >
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<div class="form-group">
						<label for="foto">Foto(s)</label>
						<input type="file" name="foto[]" multiple="multiple" class="form-control" accept="image/jpeg,image/jpg,image/bmp,image/png">
					</div>
				</div>
				<div ng-app="myApp" ng-controller="namesCtrl">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<div class="form-group">
							<label for="area_destinada">Área Destinada</label>
							<select name="area_destinada" class="form-control" ng-model="depa"  required="">
								@foreach($Departamento as $dep )
									<option  value="{{$dep->id_departamento}}">{{$dep->nombre}} </option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<div class="form-group">
							<label for="puesto">Puesto</label>
							<select name="puesto" class="form-control" ng-model="puesto">
									<option   ng-repeat="x in Departamento | filter:{id_depa: depa}" value="@{{x.id_puesto}}"> @{{x.nombre}}</option>
							</select>
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<div class="form-group">
							<label for="nombre_responsable">Nombre Del Responsable</label>
							<select name="nombre_responsable" class="form-control" >
								<option   ng-repeat="y in Empleado | filter:{id_puesto: puesto}:true" value="@{{y.id_empleado}}"> @{{y.nombre}}</option>
							</select>
						</div>
					</div> 
					<script>
						angular.module('myApp', []).controller('namesCtrl', function($scope) {
							$scope.Empleado = [
							@foreach($Empleado as $emp )
								{nombre:'{{$emp->nombre}} {{$emp->apellido_paterno}} {{$emp->apellido_materno}}',id_puesto:'{{$emp->id_puesto}}',id_empleado:'{{$emp->id_empleado}}'},
							@endforeach
							];
							$scope.Departamento = [
							@foreach($Puesto as $pues )
								{nombre:'{{$pues->nombre}}',id_depa:'{{$pues->id_departamento}}',id_puesto:'{{$pues->id_puesto}}'},
							@endforeach
							];
						});		
					</script>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<div class="form-group">
						<label for="observaciones">Observaciones</label>
						<input type="text" name="observaciones" class="form-control" placeholder="observaciones" value="{{old('observaciones')}}">
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<div class="form-group">
						<label for="garantia">Garantia</label>
						<input type="text" name="garantia" class="form-control" placeholder="garantia" value="{{old('garantia')}}">
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<div class="form-group">
						<button class="btn btn-primary" type="submit" >Guardar</button>
							<a href="/admin/inventario/EquipoComputo"><button class="btn btn-danger" type="button" >Cancelar</button></a>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
@endsection 