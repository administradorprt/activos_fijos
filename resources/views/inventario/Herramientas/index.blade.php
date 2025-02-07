@extends('layouts.admin')
@section('contenido')

<div class="row">
	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
		<h1>listado de herramientas <a href="Herramientas/create"><button class="btn btn-success"> Nuevo</button></a></h1>
		@include('inventario.Herramientas.search')
	</div>
</div>
<div class="row">
	<div class="clo-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-condesed table-hover">
				<thead>
					<th>ID</th>
					<th>Numero de equipo</th>
					<th>Sucursal</th>
					<th>Estado</th>
					<th>Descripcion</th>
					<th>Marca</th>
					<th>codigo</th>
					<th>Modelo</th>
					<th>Tipo</th>
					<th>Foto</th>
					<th>Opciones</th>

				</thead>
				@foreach ($Herramientas as $tras)
					@if(auth()->user()->role_id<3 || auth()->user()->id == $tras->created_user)
						<tr>
							<td>{{$tras->id}}</td>	
							<td>{{$tras->num_equipo}}</td>
							<td>
								{{$tras->sucursal->empresa->alias}} - {{$tras->sucursal->nombre}}
							</td>
							<td>
								@if($tras->estado==1)
								Activo
								@else
								Inactivo
								@endif
							</td>
							<td>{{substr($tras->descripcion, 0, 20)}}</td>
							<td>{{$tras->marca}}</td>
							<td>{{$tras->codigo}}</td>
							<td>{{$tras->modelo}}</td>
							<td>{{$tras->tipos->nombre}}</td>
							<td>
								@php
									$serversUser = \App\Models\Imagen::where('activo_id', '=', $tras->id)->get();
								@endphp
								@foreach ($serversUser as $ser)
									<img src="{{asset('storage/imagenes/inventario/Herramientas/img/'.$ser->imagen)}}" alt="{{$tras->descripcion}}" height="100px" width="100px" class="img-thumbnail">	
								@endforeach
							</td>
							<td>
								<a href="{{route('Herramientas.show', ['Herramienta' => $tras->id])}}"><button class="btn btn-info">Ver</button></a>
								<a href="{{route('Herramientas.edit',['Herramienta' => $tras->id])}}"><button class="btn btn-info">Editar</button></a>
								@if($tras->estado==1)
									<a href="" data-target="#modal-delete-{{$tras->id}}" data-toggle="modal"><button class="btn btn-danger">Baja</button></a>
								@else
								<a href="" data-target="#modal-activate-{{$tras->id}}" data-toggle="modal"><button class="btn btn-success">Activar</button></a>
								@endif
								@if(auth()->user()->role_id == 1 || auth()->user()->role_her == 2)
								@endif
							</td>
						</tr>
						@include('inventario.Herramientas.modal')
					@endif
				@endforeach
			</table>
		</div>
		{{$Herramientas->appends(['searchText' => $searchText])->links('pagination::bootstrap-4')}}
	</div>
</div>
@endsection 
