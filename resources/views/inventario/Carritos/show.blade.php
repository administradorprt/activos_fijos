@extends('layouts.admin')
@section('contenido')
<div class="row">
	<div class="col-lg-10 col-md-10 col-sm-6 col-xs-12">
		<h3>Datos del carrito {{$carrito->id}}</h3>
        <div class="row" x-data="qr">
            <x-row-element>
                <label for="titulo">Nombre del carrito:</label>
                    <input type="text" name="titulo" maxlength="100" class="form-control" readonly value="{{$carrito->nombre}}">
            </x-row-element>
            <x-row-element>
                <label for="sucursal">Sucursal:</label>
                    <input type="text" name="sucursal" maxlength="100" class="form-control" readonly value="{{$carrito->sucursal->empresa->alias}} - {{$carrito->sucursal->nombre}}">
            </x-row-element>
            <x-row-element>
                <label for="asignado">Asignado a:</label>
                    <input type="text" name="asignado" maxlength="100" class="form-control" readonly value="{{$carrito->asignado->nombres}} {{$carrito->asignado->apellido_p}} {{$carrito->asignado->apellido_m}}">
            </x-row-element>
            <x-row-element>
                <button class="btn btn-success" data-target="#modal-qr-{{$carrito->id}}" data-toggle="modal" @click="await getQr()"><x-icons.qr/></button>
            </x-row-element>
            @include('inventario.Carritos.Qr')
        </div>
        <x-panel title="Cajones">
            @foreach ($carrito->cajones as $cajon)
                <x-acordeon id="ac_{{$cajon->id}}">
                    <x-acordeon-element id="activos{{$cajon->id}}" hrefID="cajon_{{$cajon->id}}" parentID="ac_{{$cajon->id}}" title="{{$cajon->name}}">
                        <div class="row">
                            @foreach ($cajon->activos as $activo)   
                                <x-row-element>
                                    <span>{{$activo->num_equipo}} - {{$activo->descripcion}}</span>
                                </x-row-element>
                            @endforeach
                        </div>
                    </x-acordeon-element>
                </x-acordeon>
            @endforeach
        </x-panel>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <a href="{{ route('carritos',$carrito->giro_id) }}"><button class="btn btn-danger" type="button" >Atrás</button></a>
            </div>
        </div>
	</div>
</div>
<link rel="stylesheet" href="{{asset('css/acordeon.css')}}">
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('qr', () => ({
            pathQR: '',loading:true,
			async getQr(){
                this.loading=true;
                
				const response=await fetch(@js(route('service.carritos.qr',$carrito->id)))
                    .finally(()=>{
                        this.loading=false;
                    });
				response.ok
				?this.pathQR=await response.json()
				:console.error(`Error ${response.status}: ${response.statusText}`);
			},
        }))
    })
</script>
@endsection