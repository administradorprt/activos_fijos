@extends('layouts.admin')
@section('contenido')
<div class="row">
	<div class="col-lg-10 col-md-10 col-sm-6 col-xs-12"x-data="mantenimiento">
		<h3>Nuevo activo para mantenimiento</h3>
		@if(count($errors)>0)
		<div class="alert alert-danger">
			<ul>
				@foreach($errors ->all() as $error)
				<li>{{$error}}</li>
				@endforeach
			</ul>
		</div>
		@endif
		<form action="{{route('manteAct.store')}}" method="post" >
			@csrf
			<div class="row">
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<div class="form-group">
						<label for="sucursal_origen">Sucursal</label>
						<select name="sucursal_origen" class="form-control"  required="" @change="await getActivos(event)">
							<option value="" hidden>Seleccione una sucursal</option>
							@foreach($sucursales as $sucursal )
								<option  value="{{$sucursal->id_sucursal}}">{{$sucursal->empresa->alias}} - {{$sucursal->nombre}} </option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<div class="form-group">
						<label for="activo">Activo</label>
						<select name="activo" class="form-control"  required="">
							<option value="" hidden>Seleccione un activo</option>
							<template x-for="activo in activos" :key="activo.id">
								<option :value="activo.id" x-text="activo.descripcion"></option>
							</template>
						</select>
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<div class="form-group">
						<label for="periodo">Periodo de mantenimiento</label>
						<select name="periodo" class="form-control"  required=""{{--  @change="periodoManual(event)" --}}>
							<option value="" hidden>Seleccione el periodo</option>
							@foreach($frecuencias as $frecuencia)
								<option  value="{{$frecuencia->id}}">{{$frecuencia->name}}</option>
							@endforeach
						</select>
					</div>
				</div>
				{{-- <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" x-cloak x-show="manual">
					<div class="form-group">
						<label for="fecha_mante">Fecha de mantenimiento</label>
						<input type="date" name="fecha_mante" class="form-control" placeholder="fecha_mante" x-ref="fecha_mante" value="{{old('fecha_mante')}}">
					</div>
				</div> --}}
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<button class="btn btn-primary" type="submit" >Guardar</button>
					<a href="{{ URL::previous() }}"><button class="btn btn-danger" type="button" >Cancelar</button></a>
				</div>
			</div>
		</form>
	</div>
</div>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('mantenimiento', () => ({
            activos:[],periodos:@js($frecuencias),manual:false,
			async getActivos(event){
				const val=event.target.value;
				const urlBase = @js(route('service.getActivosBySuc', ['giro' => $tipo, 'sucursal' => '__SUCURSAL__']));
            	const url = urlBase.replace('__SUCURSAL__', val);
				
				const response=await fetch(url);
				response.ok
				?this.activos=await response.json()
				:console.error(`Error ${response.status}: ${response.statusText}`);
			},
			/* periodoManual(event){
				
				const val=event.target.value;
				const frec=this.periodos.find((element)=>element.id==val);
				frec.manual
				?this.manual=true
				:this.manual=false;
				
				this.manual
				?this.$refs.fecha_mante.required=true
				:this.$refs.fecha_mante.required=false;
			} */
        }))
    })
</script>
@endsection