@extends('layouts.admin')
@section('contenido')
<div class="row" x-data="qr">
	<div class="col-lg-10 col-md-10 col-sm-6 col-xs-12">
		<h3>Activo: {{$mante->activo_id}}</h3>
        <div class="row">
            <x-row-element>
                <label for="sucursal_origen">Sucursal</label>
                <input type="text" name="sucursal_origen" class="form-control" placeholder="sucursal_origen" value="{{$mante->activo->sucursal->empresa->alias}} - {{$mante->activo->sucursal->nombre}}" readonly="">
            </x-row-element>
            <x-row-element>
                <label for="num_equipo">Número de equipo</label>
                <input type="text" name="num_equipo" class="form-control" placeholder="sucursal_origen" value="{{$mante->activo->num_equipo}}" readonly="">
            </x-row-element>
            <x-row-element>
                <label for="descripcion">Descripción</label>
                <input type="text" name="descripcion" class="form-control" placeholder="sucursal_origen" value="{{$mante->activo->descripcion}}" readonly="">
            </x-row-element>
            <x-row-element>
                <label for="num_equipo">Periodo de mantenimiento</label>
                <input type="text" name="num_equipo" class="form-control" placeholder="sucursal_origen" value="{{$mante->frecuencia->name}}" readonly="">
            </x-row-element>
            <x-row-element>
                <button class="btn btn-success" data-target="#modal-qr-{{$mante->id}}" data-toggle="modal" @click="await getQr()"><x-icons.qr/></button>
            </x-row-element>
        </div>       
	</div>
    @include('inventario.MantenimientoActivos.modalQr')
</div>
<hr>
@if ($mante->lastMante)
    <h4>Último mantenimiento ({{$mante->ultimo_mante}} )</h4>
    <div class="row">
        <x-row-element>
            <label for="proveedor">Proveedor</label>
                <input type="text" name="proveedor" class="form-control" placeholder="tipo de manto." value="{{$mante->lastMante->proveedor}}" readonly="">
        </x-row-element>
        <x-row-element>
            <label for="clase_manto">Proveedor</label>
                <input type="text" name="clase_manto" class="form-control" placeholder="tipo de manto." value="
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
            <input type="text" name="comentario" class="form-control" placeholder="sin observaciones..." value="{{$mante->lastMante->comentarios}}" readonly="">
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
                                                <a href="{{asset('storage/'.$img->path)}}" target="_blank">
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
                                    {{-- <div class="form-group">
                                        <div class="" style="margin-bottom: 1em; margin-right: 2em;">
                                            <div class="img_galery">
                                                <a href="{{asset('storage/'.$pdf->path)}}" target="_blank" class="rounded">
                                                    <img class="upd_img figure-img img-fluid rounded img-thumbnail" height="100px" width="100px" src="{{asset('img/pdf.svg')}}"/>
                                                    <span>{{$pdf->name}}</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div> --}}
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
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('qr', () => ({
            pathQR: 'hola',loading:true,
            log(){
                console.log(this.$refs.qr);
            },
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