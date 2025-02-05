@extends('layouts.admin')
@section('contenido')
<div class="row">
	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
		<h1>listado de equipos de transporte <a href="EquipoTransporte/create"><button class="btn btn-success"> Nuevo</button></a></h1>
		@include('inventario.EquipoTransporte.search')
	</div>
</div>
<div class="row">
	<div class="clo-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-condesed table-hover">
				<thead>
					<th>ID</th>
					<th>Sucursal</th>
					<th>Numero de equipo</th>
					<th>Estado</th>
					<th>Descripcion</th>
					<th>Marca</th>
					<th>Vin</th>
					<th>Modelo</th>
					<th>Tipo</th>
					<th>Foto</th>
					<th>Opciones</th>
				</thead>
				@foreach ($EquipoTransporte as $tras)
					@if(auth()->user()->role_id<3 || auth()->user()->id == $tras->created_user)
						<tr>
							<td>{{$tras->id}}</td>
							<td>{{$tras->sucursal->empresa->alias}} - {{$tras->sucursal->nombre}}</td>	
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
							<td>{{$tras->vin}}</td>
							<td>{{$tras->modelo}}</td>
							<td>{{$tras->nombre}}</td>
							<td>
								@php
									$serversUser = \App\Models\Imagen::where('activo_id', '=', $tras->id)->take(1)->get();
								@endphp
								@foreach ($serversUser as $ser)
									<img src="{{asset('imagenes/inventario/EquipoTransporte/img/'.$ser->imagen)}}" alt="{{$tras->descripcion}}" height="100px" width="100px" class="img-thumbnail">	
								@endforeach
							</td>
							<td>
								<a href="{{route('EquipoTransporte.show', ['EquipoTransporte' => $tras->id])}}"><button class="btn btn-info">Ver</button></a>
								<a href="{{route('EquipoTransporte.edit',['EquipoTransporte' => $tras->id])}}"><button class="btn btn-info">Editar</button></a>
							
								@if($tras->estado==1)
									<a href="" data-target="#modal-delete-{{$tras->id}}" data-toggle="modal"><button class="btn btn-danger">Baja</button></a>
								@else
									<a href="" data-target="#modal-activate-{{$tras->id}}" data-toggle="modal"><button class="btn btn-success">Activar</button></a>
								@endif
								@if(auth()->user()->role_id == 1 || auth()->user()->role_trans == 2)
							
								@endif
							</td>
						</tr>
						@include('inventario.EquipoTransporte.modal')
					@endif
				@endforeach
			</table>
		</div>
		{{$EquipoTransporte->appends(['searchText' => $searchText])->links('pagination::bootstrap-4')}}
	</div>
</div>
@endsection 