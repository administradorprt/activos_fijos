<div class="modal fade model-side-in-right" role="dialog" tabindex="-1" aria-hidden="true" id="modal-fecha-{{$prestamo->id}}">
	<form action="{{ route('prestamos.devolucion', $prestamo->id) }}" method="post">
		@method('PATCH')
		@CSRF
		<div class="modal-dialog"  role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">x</span>
						<input name="id_act" type="text" value="{{$prestamo->id}}" hidden="">
					</button>
					<h4 class="modal-title">Devolución del activo {{$prestamo->activo_id}}</h4>
				</div>
				<div class="modal-body">
                    <div class="row">
                        <x-row-element>
                            <label for="fecha_fin">Fecha de devolución</label>
                            <input type="datetime-local" id="fecha_fin" name="fecha_fin" class="form-control" required value="{{old('fecha_fin')}}">
                        </x-row-element>
                    </div>
                    <div class="row">
                        <x-row-element>
                            <label for="comentario">Comentarios/observaciones</label>
                            <input type="text" name="comentario" class="form-control" placeholder="Comentario..."  maxlength="95" required value="{{old('comentario')}}">
                        </x-row-element>
                    </div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-primary" >Confirmar</button>
				</div>
			</div>
		</div>
	</form>
</div>