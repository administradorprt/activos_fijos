<center><h1>Responsiva de equipo</h1></center>
<hr>
<h4>El equipo contiene las siguientes caracteristicas</h4>


<div>
	<strong><label for="">Nombre del responsable:</strong>
   {{$MobiliarioEquipo->empleado->nombres}} {{$MobiliarioEquipo->empleado->apellido_p}} {{$MobiliarioEquipo->empleado->apellido_m}}</label>
</div>
<div>
	<strong> <label for="">Número del Equipo:</strong>
   {{$MobiliarioEquipo->num_equipo}} </label>
</div>
<div>
	<strong> <label for="">Marca:</strong>
   {{$MobiliarioEquipo->marca}} </label>
</div>
<div>
	<strong> <label for="">modelo:</strong>
   {{$MobiliarioEquipo->modelo}} </label>
</div>
<div>
	<strong> <label for="">Color:</strong>
   {{$MobiliarioEquipo->color}} </label>
</div>
<div>
	<strong> <label for="">Tipo:</strong>
   {{$MobiliarioEquipo->tipos->nombre}} </label>
</div>
<div>
	<strong> <label for="">Detalles:</strong>
   {{$MobiliarioEquipo->descripcion}} </label>
</div>
<div>
	<strong> <label for="">Observaciones:</strong>
   {{$MobiliarioEquipo->observaciones}} </label>
</div>
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
<div>{{$MobiliarioEquipo->empleado->nombre}} {{$MobiliarioEquipo->empleado->apellido_p}} {{$MobiliarioEquipo->empleado->apellido_m}}</div>
<br>
_____________________________