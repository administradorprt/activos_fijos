@extends('layouts.admin')
@section('contenido')
<div class="row">
	<div class="col-lg-10 col-md-10 col-sm-6 col-xs-12"x-data="mantenimiento">
		<h3>Nuevo prestamo de activo</h3>
		@if(count($errors)>0)
		<div class="alert alert-danger">
			<ul>
				@foreach($errors ->all() as $error)
				<li>{{$error}}</li>
				@endforeach
			</ul>
		</div>
		@endif
		<form action="{{route('prestamos.store',$tipo)}}" method="post" >
			@csrf
			<div class="row">
                <x-row-element>
                    <label for="sucursal">Sucursal</label>
                    <select id="sucursal" name="sucursal" class="form-control"  required="" @change="await getActivos(event)">
                        <option value="" hidden>Seleccione una sucursal</option>
                        @foreach($sucursales as $sucursal )
                            <option  value="{{$sucursal->id_sucursal}}">{{$sucursal->empresa->alias}} - {{$sucursal->nombre}} </option>
                        @endforeach
                    </select>
                </x-row-element>
                <x-row-element>
                    <label for="activo">Activo</label>
                    <select name="activo" class="form-control"  required="">
                        <option value="" hidden>Seleccione un activo</option>
                        <template x-for="activo in activos" :key="activo.id">
                            <option :value="activo.id" x-text="`${activo.descripcion} - ${activo.num_equipo}`"></option>
                        </template>
                    </select>
                </x-row-element>
                <x-row-element>
                    <label for="empleado">Empleado</label>
                    <select name="user" class="form-control"  required="">
                        <option value="" hidden>Seleccione un empleado</option>
                        <template x-for="emp in users" :key="emp.id_empleado">
                            <option :value="emp.id_empleado" x-text="`${emp.nombres} ${emp.apellido_p} ${emp.apellido_m}`"></option>
                        </template>
                    </select>
                </x-row-element>
                <x-row-element>
                    <label for="fecha">Fecha del prestamo</label>
                    <input type="datetime-local" id="fecha" name="fecha" class="form-control" placeholder="" required value="{{old('fecha')}}">
                </x-row-element>
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
            activos:[],users:[],
			async getActivos(event){
				const val=event.target.value;
				const urlBase = @js(route('service.prestamos.activos', ['giro' => $tipo, 'sucursal' => '__SUCURSAL__']));
            	const url = urlBase.replace('__SUCURSAL__', val);
                
				const response=await fetch(url);
				response.ok
				?this.activos=await response.json()
				:console.error(`Error ${response.status}: ${response.statusText}`);
                this.getEmpleados(val);
            
			},
            async getEmpleados(sucursal){
                const urlBase = @js(route('service.empleados.suc', ['sucursal' => '__SUCURSAL__']));
            	const url = urlBase.replace('__SUCURSAL__', sucursal);
				const response=await fetch(url);
				response.ok
				?this.users=await response.json()
				:console.error(`Error ${response.status}: ${response.statusText}`);
            },
        }))
    })
</script>
@endsection