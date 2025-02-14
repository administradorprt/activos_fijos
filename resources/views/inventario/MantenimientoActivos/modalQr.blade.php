<div class="modal fade model-side-in-right" role="dialog" tabindex="-1" aria-hidden="true" id="modal-qr-{{$mante->id}}">
    <div class="modal-dialog"  role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">x</span>
                    
                </button>
                <h4 class="modal-title">QR del activo {{$mante->activo->id}}</h4>
            </div>
            <div class="modal-body">
                <div class="loader" x-cloak x-show="loading"></div>
                <div class="thumbnail">
                    <img :src="pathQR.path" alt="Qr_activo_{{$mante->activo->id}}" x-cloak x-show="!loading">
                    <div class="caption">
                        <a class="btn btn-success" :href="pathQR.path" download="" x-cloak x-show="!loading">Descargar QR</a>
                    </div>
                </div>
                
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary" >Confirmar</button>
            </div>
        </div>
    </div>
</div>
<style>
    /* css del spinner de carga */
.loader {
  width: 50px;
  padding: 8px;
  aspect-ratio: 1;
  border-radius: 50%;
  background: #25b09b;
  --_m: 
    conic-gradient(#0000 10%,#000),
    linear-gradient(#000 0 0) content-box;
  -webkit-mask: var(--_m);
          mask: var(--_m);
  -webkit-mask-composite: source-out;
          mask-composite: subtract;
  animation: l3 1s infinite linear;
}
@keyframes l3 {to{transform: rotate(1turn)}}
</style>