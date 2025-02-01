@extends('layouts.admin')
@section('contenido')
<div class="row">
	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
		<h1>Responsiva de Maquinaria y equipo de oficina </h1>
	</div>
</div>
<div class="row">
	<div class="clo-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<form action="" method="post">
				@csrf
				<div class="form-group">
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				  <div class="form-group">
					  <label for="nombre_responsable">Nombre del Responsable</label>
					  <select name="nombre_responsable" class="form-control" >
						  @foreach($Empleado as $emp )								
							  <option value="{{$emp->id_empleado}}"> {{$emp->apellido_paterno}} {{$emp->apellido_materno}} {{$emp->nombre}}</option>
						  @endforeach
					  </select>
				  </div>
				  <button type="submit" class="btn btn-primary">Imprimir</button>
			  </div>
			</form>
		</div>
	</div>
</div>
@endsection 