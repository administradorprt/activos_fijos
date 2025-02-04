@extends('layouts.admin')
@section('contenido')

<div class="row">
	<div class="col-lg-10 col-md-10 col-sm-6 col-xs-12">
		<h3>Nuevo Tipo</h3>
		@if(count($errors)>0)
		<div class="alert alert-danger">
			<ul>
				@foreach($errors ->all() as $error)
				<li>{{$error}}</li>
				@endforeach
			</ul>
			
		</div>
		@endif

		<form action="{{route('Tipo.store')}}" method="post" autocomplete="off">
			@csrf
			<div class="row">
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<div class="form-group">
						<label for="giro">Categoria de Activo</label>
						<select name="giro" class="form-control">
							@foreach($Giro as $gir )
								<option value="{{$gir->id_giro}}">{{$gir->nombre}} </option>
							@endforeach
							
						</select>
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<div class="form-group">
						<label for="sucursal">Sucursal</label>
						<select name="sucursal" class="form-control">
							<option value="" hidden>Seleccionar sucursal</option>
							@foreach($sucursales as $suc )
								<option value="{{$suc->id_sucursal}}">{{$suc->empresa->alias}} - {{$suc->nombre}} </option>
							@endforeach
							
						</select>
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<div class="form-group">
						<label for="nombre">Nombre</label>
						<input type="text" name="nombre" class="form-control" placeholder="nombre" required value="{{old('nombre')}}">
					</div>
				</div>
	
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<button class="btn btn-primary" type="submit" >Guardar</button>
						<a href="{{ URL::previous() }}"><button class="btn btn-danger" type="button" >Cancelar</button></a>
				</div>
			</div>
		</form>
	</div>
</div>

@endsection 
