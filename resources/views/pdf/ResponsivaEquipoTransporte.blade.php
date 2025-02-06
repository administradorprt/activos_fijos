<center><h1>Responsiva de equipos</h1></center>
<hr>
<style>
table, th, td {
  border: 1px solid black;
  border-collapse: collapse;
}
</style>
@php 
   $hoy = date("m-d-Y");
@endphp
<div>
	<strong><label for="">Nombre del responsable:</strong>
   {{$Empleado->nombres}} {{$Empleado->apellido_p}} {{$Empleado->apellido_m}}</label>
</div>
<div>
	@foreach ($Empleado->puesto->departamento as $depto)    
	<strong><label for="">Departamento:</strong>
   {{$depto->nombre}}</label>
   @endforeach
</div>
<div>
	<strong><label for="">Puesto:</strong>
   {{$Empleado->puesto->nombre}} </label>
</div>
<div>
	<strong><label for="">Fecha:</strong>
   {{$hoy}}</label>
</div>
<h4>DESCRIPCION DE EQUIPOS ASIGNADOS</h4>
<table >
   <tr>
      <th>numero equipo</th>
      <th>vin</th>
      <th>descripcion</th>
      <th>modelo</th>
      <th>factura</th>
	</tr>
   @foreach ($EquipoTransporte as $tras)
    <tr >
      <td>{{$tras->num_equipo}}</td>
      <td>{{$tras->vin}}</td>
      <td>{{$tras->descripcion}}</td>
      <td>{{$tras->modelo}}</td>
      <td>{{$tras->num_factura}}</td>
	</tr>
   @endforeach
</table>
<br>
<div ALIGN="justify" >
Dicho equipo es único y exclusivamente para el uso de la persona a quien se le hace entrega; por lo que es su responsabilidad tenerlo siempre en buen estado y en óptimas condiciones, ya que la finalidad de este equipo es para el uso exclusivo en los servicios requeridos en el área de trabajo.
</div>
<br>
<div ALIGN="justify">
Me responsabilizo del cuidado del equipo que se describe en las Condiciones Generales del Equipo, de conformidad con el departamento correspondiente. En caso de extravió, robo o daño distinto al deterioro normal del equipo, acepto realizar la reposición del equipo con las mismas características y/o realizar la reparación del mismo.
</div>
<br>	
<divALIGN="justify">
En el caso del mal uso del equipo, si el usuario por primera vez no cumpla con algún punto de esta política, el Área correspondiente emitirá un escrito al usuario invitándolo a que cumpla con las políticas para el buen uso del equipo.
</div>
<br>
<div ALIGN="justify">
Si el usuario reincide, el Área correspondiente procederá a enviar un escrito a la Gerencia General y al Contralor, notificando que es la segunda ocasión que incumple con las políticas. Dichas Áreas tomaran las medidas disciplinarias y/o administrativas correspondientes.
</div>

<br>
<br>
<div>Asociado Recibió</div>

 <div>{{$Empleado->nombre}} {{$Empleado->apellido_p}} {{$Empleado->apellido_m}}</div>

<br>
_____________________________