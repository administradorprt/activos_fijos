@extends('layouts.admin')
@section('contenido')
<div class="row">
	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
		<h1>listado de tipos <a href="Tipo/create"><button class="btn btn-success"> Nuevo</button></a></h1>
		@include('inventario.Tipo.search')
	</div>
</div>
<div class="row">
	<div class="clo-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-condesed table-hover">
				<thead>
					<th>ID</th>
					<th>Nombre</th>
					<th>Giro</th>
					<th>Opciones</th>
				</thead>
				@foreach ($Tipo as $tras)
				<tr>
					<td>{{$tras->id_tipo}}</td>
					<td>{{$tras->nombre}}</td>
					<td>{{$tras->giro_nombre}}</td>
					<td>
						<a href="/admin/inventario/Tipo/{{$tras->id_tipo}}/edit"><button class="btn btn-info">Editar</button></a>
						@if($tras->estado==1)
							<a href="" data-target="#modal-delete-{{$tras->id_tipo}}" data-toggle="modal"><button class="btn btn-danger">Baja</button></a>
						@else
							<a href="" data-target="#modal-activate-{{$tras->id_tipo}}" data-toggle="modal"><button class="btn btn-success">Activar</button></a>
						@endif
						@if(auth()->user()->role_id == 1 )
							
						@endif
					</td>
				</tr>
				@include('inventario.Tipo.modal')
				@endforeach
			</table>
		</div>
		{{$Tipo->appends(['searchText' => $searchText])->links('pagination::bootstrap-4')}}
	</div>
</div>
@endsection 