<div class="modal fade model-side-in-right" role="dialog" tabindex="-1" aria-hidden="true" id="modal-delete-{{$tras->id_equipo_maquinaria}}">
	<form action="{{ route('MaquinariaEquipo.destroy',['MaquinariaEquipo' => $tras->id_equipo_maquinaria]) }}" method="post">
		@method("DELETE")
		@CSRF
		<div class="modal-dialog"  role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">x</span>
						
					</button>
					<h4 class="modal-title">Baja Maquinaria {{$tras->id_equipo_maquinaria}}</h4>
				</div>
				<div class="modal-body">
					confirme si desea dar de baja la maquinaria o equipo
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
<div class="modal fade model-side-in-right" role="dialog" tabindex="-1" aria-hidden="true" id="modal-activate-{{$tras->id_equipo_maquinaria}}">
	<form action="{{ route('MaquinariaEquipo.activar',['MaquinariaEquipo' => $tras->id_equipo_maquinaria]) }}" method="post">
		@CSRF
		<div class="modal-dialog"  role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">x</span>
						<input name="id_act" type="text" value="{{$tras->id_equipo_maquinaria}}" hidden="" 	>
					</button>
					<h4 class="modal-title">Activar Maquinaria o Equipo {{$tras->id_equipo_maquinaria}}</h4>
				</div>
				<div class="modal-body">
					confirme si desea activar la Maquinaria o Equipo
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-primary" >Confirmar</button>
				</div>
			</div>
		</div>
	</form>
</div>