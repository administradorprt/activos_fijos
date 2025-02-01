<form action="{{route('EquipoTransporte.index')}}" method="GET" autocomplete="off" role="search">
	<div class="form-group col-md-6">
		<div class="form-group">
			<div class="input-group">
				<input type="text" class="form-control " name="searchText" placeholder="buscar..(id, descripción o Vin)" value="{{$searchText}}">
					<span class="input-group-btn">
						<button type="submit" class="btn btn-primary">Buscar</button>
					</span>
			</div>
		</div>
	</div>
</form>

