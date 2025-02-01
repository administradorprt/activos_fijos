

<div class="modal fade model-side-in-right" role="dialog" tabindex="-1" aria-hidden="true" id="modal-delete-{{$tras->id_tipo}}">
	<form action="{{ route('Tipo.destroy',['Tipo' => $tras->id_tipo]) }}" method="post">
	@method("DELETE")
	@CSRF
		<div class="modal-dialog"  role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">x</span>
					</button>
					<h4 class="modal-title">Baja de Tipo {{$tras->id_tipo}}</h4>
				</div>
				<div class="modal-body">
					confirme si desea dar de baja el Tipo de activo
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-primary" >Confirmar</button>
				</div>
			</div>
		</div>
	</form>
</div>