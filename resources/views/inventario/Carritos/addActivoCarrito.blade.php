@extends('layouts.admin')
@section('contenido')
<div class="row">
	<div class="col-lg-10 col-md-10 col-sm-6 col-xs-12">
		<h3>Añadir activos al cajón <em>"{{$cajon->name}}"</em> del carrito <em>"{{$cajon->carrito->nombre}}"</em></h3>
		@if(count($errors)>0)
		<div class="alert alert-danger">
			<ul>
				@foreach($errors ->all() as $error)
				<li>{{$error}}</li>
				@endforeach
			</ul>
		</div>
		@endif
		<form action="{{route('carritos.cajon.store',$cajon->id)}}" method="post" x-data="cajon">
			@csrf
            <div x-data="lista">
                <x-panel title="Activos disponibles">
                    <div class="input-group">
                        <label class="input-group-addon" for="cajon_serch"><i class="fa fa-search" aria-hidden="true"></i>
                        </label>
                        <input type="text" id="cajon_serch" class="form-control" placeholder="Buscar..." aria-describedby="sizing-addon2" x-model="search" @keyup="filter(event)">
                    </div>
                    <div class="row" style="overflow-y: auto;max-height: 40rem;">
                        <template x-for="activo in actList" :key="activo.id">
                            <x-row-element>
                                    <label >
                                        <input type="checkbox" :name="`activo[]`" :id="`act_${activo.id}`" :value="activo.id" x-model="selected">
                                        <span x-text="`${activo.num_equipo} - ${activo.descripcion}`"></span>
                                    </label>
                            </x-row-element>
                        </template>
                    </div>
                </x-panel>
            </div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<button class="btn btn-primary" type="submit" >Guardar</button>
					<a href="{{ URL::previous() }}"><button class="btn btn-danger" type="button" >Cancelar</button></a>
				</div>
			</div>
		</form>
	</div>
</div>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('cajon', () => ({
            activos:@js($activos)
        }));
        Alpine.data('lista',()=>({
            actList:[],search:'',selected:[],
            init(){
                this.actList=this.activos;
            },
			filter(){
                this.actList = this.activos.filter(act => 
                    act.descripcion.toLowerCase().includes(this.search.toLowerCase()) ||
                    act.num_equipo.toLowerCase().includes(this.search.toLowerCase())
                );   
            }
        }));
    })
</script>
@endsection