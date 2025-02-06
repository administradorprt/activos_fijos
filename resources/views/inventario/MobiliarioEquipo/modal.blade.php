<div class="modal fade model-side-in-right" role="dialog" tabindex="-1" aria-hidden="true" id="modal-delete-{{$tras->id}}">
	<form action="{{ route('MobiliarioEquipo.destroy',['MobiliarioEquipo' => $tras->id]) }}" method="post">
		@method("DELETE")
		@CSRF	
		<div class="modal-dialog"  role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">x</span>
						
					</button>
					<h4 class="modal-title">Baja Mobiliario {{$tras->id}}</h4>
				</div>
				<div class="modal-body">
					confirme si desea dar de baja el mobiliario
					<input type="text" name="motivo_baja" class="form-control" placeholder="motivo_baja" value="" >
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-primary" >Confirmar</button>
				</div>
			</div>
		</div>
	</form>
</div>
<div class="modal fade model-side-in-right" role="dialog" tabindex="-1" aria-hidden="true" id="modal-activate-{{$tras->id}}">
	<form action="{{ route('MobiliarioEquipo.activar',['MobiliarioEquipo' => $tras->id]) }}" method="post">
		@CSRF
		<div class="modal-dialog"  role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">x</span>
						<input name="id_act" type="text" value="{{$tras->id}}" hidden="" 	>
					</button>
					<h4 class="modal-title">Activar Mobiliaro {{$tras->id}}</h4>
				</div>
				<div class="modal-body">
					confirme si desea activar el mobiliario
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-primary" >Confirmar</button>
				</div>
			</div>
		</div>
	</form>
</div>