@extends('layouts.admin')
@section('contenido')
<div class="row" x-data="qr">
	<div class="col-lg-10 col-md-10 col-sm-6 col-xs-12">
		<h3>Activo: {{$mante->activo_id}}</h3>
        <div class="row">
            <x-row-element>
                <label for="sucursal_origen">Sucursal</label>
                <input type="text" id="sucursal_origen" name="sucursal_origen" class="form-control" placeholder="sucursal_origen" value="{{$mante->activo->sucursal->empresa->alias}} - {{$mante->activo->sucursal->nombre}}" readonly="">
            </x-row-element>
            <x-row-element>
                <label for="num_equipo">Número de equipo</label>
                <input type="text" id="num_equipo" name="num_equipo" class="form-control" placeholder="sucursal_origen" value="{{$mante->activo->num_equipo}}" readonly="">
            </x-row-element>
            <x-row-element>
                <label for="descripcion">Descripción</label>
                <input type="text" id="descripcion" name="descripcion" class="form-control" placeholder="sucursal_origen" value="{{$mante->activo->descripcion}}" readonly="">
            </x-row-element>
            <x-row-element>
                <label for="frecuencia">Periodo de mantenimiento</label>
                <input type="text" id="frecuencia" name="frecuencia" class="form-control" placeholder="sucursal_origen" value="{{$mante->frecuencia->name}}" readonly="">
            </x-row-element>
            @if (request()->routeIs('manteAct.show'))    
                <x-row-element>
                    <button class="btn btn-success" data-target="#modal-qr-{{$mante->id}}" data-toggle="modal" @click="await getQr()"><x-icons.qr/></button>
                </x-row-element>
            @endif
        </div>       
	</div>
    @if (request()->routeIs('manteAct.show'))
        @include('inventario.MantenimientoActivos.modalQr')
    @endif
</div>
<hr>
@if ($mante->lastMante)
    <h4>Último mantenimiento ({{$mante->ultimo_mante}} ) <a href="{{URL::signedRoute('mante.history',$mante->id)}}"><button class="btn btn-primary" type="button" >Consultar historico</button></a></h4>
    <div class="row">
        <x-row-element>
            <label for="proveedor">Proveedor</label>
                <input type="text" id="proveedor" name="proveedor" class="form-control" placeholder="tipo de manto." value="{{$mante->lastMante->proveedor}}" readonly="">
        </x-row-element>
        <x-row-element>
            <label for="clase_manto">Tipo de mantenimiento</label>
                <input type="text" id="clase_manto" name="clase_manto" class="form-control" placeholder="tipo de manto." value="
                @switch($mante->lastMante->tipo)
                    @case(1)
                        Correctivo
                        @break
                    @case(2)
                        Preventivo
                        @break
                @endswitch" readonly="">
        </x-row-element>
        <x-row-element>
            <label for="comentario">Observaciones</label>
            <input type="text" id="comentario" name="comentario" class="form-control" placeholder="sin observaciones..." value="{{$mante->lastMante->comentarios}}" readonly="">
        </x-row-element>
        <div class="col-lg-10 col-md-10 col-sm-6 col-xs-12">
            @isset($mante->lastMante->imagenes)    
                <x-acordeon id="imagenes">
                    <x-acordeon-element id="img_{{$mante->id}}" hrefID="imagenes_{{$mante->id}}" parentID="imagenes" title="Imagenes">
                        <div class="container">
                            <div class="row">
                                @foreach ($mante->lastMante->imagenes as $img)
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
                                    <div class="form-group">
                                        <div class="" style="margin-bottom: 1em; margin-right: 2em;">
                                            <div class="img_galery">
                                                <a data-fslightbox href="{{asset('storage/'.$img->path)}}">
                                                    <img class="upd_img figure-img img-fluid rounded img-thumbnail" height="100px" width="100px" src="{{asset('storage/'.$img->path)}}"/>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </x-acordeon-element>
                </x-acordeon>
            @endisset
            @isset($mante->lastMante->pdfs)    
                <x-acordeon id="pdfs">
                    <x-acordeon-element id="pdf_{{$mante->id}}" hrefID="pdfs_{{$mante->id}}" parentID="pdfs" title="pdfs">
                        <div class="container">
                            <div class="row">
                                @foreach ($mante->lastMante->pdfs as $pdf)
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
                                    <div class="thumbnail">
                                        <div class="caption">
                                            <a href="{{asset('storage/'.$pdf->path)}}" target="_blank" class="rounded">
                                                <img class="upd_img" height="100px" width="100px" src="{{asset('img/pdf.svg')}}"/>
                                                <span>{{$pdf->name}}</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </x-acordeon-element>
                </x-acordeon>
            @endisset
        </div>
        
    </div>
@else
    <p class="text-muted">Aún no se han registrado mantenimientos.</p>
@endif
<div class="form-group">
    <a href="{{route('mante',$mante->activo->tipos->id_giro)}}"><button class="btn btn-danger" type="button" >Atras</button></a>
</div>
<script src="{{asset('js/fslightbox.js')}}"></script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('qr', () => ({
            pathQR: '',loading:true,
			async getQr(){
                this.loading=true;
				const response=await fetch(@js(route('service.manteAct.qr',$mante->id)))
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