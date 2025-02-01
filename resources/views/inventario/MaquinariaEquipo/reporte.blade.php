@extends('layouts.admin')
@section('contenido')

<div class="row">
	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
		<h1>reportes de Maquinaria y Equipo </h1>
	
	</div>
</div>
<div class="row">
	<div class="clo-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			
			<form>
 
  <div class="form-group">
  
  </div>
  
  
  <a href="{{url('admin/inventario/MaquinariaEquipo/Reporte/1')}}"><button type="button" class="btn btn-primary">General</button>
</a>
 <a href="{{url('admin/inventario/MaquinariaEquipo/Reporte/2')}}"><button type="button" class="btn btn-primary">inactivos</button>
</a>
 <a href="{{url('admin/inventario/MaquinariaEquipo/Reporte/3')}}"><button type="button" class="btn btn-primary">Activos</button>
</a>
</form>
			
		</div>
		
	</div>
</div>
@endsection 
