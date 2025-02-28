<div class="modal fade model-side-in-right" role="dialog" tabindex="-1" aria-hidden="true" id="modal-delete-{{$cajon->id}}">
	<form action="{{ route('carritos.cajon.delete',$cajon->id) }}" method="post">
		@method('DELETE')
		@CSRF
		<div class="modal-dialog"  role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">x</span>
						<input name="id_act" type="text" value="{{$cajon->id}}" hidden="">
					</button>
					<h4 class="modal-title">{{$cajon->name}}</h4>
				</div>
				<div class="modal-body">
					<h3>¿Esta seguro de eliminar el cajón?</h3>
                    <p>Al eliminar un cajón, los activos dentro de este seran removidos.</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
					<button type="submit" class="btn btn-primary" >Confirmar</button>
				</div>
			</div>
		</div>
	</form>
</div>