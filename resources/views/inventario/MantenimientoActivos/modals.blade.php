<div class="modal fade model-side-in-right" role="dialog" tabindex="-1" aria-hidden="true" id="modal-delete-{{$mante->id}}">
	<form action="{{ route('manteAct.delete',['id' => $mante->id]) }}" method="post">
		@method("DELETE")
		@CSRF
		<div class="modal-dialog"  role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">x</span>
						
					</button>
					<h4 class="modal-title">Baja de activo {{$mante->id}}</h4>
				</div>
				<div class="modal-body">
					<h3>¿Esta seguro de dar de baja el activo?</h3>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
					<button type="submit" class="btn btn-primary" >Confirmar</button>
				</div>
			</div>
		</div>
	</form>
</div>

<div class="modal fade model-side-in-right" role="dialog" tabindex="-1" aria-hidden="true" id="modal-activate-{{$mante->id}}">
	<form action="{{ route('manteAct.activar',['id' => $mante->id]) }}" method="post">
		@CSRF
		<div class="modal-dialog"  role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">x</span>
						<input name="id_act" type="text" value="{{$mante->id}}" hidden="" 	>
					</button>
					<h4 class="modal-title">Activar Herramienta {{$mante->id}}</h4>
				</div>
				<div class="modal-body">
					Confirme si desea habilitar el activo
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-primary" >Confirmar</button>
				</div>
			</div>
		</div>
	</form>
</div>
<div class="modal fade model-side-in-right" role="dialog" tabindex="-1" aria-hidden="true" id="modal-fecha-{{$mante->id}}">
	<form action="{{ route('mante.updateProx',['activo' => $mante->id]) }}" method="post">
		@method('PATCH')
		@CSRF
		<div class="modal-dialog"  role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">x</span>
						<input name="id_act" type="text" value="{{$mante->id}}" hidden="">
					</button>
					<h4 class="modal-title">Definir fecha de mantenimiento para el activo {{$mante->id}}</h4>
				</div>
				<div class="modal-body">
					<label for="fecha">Fecha del próximo mantenimiento</label>
					<input type="date" name="fecha" class="form-control" required value="{{old('fecha')}}">
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-primary" >Confirmar</button>
				</div>
			</div>
		</div>
	</form>
</div>
