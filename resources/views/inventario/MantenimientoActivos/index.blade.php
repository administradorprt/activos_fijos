@extends('layouts.admin')
@section('contenido')
<div class="row">
	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
		<h1>listado de mantenimientos <a href="{{route('manteAct.create',$tipo)}}"><button class="btn btn-success"> Añadir activo</button></a></h1>
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
</div>
<div>
	<table id="table_mante" class="display">
		<thead>
			<tr>
				<th>ID</th>
				<th>Num. de equipo</th>
				<th>Sucursal</th>
				<th>Estado</th>
				<th>Descripción</th>
				<th>Frec. mantenimiento</th>
				<th>Último mantenimiento</th>
				<th>Próximo mantenimiento</th>
				<th>opciones</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($manteList as $mante)
				<tr>
					<td>{{$mante->id}}</td>
					<td>{{$mante->activo->num_equipo}}</td>
					<td>{{$mante->activo->sucursal->empresa->alias}} - {{$mante->activo->sucursal->nombre}}</td>
					<td>
						@if ($mante->status==1)
							<span>Activo</span>
						@else
							<span>Inactivo</span>
						@endif
					</td>
					<td>{{$mante->activo->descripcion}}</td>
					<td>{{$mante->frecuencia->name}}</td>
					<td>{{$mante->ultimo_mante}}</td>
					<td>{{$mante->proximo_mante}}</td>
					<td>
						<a href="{{route('manteAct.show',$mante->id)}}"><button class="btn btn-info">Ver</button></a>
						<a href="{{route('mante.create',$mante->id)}}"><button class="btn btn-info">Manto.</button></a>
						@if ($mante->frecuencia->manual)
							<a href="" data-target="#modal-fecha-{{$mante->id}}" data-toggle="modal"><button class="btn btn-primary">Definir manto.</button></a>
						@endif
						@if($mante->status==1)
							<a href="" data-target="#modal-delete-{{$mante->id}}" data-toggle="modal"><button class="btn btn-danger">Baja</button></a>
						@else
							<a href="" data-target="#modal-activate-{{$mante->id}}" data-toggle="modal"><button class="btn btn-success">Activar</button></a>
						@endif
					</td>
				</tr>
				@include('inventario.MantenimientoActivos.modals')
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
	new DataTable('#table_mante',{
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
			this.api().columns([2,5]).every(function () {
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