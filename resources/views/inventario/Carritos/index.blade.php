@extends('layouts.admin')
@section('contenido')
<div class="row">
	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
		<h1>listado de carritos de activos <a href="{{route('carritos.create',$tipo)}}"><button class="btn btn-success"> Nuevo carrito</button></a></h1>
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
	<table id="table_carritos" class="display" style="width: 100%">
		<thead>
			<tr>
				<th>Sucursal</th>
				<th>Carrito</th>
				<th>Usuario</th>
				<th>opciones</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($carritos as $carrito)
				<tr>
					<td>{{$carrito->sucursal->empresa->alias}} - {{$carrito->sucursal->nombre}}</td>
					<td>{{$carrito->nombre}}</td>
					<td>{{$carrito->asignado->nombres}} {{$carrito->asignado->apellido_p}} {{$carrito->asignado->apellido_m}}</td>
					<td>
						<a href="{{route('carritos.show',$carrito->id)}}"><button class="btn btn-info">Ver</button></a>
						<a href="{{route('carritos.edit',$carrito->id)}}"><button class="btn btn-primary">Editar</button></a>
					</td>
				</tr>
			@endforeach
		</tbody>
		<tfoot>
			<tr>
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
	new DataTable('#table_carritos',{
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