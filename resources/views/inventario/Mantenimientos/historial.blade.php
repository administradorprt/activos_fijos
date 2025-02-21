@extends('layouts.admin')
@section('contenido')
<h4>Historial de mantenimiento del activo: {{$activo->activo_id}}</h4>
<div x-data="mantenimiento">
  <div class="row">
    <x-row-element>
      <label for="mantenimiento"><span>Mantenimiento del:</span></label>
      <select name="mantenimiento" class="form-control"  required="" @change="await getMante(event)">
        @foreach ($activo->mantenimientos as $manto)  
          <option value="{{$manto->id}}">{{$manto->fecha}}</option>
        @endforeach
      </select>
    </x-row-element>
  </div>
  <div class="loader" x-cloak x-show="loading"></div>
  <div class="row" x-cloak x-show="!loading">
    <x-row-element>
        <label for="proveedor">Proveedor</label>
            <input type="text" name="proveedor" class="form-control" placeholder="tipo de manto." :value="mante.proveedor" readonly="">
    </x-row-element>
    <x-row-element>
        <label for="clase_manto">Tipo de mantenimiento</label>
            <input type="text" name="clase_manto" class="form-control" placeholder="tipo de manto." :value="
            mante.tipo==1?'Correctivo':'Preventivo'
            " readonly="">
    </x-row-element>
    <x-row-element>
        <label for="comentario">Observaciones</label>
        <input type="text" name="comentario" class="form-control" placeholder="sin observaciones..." :value="mante.comentarios" readonly="">
    </x-row-element>
    <div class="col-lg-10 col-md-10 col-sm-6 col-xs-12">
      {{-- imagenes del mantenimiento --}}
      <template x-if="mante.imagenes.length>0">
        <x-acordeon id="imagenes">
            <x-acordeon-element id="img_" hrefID="imagenes_" parentID="imagenes" title="Imagenes">
                <div class="container">
                    <div class="row">
                      <template x-for="img in mante.imagenes" :key="img.id">
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
                          <div class="form-group">
                              <div class="" style="margin-bottom: 1em; margin-right: 2em;">
                                  <div class="img_galery">
                                    <img class="upd_img figure-img img-fluid rounded img-thumbnail" height="100px" width="100px" :src="'{{ asset('storage') }}/'+img.path" onClick="reply_click()"/>
                                  </div>
                              </div>
                          </div>
                        </div>
                      </template>
                    </div>
                </div>
            </x-acordeon-element>
        </x-acordeon>
      </template>
      <template x-if="mante.pdfs.length>0">
        <x-acordeon id="pdfs">
          <x-acordeon-element id="pdf_" hrefID="pdfs_" parentID="pdfs" title="pdfs">
              <div class="container">
                  <div class="row">
                    <template x-for="pdf in mante.pdfs" :key="pdf.id">
                      <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
                          <div class="thumbnail">
                              <div class="caption">
                                  <a :href="'{{asset('storage')}}/'+pdf.path" target="_blank" class="rounded">
                                      <img class="upd_img" height="100px" width="100px" src="{{asset('img/pdf.svg')}}"/>
                                      <span x-text="pdf.name"></span>
                                  </a>
                              </div>
                          </div>
                      </div>
                    </template>
                  </div>
              </div>
          </x-acordeon-element>
      </x-acordeon>
      </template>
    </div>
    
  </div>
</div>
<div id="myModal" class="modal">
  <button onclick="closeModal()" class="close">&times;</span></button>
  <img class="modal-content" id="img01">
  <div id="caption"></div>
</div>
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
@endsection