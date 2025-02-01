@extends('layouts.admin')
@section('contenido')
	<div class="row">
		<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
			<h1>Carga Masiva de activos de transporte</h1>
		</div>
	</div>
	<div class="row">
		<div class="clo-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="table-responsive">
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-10 col-md-10 col-sm-6 col-xs-12">
			@if(count($errors)>0)
				<div class="alert alert-danger">
					<ul>
						@foreach($errors ->all() as $error)
						<li>{{$error}}</li>
						@endforeach
					</ul>
				</div>
			@endif
			<div class="">
				<div class="row ">
					<form id='frm_up_xls_multi_trans' action="{{ route('ImportarTransporte') }}" method="POST" enctype="multipart/form-data">
						{{Csrf_field()}}
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
							<div class="panel panel-default">
								<!-- Default panel contents -->
								<div class="panel-heading">Subir archivo</div>
								<div class="panel-body">
									<p>
										<div class="form-group">
											<label for="giro">Categoria Equipo de Transporte</label>
											<input type="file" name="archivo_excel" id='archivo_excel' class="form-control" accept=".xls,.xlsx" >
										</div>
										<div class="form-group">
										<!--	<button class="btn btn-success" type="submit" >Guardar</button>-->
											<button  id='btn_subir_excel_masivo_trans' class="btn btn-success" type="button" >Guardar</button>
										</div>
									</p>
								</div>
							</div>
						</div>
					</form>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<div class="panel panel-default">
							<!-- Default panel contents -->
							<div class="panel-heading">Recomendaciones</div>
							<div class="panel-body">
								<p>
									Recuerda Usar el layout corespondiente para cada tipo de activo, si no cuentas con el descargalo aqui
									<div class="form-group">
										<A href="{{asset('archivos/inventario/EquipoTransporte/layout/Layout_Equipo_Transporte.xlsx')}}" ><input type="button"  class="form-control btn-primary" value="Descargar"></A>
									</div>
									<div class="form-group">
										Recomendaciones: 
										<br><br>
										<strong>No modificar la primera fila</strong>
										<br><br>
										<strong>Si no se conoce algún dato dejar el campo vacío </strong>
										<br><br>
										<strong>No usar formulas o referencias en los campos </strong>
										<br><br>
										Los siguientes campos solo admiten <strong>números </strong> o en su defecto <strong>el campo vacío</strong>: Estado, Tipo, costo, tasa depreciación, meses de vida útil, área, puesto, responsable, estatus depreciación, precio de venta
										<br><br>
										El estado puede ser: <strong>0</strong> (Inactivo) ó <strong>1</strong> (Activo)
										<br><br>
										El estado de depreciación puede ser: <strong>1</strong> (Vigente) <strong>2</strong>(Baja) <strong>3</strong> (Depreciado en uso)
									</div>
								</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!--<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<p class="alert alert-info">{{$Respuesta}}</p>
				</div>
			</div>-->
		</div>
	</div>
@endsection 