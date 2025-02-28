<x-layouts.public>
    <header class="flex items-center flex-wrap mb-6">
        <figure class=" max-w-56">
            <img src="{{asset('img/logoPRT.png')}}" alt="logo PRT" srcset="" class="w-full">
        </figure>
        <h1 class="flex-1 text-center text-xl">Carrito <em>{{$carrito->nombre}}</em></h1>
    </header>
    <section>
        <x-card>
            <div class="flex flex-wrap gap-3">
                <div>
                    <x-label value="Sucursal">
                        <x-input name="sucursal" value="{{$carrito->sucursal->empresa->alias}} - {{$carrito->sucursal->nombre}}" readonly=""/>
                    </x-label>
                </div>
                <div class="flex-1">
                    <x-label value="Asignado a:">
                        <x-input name="asignado" class="w-full" value="{{$carrito->asignado->nombres}} {{$carrito->asignado->apellido_p}} {{$carrito->asignado->apellido_m}}" readonly=""/>
                    </x-label>
                </div>
            </div>
        </x-card>
    </section>
    <section>
        <x-card margin="my-2">
            <h4 class="pb-1 border-b mb-3 font-medium">Cajones</h4>
            @if ($carrito->cajones)
            <div class="grid grid-cols-1 gap-3">
                @foreach ($carrito->cajones as $cajon)    
                    <x-alpine.accordion label="{{$cajon->name}}">
                        <ul class="flex gap-x-8 gap-y-3 flex-wrap px-4 py-2 list-disc">
                            @foreach ($cajon->activos as $activo)
                                <li><span class="block">{{$activo->num_equipo}} - {{$activo->descripcion}}</span></li>
                            @endforeach
                        </ul>
                        <div class="flex gap-3 flex-wrap p-2">
                        </div>
                    </x-alpine.accordion>
                @endforeach
            </div>
            @else
                <span class=" text-sm text-gray-500">Sin cajones registrados</span>
            @endif
        </x-card>
    </section>
    {{-- <script src="{{asset('js/fslightbox.js')}}"></script> --}}
</x-layouts.public>