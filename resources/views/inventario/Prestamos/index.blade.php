@extends('layouts.admin')
@section('contenido')
@php
	use Carbon\Carbon;
@endphp
<div class="row">
	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
		<h1>Listado de prestamos pendientes 
			<a href="{{route('prestamos.create',$tipo)}}"><button class="btn btn-success"> Nuevo préstamo</button></a>
			<a href="" data-target="#modal-reporte" data-toggle="modal" class="btn btn-default">Reporte general</a>
		</h1>
		@if(count($errors)>0)
		<div class="alert alert-danger">
			<ul>
				@foreach($errors ->all() as $error)
				<li>{{$error}}</li>
				@endforeach
			</ul>
		</div>
		@endif
	</div>
	@include('inventario.Prestamos.modalReporte')
</div>
<div>
	<table id="table_prest" class="display">
		<thead>
			<tr>
				<th>Sucursal</th>
				<th>Num. de equipo</th>
				<th>Descripción</th>
				<th>Usuario</th>
				<th>Fecha de préstamo</th>
				<th>opciones</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($prestamos as $prestamo)
				<tr>
					<td>{{$prestamo->activo->sucursal->empresa->alias}} - {{$prestamo->activo->sucursal->nombre}}</td>
					<td>{{$prestamo->activo->num_equipo}}</td>
					<td>{{str($prestamo->activo->descripcion)->words(4,'...')}}</td>
					<td>{{$prestamo->usuario->nombres}} {{$prestamo->usuario->apellido_p}} {{$prestamo->usuario->apellido_m}}</td>
					<td>{{Carbon::create($prestamo->fecha)->locale('es')->isoFormat('D MMM YYYY hh:mm a')}}</td>
					<td>
						<a href="" data-target="#modal-fecha-{{$prestamo->id}}" data-toggle="modal" class="btn btn-primary">
							Devolución
						</a>
					</td>
				</tr>
				@if ($prestamo->fecha_devuelto == null)
					@include('inventario.Prestamos.modalDevolucion')
				@endif
			@endforeach
		</tbody>
		<tfoot>
			<tr>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
			</tr>
		</tfoot>
	</table>
	
</div>
<link rel="stylesheet" href="{{asset('css/datatables.min.css')}}">
<script src="{{asset('js/datatables.min.js')}}"></script>
<script>
	new DataTable('#table_prest',{
		language:{
			info:'Mostrando página _PAGE_ de _PAGES_',
			infoEmpty:'No hay datos disponibles',
            infoFiltered:'(filtrado de _MAX_ elementos)',
            lengthMenu:'Mostrar _MENU_ elementos',
            search:'Buscar:',
            zeroRecords:'No se encontraron resultados',
		},
		layout: {
			topStart: {
				buttons: ['copy', 'excel', 'pdf']
			}
		},
		stateSave:true,
		//filtro por columna
		initComplete: function () {	
			this.api().columns([0]).every(function () {
				let column = this;
				
				// Create select element
				let select = document.createElement('select');
				select.add(new Option(''));
				column.footer().replaceChildren(select);

				// Apply listener for user change in value
				select.addEventListener('change', function () {
					column.search(select.value, {exact: true}).draw();
				});

				// Add list of options
				column.data().unique().sort().each(function (d, j) {
					select.add(new Option(d));
				});
			});
		}
	});
</script>
@endsection