@extends('layouts.admin')
@section('contenido')
<div class="row">
	<div class="col-lg-10 col-md-10 col-sm-6 col-xs-12">
		<h3>Datos del carrito {{$carrito->id}}</h3>
        @if(count($errors)>0)
		<div class="alert alert-danger">
			<ul>
				@foreach($errors ->all() as $error)
				<li>{{$error}}</li>
				@endforeach
			</ul>
		</div>
		@endif
        <form action="{{route('carritos.update',$carrito->id)}}" method="post" x-data="carrito_edit">
            @method('PATCH')
            @csrf
            <div class="row">
                <x-row-element>
                    <label for="titulo">Nombre del carrito:</label>
                        <input type="text" name="titulo" minlength="1" maxlength="100" class="form-control" value="{{$carrito->nombre}}">
                </x-row-element>
                <x-row-element>
                    <label for="sucursal_origen">Sucursal</label>
                    <select name="sucursal" id="sucursal_origen" class="form-control"  required="" @change="await getActivos(event)">
                        <option value="" hidden>Seleccione una sucursal</option>
                        @foreach($sucursales as $sucursal )
                            <option  value="{{$sucursal->id_sucursal}}" @selected($sucursal->id_sucursal==$carrito->sucursal_id)>{{$sucursal->empresa->alias}} - {{$sucursal->nombre}} </option>
                        @endforeach
                    </select>
                </x-row-element>
                <x-row-element>
                    <label for="user">Asignado a:</label>
                    <select name="user" class="form-control"  required="" x-model="emp">
                        <option value="" hidden>Seleccione un empleado</option>
                        <template x-for="emple in users" :key="emple.id_empleado">
                            <option :value="emple.id_empleado" x-text="`${emple.nombres} ${emple.apellido_p} ${emple.apellido_m}`" :selected="emp==emple.id_empleado&&true"></option>
                        </template>
                    </select>
                </x-row-element>
            </div>
            <x-panel title="Cajones">
                <div class="alert alert-warning" role="alert">
                    <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                    Marque los activos que desea remover de los cajones.
                </div>
                @foreach ($carrito->cajones as $cajon)
                    <x-acordeon id="ac_{{$cajon->id}}">
                        <x-acordeon-element id="activos{{$cajon->id}}" hrefID="cajon_{{$cajon->id}}" parentID="ac_{{$cajon->id}}" title="{{$cajon->name}}">
                            <div class="row" style="overflow-y: auto;max-height: 25rem;">
                                @foreach ($cajon->activos as $activo)   
                                    <x-row-element>
                                        <label >
                                            <input type="checkbox" name="cajones[{{$cajon->id}}][]" id="c_{{$cajon->id}}_act_{{$activo->id}}" value="{{$activo->id}}">
                                            <span>{{$activo->num_equipo}} - {{$activo->descripcion}}</span>
                                        </label>
                                    </x-row-element>
                                @endforeach
                            </div>
                            <div class="row">
                                <x-row-element>
                                    <a href="{{route('carritos.cajon.add',$cajon->id)}}" class="btn btn-success">Añadir activos</a>
                                    <button type="button" class="btn btn-danger" data-target="#modal-delete-{{$cajon->id}}" data-toggle="modal"><i class="fa fa-trash" aria-hidden="true"></i>
                                        <span>Eliminar cajón</span>
                                    </button>
                                </x-row-element>
                            </div>
                        </x-acordeon-element>
                    </x-acordeon>
                @endforeach
            </x-panel>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                    <button class="btn btn-primary" type="submit" >Actualizar</button>
                    <a href="{{ route('carritos',$carrito->giro_id) }}"><button class="btn btn-danger" type="button" >Cancelar</button></a>
                </div>
            </div>
        </form>
        @foreach ($carrito->cajones as $cajon)
            @include('inventario.Carritos.modalDeleteCajon')
        @endforeach
	</div>
</div>
<link rel="stylesheet" href="{{asset('css/acordeon.css')}}">
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('carrito_edit', () => ({loading: false,emp:@js($carrito->user_asignado),
            activos:[],users:@js($empleados),errors:[],
			async getActivos(event){
                this.carrito=[];
                this.loading=true;
				const val=event.target.value;
				const urlBase = @js(route('service.getActivosBySuc', ['giro' => $carrito->giro_id, 'sucursal' => '__SUCURSAL__']));
            	const url = urlBase.replace('__SUCURSAL__', val);
				await this.getEmpleados(val);
				const response=await fetch(url).finally(()=>{
                    this.loading=false;
                });
				response.ok
				?this.activos=await response.json()
				:console.error(`Error ${response.status}: ${response.statusText}`);

			},
            async getEmpleados(sucursal){
                const urlBase = @js(route('service.empleados.suc', ['sucursal' => '__SUCURSAL__']));
            	const url = urlBase.replace('__SUCURSAL__', sucursal);
				const response=await fetch(url);
				response.ok
				?this.users=await response.json()
				:console.error(`Error ${response.status}: ${response.statusText}`);
            },
            error(list){
                Object.entries(list.errors).forEach(([clave,error]) => {
                    this.errors.push(error[0]);
                });
                console.log(list.errors,this.errors);
            }
        }))
    })
</script>
@endsection