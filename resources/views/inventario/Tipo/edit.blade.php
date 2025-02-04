@extends('layouts.admin')
@section('contenido')

<div class="row">
	<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
		<h3>Editar Tipo: {{$Tipo->id_tipo}}</h3>
		@if(count($errors)>0)
		<div class="alert alert-danger">
			<ul>
				@foreach($errors ->all() as $error)
				<li>{{$error}}</li>
				@endforeach
			</ul>
			
		</div>
		@endif

		<form action="{{route('Tipo.update',$Tipo->id_tipo)}}" method="post">
			@method('PATCH')
			@csrf
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<label for="estado">Estado</label>
					<select name="estado" class="form-control" disabled="" ">
						
							@if($Tipo->estado== 1)
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
					<label for="giro">Categoria de Activo</label>
					<select name="giro" class="form-control" value="">
						@foreach($Giro as $gir )
							@if($gir->id_giro== $Tipo->id_giro)
							<option selected value="{{$gir->id_giro}}">{{$gir->nombre}} </option>
							@else
								<option value="{{$gir->id_giro}}"> {{$gir->nombre}} </option>
							@endif
						@endforeach
						
					</select>
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<label for="sucursal">Sucursal</label>
					<select name="sucursal" class="form-control">
						@foreach ($sucursales as $suc)
							@if ($suc->id_sucursal==$Tipo->sucursal_id)
								<option value="{{$suc->id_sucursal}}" selected>{{$suc->empresa->alias}} - {{$suc->nombre}}</option>
							@endif
						@endforeach
					</select>
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<label for="nombre">Nombre</label>
					<input type="text" name="nombre" class="form-control" placeholder="nombre" required value="{{$Tipo->nombre}}">
				</div>
			</div>
		</div>

			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
			<div class="form-group">
				<button class="btn btn-primary" type="submit" >Actualizar</button>
				<a href="{{ URL::previous() }}"><button class="btn btn-danger" type="button" >Cancelar</button></a>

			</div>
			</div>
		</div>
		</form>
	</div>
</div>
@endsection 