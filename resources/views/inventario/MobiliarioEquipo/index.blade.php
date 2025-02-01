@extends('layouts.admin')
@section('contenido')

<div class="row">
	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
		<h1>Mobiliario y Equipos de Oficina <a href="MobiliarioEquipo/create"><button class="btn btn-success"> Nuevo</button></a></h1>
		@include('inventario.MobiliarioEquipo.search')
	</div>
</div>
<div class="row">
	<div class="clo-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-condesed table-hover">
				<thead>
					<th>ID</th>
					<th>Numero de equipo</th>
					<th>Estado</th>
					<th>Descripcion</th>
					<th>Marca</th>
					<th>serie</th>
					<th>Modelo</th>
					<th>Tipo</th>
					<th>Foto</th>
					<th>Opciones</th>

				</thead>
				@foreach ($MobiliarioEquipo as $tras)
					@if(auth()->user()->role_id<3 || auth()->user()->id == $tras->created_user)
						<tr>
							<td>{{$tras->id_equipo_mobiliario}}</td>	
							<td>{{$tras->num_equipo}}</td>
							<td>
								@if($tras->estado==1)
									Activo
								@else
									Inactivo
								@endif
							</td>
							<td>{{substr($tras->descripcion, 0, 20)}}</td>
							<td>{{$tras->marca}}</td>
							<td>{{$tras->serie}}</td>
							<td>{{$tras->modelo}}</td>
							<td>{{$tras->nombre}}</td>
							<td>
								@php
									$serversUser = \App\Models\Imagen::where('id_equipo', '=', $tras->id_equipo_mobiliario)->where('id_area', '=', 3)->take(1)->get();
								@endphp
								@foreach ($serversUser as $ser)
									<img src="{{asset('imagenes/inventario/MobiliarioEquipo/img/'.$ser->imagen)}}" alt="{{$tras->descripcion}}" height="100px" width="100px" class="img-thumbnail">	
								@endforeach
							</td>
							<td>
								<a href="{{route('MobiliarioEquipo.show', ['MobiliarioEquipo' => $tras->id_equipo_mobiliario])}}"><button class="btn btn-info">Ver</button></a>
								<a href="{{route('MobiliarioEquipo.edit',['MobiliarioEquipo' => $tras->id_equipo_mobiliario])}}"><button class="btn btn-info">Editar</button></a>		
								@if($tras->estado==1)
									<a href="" data-target="#modal-delete-{{$tras->id_equipo_mobiliario}}" data-toggle="modal"><button class="btn btn-danger">Baja</button></a>
								@else
									<a href="" data-target="#modal-activate-{{$tras->id_equipo_mobiliario}}" data-toggle="modal"><button class="btn btn-success">Activar</button></a>
								@endif
								@if(auth()->user()->role_id == 1 || auth()->user()->role_mob == 2)
								@endif
							</td>
						</tr>
							@include('inventario.MobiliarioEquipo.modal')
					@endif
				@endforeach
			</table>
		</div>
		<div class="container">
</div>
		{{$MobiliarioEquipo->appends(['searchText' => $searchText])->links('pagination::bootstrap-4')}}
	</div>
</div>
@endsection 