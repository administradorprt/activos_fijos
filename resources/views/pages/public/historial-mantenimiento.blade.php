<x-layouts.public>
    <header class="flex items-center flex-wrap mb-6">
        <figure class=" max-w-56">
            <img src="{{asset('img/logoPRT.png')}}" alt="logo PRT" srcset="" class="w-full">
        </figure>
        <h1 class="flex-1 text-center text-xl">Historial de mantenimiento del activo: {{$activo->activo_id}}</h1>
    </header>
    <section x-data="mantenimiento">
        <x-card>
            <div class="flex flex-wrap gap-3">
                <div>
                    <x-label value="Mantenimiento del:">
                        <x-select name="mantenimiento" class="form-control"  required="" @change="await getMante(event)">
                            @foreach ($activo->mantenimientos as $manto)  
                                <option value="{{$manto->id}}">{{$manto->fecha}}</option>
                            @endforeach
                        </x-select>
                    </x-label>
                </div>
                <div>
                    <x-label value="Proveedor">
                        <x-input type="text" name="proveedor" class="form-control" placeholder="tipo de manto." x-bind:value="mante.proveedor" readonly=""/>
                    </x-label>
                </div>
                <div>
                    <x-label value="Tipo de mantenimiento">
                        <x-input type="text" name="clase_manto" class="form-control" placeholder="tipo de manto." x-bind:value="
                        mante.tipo==1?'Correctivo':'Preventivo'
                        " readonly=""/>
                    </x-label>
                </div>
                <div class="flex-1">
                    <x-label value="Observaciones">
                        <x-input type="text" name="comentario" class="form-control" placeholder="sin observaciones..." x-bind:value="mante.comentarios" readonly=""/>
                    </x-label>
                </div>
            </div>
            <div class="loader" x-cloak x-show="loading"></div>
            <div x-cloak x-show="!loading">
                <template x-if="mante.imagenes.length>0">
                    <div>
                        <br>
                        <x-alpine.accordion label="Imágenes">
                            <div class="flex gap-3 p-2">
                                <template x-for="img in mante.imagenes" :key="img.id">
                                    <div class="img_galery">
                                        <img class="upd_img figure-img img-fluid rounded img-thumbnail" height="100px" width="100px" :src="'{{ asset('storage') }}/'+img.path" onClick="reply_click()"/>
                                    </div>
                                </template>
                              </div>
                        </x-alpine.accordion>
                    </div>
                </template>
                <template x-if="mante.imagenes.length>0">
                    <div>
                        <br>
                        <x-alpine.accordion label="Pdfs">
                            <div class="flex gap-3 p-2">
                                <template x-for="pdf in mante.pdfs" :key="pdf.id">
                                    <div>
                                        <a :href="'{{asset('storage')}}/'+pdf.path" target="_blank" class="rounded block w-28">
                                            <img class="upd_img" height="100px" width="100px" src="{{asset('img/pdf.svg')}}"/>
                                            <span x-text="pdf.name" class="block text-balance"></span>
                                        </a>
                                    </div>
                                </template>
                            </div>
                        </x-alpine.accordion>
                    </div>
                </template>
            </div>
        </x-card>
    </section>
    <div id="myModal" class="modal">
        <button onclick="closeModal()" class="close">&times;</span></button>
        <img class="modal-content" id="img01">
        <div id="caption"></div>
    </div>
    <script src="{{asset('js/jquery-3.7.1.js')}}"></script>
{{--     <script src="{{asset('js/fslightbox.js')}}"></script> --}}
    <script src="{{asset('js/funciones.js')}}"></script>
    @vite(['public/css/spinner.css','public/css/acordeon.css','public/css/modal.css'])
    <script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('mantenimiento', () => ({
            mante:@js($activo->lastMante),loading:false,
            
            async getMante(event){
                this.loading=true;
                const val=event.target.value;
                const urlBase = @js(route('service.manto', ['id' => '__ID__']));
                const url = urlBase.replace('__ID__', val);
                        
                const response=await fetch(url).finally(()=>{this.loading=false;});
                response.ok
                ?this.mante=await response.json()
                :console.error(`Error ${response.status}: ${response.statusText}`);
            },
        }))
    })
    </script>
</x-layouts.public>