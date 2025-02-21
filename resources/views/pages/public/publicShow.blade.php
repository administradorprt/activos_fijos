<x-layouts.public>
    <header class="flex items-center flex-wrap mb-6">
        <figure class=" max-w-56">
            <img src="{{asset('img/logoPRT.png')}}" alt="logo PRT" srcset="" class="w-full">
        </figure>
        <h1 class="flex-1 text-center text-xl">Información del mantenimiento del activo {{$mante->activo_id}}</h1>
    </header>
    <section>
        <x-card>
            <div class="flex flex-wrap gap-3">
                <div class="w-full">
                    <x-label value="Descripción">
                        <x-input class="w-full" name="descripcion" value="{{$mante->activo->descripcion}}" readonly=""/>
                    </x-label>
                </div>
                <div>
                    <x-label value="sucursal">
                        <x-input name="sucursal" value="{{$mante->activo->sucursal->empresa->alias}} - {{$mante->activo->sucursal->nombre}}" readonly=""/>
                    </x-label>
                </div>
                <div>
                    <x-label value="Número de equipo">
                        <x-input name="num_equipo" value="{{$mante->activo->num_equipo}}" readonly=""/>
                    </x-label>
                </div>
                <div class="flex-1">
                    <x-label value="Periodo de mantenimiento">
                        <x-input name="frecuencia" value="{{$mante->frecuencia->name}}" readonly=""/>
                    </x-label>
                </div>
            </div>
        </x-card>
    </section>
    <section>
        <x-card margin="my-2">
            @if ($mante->lastMante)
                <div class="flex flex-wrap gap-3 justify-between items-center pb-1 border-b mb-3">
                    <h4>Último mantenimiento ({{$mante->ultimo_mante}} )</h4>
                    <a href="{{URL::signedRoute('public.mante.history',$mante->id)}}" class=" text-md text-purple-100 bg-purple-600 px-2 py-1 rounded-lg">Consultar historico</a>
                </div>
                <div class="flex flex-wrap gap-3">
                    <div>
                        <x-label value="Proveedor">
                            <x-input name="proveedor" value="{{$mante->lastMante->proveedor}}" readonly=""/>
                        </x-label>
                    </div>
                    <div>
                        <x-label value="Tipo de mantenimiento">
                            @switch($mante->lastMante->tipo)
                                @case(1)
                                    <x-input name="tipo" value="{{$mante->lastMante->proveedor}}" value="Correctivo" readonly=""/>
                                    @break
                                @case(2)
                                    <x-input name="tipo" value="{{$mante->lastMante->proveedor}}" value="Preventivo" readonly=""/>
                                    @break
                            @endswitch
                        </x-label>
                    </div>
                    <div class="flex-1">
                        <x-label value="comentarios">
                            <x-input name="comentarios" placeholder="sin observaciones..." value="{{$mante->lastMante->comentarios}}" readonly="" class="w-full"/>
                        </x-label>
                    </div>
                </div>
                @isset($mante->lastMante->imagenes)
                <br>    
                    <x-alpine.accordion label="Imágenes">
                        <div class="flex gap-3 flex-wrap p-2">
                            @foreach ($mante->lastMante->imagenes as $img)
                                <a data-fslightbox href="{{asset('storage/'.$img->path)}}">
                                    <img class="upd_img figure-img img-fluid rounded img-thumbnail" height="100px" width="100px" src="{{asset('storage/'.$img->path)}}"/>
                                </a>
                            @endforeach
                        </div>
                    </x-alpine.accordion>
                @endisset
                @isset($mante->lastMante->pdfs)
                <br>    
                    <x-alpine.accordion label="Pdfs">
                        <div class="flex gap-3 flex-wrap p-2">
                            @foreach ($mante->lastMante->pdfs as $pdf)
                                <a href="{{asset('storage/'.$pdf->path)}}" target="_blank" class="rounded">
                                    <img class="upd_img" height="100px" width="100px" src="{{asset('img/pdf.svg')}}"/>
                                    <span>{{$pdf->name}}</span>
                                </a>
                            @endforeach
                        </div>
                    </x-alpine.accordion>
                @endisset
            @else
                <span class=" text-sm text-gray-500">Aún no se han registrado mantenimientos</span>
            @endif
        </x-card>
    </section>
    <script src="{{asset('js/fslightbox.js')}}"></script>
</x-layouts.public>