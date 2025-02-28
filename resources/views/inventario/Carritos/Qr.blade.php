<div class="modal fade model-side-in-right" role="dialog" tabindex="-1" aria-hidden="true" id="modal-qr-{{$carrito->id}}">
    <div class="modal-dialog"  role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">x</span>
                    
                </button>
                <h4 class="modal-title">QR del carrito {{$carrito->id}}</h4>
            </div>
            <div class="modal-body">
                <div class="loader" x-cloak x-show="loading"></div>
                <div class="thumbnail">
                    <img :src="pathQR.path" alt="Qr_carrito_{{$carrito->id}}" x-cloak x-show="!loading">
                    <div class="caption">
                        <a class="btn btn-success" :href="pathQR.path" download="" x-cloak x-show="!loading">
                            <x-icons.download/>
                            <span>Descargar QR</span>
                        </a>
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
<link rel="stylesheet" href="{{asset('css/spinner.css')}}">