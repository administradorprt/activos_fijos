<h5>Reporte de Equipos de Transporte</h5>
<table>
	<thead>
	<tr>
		<th>id</th>
		<th>descripcion</th>
		<th>estado</th>
		<th>marca</th>
		<th>vin</th>
		<th>modelo</th>
		<th>motor</th>
		<th>color</th>
		<th>tipo</th>
		<th>costo</th>
		<th>proveedor</th>
		<th>id proveedor</th>
		<th>numero de factura</th>
		<th>fecha de compra</th>
		<th>tasa de depreciacion</th>
		<th>vida util</th>
		<th>responsable</th>
		<th>area</th>
		<th>puesto</th>
		<th>fecha de baja</th>
		<th>motivo de baja</th>
		<th>observaciones</th>
		<th>garantia</th>
		<th>n. pedido</th>
		<th>inicio vida util</th>
		<th>inicio depreciacion</th>
		<th>precio venta</th>
		<th>estado depreciacion</th>
	</tr>
	</thead>
	<tbody>
		@foreach($equipos as $equipo)
			<tr>
				<td>{{$equipo->num_equipo}}</td>
				<td>{{$equipo->descripcion}}</td>
				<td>{{$equipo->estado ? 'activo':'inactivo'}}</td>
				<td>{{$equipo->marca}}</td>
				<td>{{$equipo->vin}}</td>
				<td>{{$equipo->modelo}}</td>
				<td>{{$equipo->num_motor}}</td>
				<td>{{$equipo->color}}</td>
				<td>{{$equipo->tipos->nombre}}</td>
				<td>{{$equipo->costo}}</td>
				<td>{{$equipo->nombre_provedor}}</td>
				<td>{{$equipo->id_proveedor}}</td>
				<td>{{$equipo->num_factura}}</td>
				<td>{{$equipo->fecha_compra}}</td>
				<td>{{$equipo->tasa_depreciacion}}</td>
				<td>{{$equipo->vida_util}}</td>
				<td>{{$equipo->nombre_responsable}}</td>
				<td>{{$equipo->area}}</td>
				<td>{{$equipo->puesto}}</td>
				<td>{{$equipo->fecha_baja}}</td>
				<td>{{$equipo->motivo_baja}}</td>
				<td>{{$equipo->observaciones}}</td>
				<td>{{$equipo->garantia}}</td>
				<td>{{$equipo->numero_de_pedido}}</td>
				<td>{{$equipo->fecha_vida_util_inicio}}</td>
				<td>{{$equipo->fecha_depreciacion_inicio}}</td>
				<td>{{$equipo->precio_venta}}</td>
				<td>{{$equipo->estatus_depreciacion}}</td>
			</tr>
		@endforeach
	</tbody>
</table>