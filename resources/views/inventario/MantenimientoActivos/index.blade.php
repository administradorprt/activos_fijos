@extends('layouts.admin')
@section('contenido')
<div class="row">
	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
		<h1>listado de mantenimientos <a href="{{route('manteAct.create',$tipo)}}"><button class="btn btn-success"> Nuevo</button></a></h1>
	</div>
</div>
<div>
	<table id="table_mante" width="100%" border=1>
		<thead>
			<tr>
				<th>Num. de equipo</th>
				<th>Sucursal</th>
				<th>Estado</th>
				<th>Descripción</th>
				<th>Último mantenimiento</th>
				<th>Próximo mantenimiento</th>
				<th>opciones</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($manteList as $mante)
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td>{{$mante->ultimo_mante}}</td>
					<td>{{$mante->proximo_mante}}</td>
					<td></td>
				</tr>
			@endforeach
		</tbody>
	</table>
</div>
@endsection