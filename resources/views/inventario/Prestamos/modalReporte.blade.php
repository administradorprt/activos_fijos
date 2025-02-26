<div class="modal fade model-side-in-right" role="dialog" tabindex="-1" aria-hidden="true" id="modal-reporte">
	<form action="{{ route('prestamos.reporte', $tipo) }}" method="post">
		@CSRF
		<div class="modal-dialog"  role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">x</span>
						<input name="id_act" type="text" value="" hidden="">
					</button>
					<h4 class="modal-title">Generar reporte de prestamos</h4>
				</div>
				<div class="modal-body">
                    <div class="row">
                        <x-row-element>
                            <label for="fecha_in">Fecha de inicial</label>
                            <input type="date" id="fecha_fin" name="fecha_in" class="form-control" required value="{{old('fecha_in')}}">
                        </x-row-element>
                        <x-row-element>
                            <label for="fecha_fin">Fecha final</label>
                            <input type="date" id="fecha_fin" name="fecha_fin" class="form-control" required value="{{old('fecha_fin')}}">
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