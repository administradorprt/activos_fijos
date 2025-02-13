@extends('layouts.admin')
@section('contenido')
<div class="row">
	<div class="col-lg-10 col-md-10 col-sm-6 col-xs-12">
		<h3>Nuevo mantenimiento para el activo {{$activo->id}}</h3>
        @if(count($errors)>0)
		<div class="alert alert-danger">
			<ul>
				@foreach($errors ->all() as $error)
				<li>{{$error}}</li>
				@endforeach
			</ul>
		</div>
		@endif
		<form action="{{route('mante.store',$activo->id)}}" method="post" enctype="multipart/form-data">
			@csrf
			<div class="row">
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<div class="form-group">
						<label for="tipo">Tipo de mantenimiento</label>
						<select name="tipo" class="form-control"  required="">
							<option value="" hidden>Seleccione una tipo</option>
                            <option value="1">Correctivo</option>
                            <option value="2">Preventivo</option>
						</select>
					</div>
				</div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<div class="form-group">
						<label for="proveedor">Proveedor</label>
						<input type="text" name="proveedor" maxlength="100" class="form-control" placeholder="proveedor" required value="{{old('proveedor')}}">
					</div>
				</div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<div class="form-group" title="Fecha en la que el proveedor realizó el mantenimeinto">
						<label for="fecha">Fecha del mantenimiento</label>
						<input type="date" name="fecha" class="form-control" required value="{{old('fecha')}}">
					</div>
				</div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<div class="form-group">
						<label for="pdfs">Pdfs</label>
						<input type="file" name="pdfs[]" class="form-control" accept="application/pdf" multiple required>
					</div>
				</div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<div class="form-group">
						<label for="foto">Evidencias</label>
						<input type="file" name="foto[]" multiple="multiple" class="form-control" accept="image/*" required>
					</div>
				</div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<div class="form-group">
						<label for="comentario">Comentarios/observaciones</label>
						<input type="text" name="comentario" maxlength="900" class="form-control" placeholder="comentario" value="{{old('comentario')}}">
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