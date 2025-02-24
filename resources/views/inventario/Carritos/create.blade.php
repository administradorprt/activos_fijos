@extends('layouts.admin')
@section('contenido')
<div class="row">
	<div class="col-lg-10 col-md-10 col-sm-6 col-xs-12"x-data="mantenimiento">
		<h3>Nuevo carrito</h3>
		<div class="alert alert-danger" x-cloak x-show="errors.length>0">
			<ul>
                <template x-for="(er,index) in errors" :key="index">
                    <li><span x-text="er"></span></li>
                </template>
			</ul>
		</div>
		<form action="{{route('carritos.store',$tipo)}}" method="post" >
			@csrf
            <div class="row">
                <x-row-element>
                    <label for="titulo">Nombre del carrito</label>
                        <input type="text" name="titulo" maxlength="100" class="form-control" placeholder="titulo del carrito..." required="" x-model="titulo">
                </x-row-element>
            </div>
			<div class="row">
                <x-row-element>
                    <label for="sucursal_origen">Sucursal</label>
                    <select name="sucursal" id="sucursal_origen" class="form-control"  required="" x-model="suc" @change="await getActivos(event)">
                        <option value="" hidden>Seleccione una sucursal</option>
                        @foreach($sucursales as $sucursal )
                            <option  value="{{$sucursal->id_sucursal}}">{{$sucursal->empresa->alias}} - {{$sucursal->nombre}} </option>
                        @endforeach
                    </select>
                </x-row-element>
                <x-row-element>
                    <label for="empleado">Empleado</label>
                    <select name="user" class="form-control"  required="" x-model="emp">
                        <option value="" hidden>Seleccione un empleado</option>
                        <template x-for="emp in users" :key="emp.id_empleado">
                            <option :value="emp.id_empleado" x-text="`${emp.nombres} ${emp.apellido_p} ${emp.apellido_m}`"></option>
                        </template>
                    </select>
                </x-row-element>
			</div>
            <template x-for="(cajon,index) in carrito" :key="cajon.id">
                <div>
                    <fieldset>
                        <legend><span x-text="`Cajón # ${index+1}`"></span></legend>
                        <div class="row">
                            <x-row-element>
                                <label :for="`etiqueta_${cajon.id}`">Etiqueta</label>
                                <input type="text" name="etiqueta" :id="`etiqueta_${cajon.id}`" class="form-control" placeholder="Etiqueta del cajón..." required x-model="cajon.name">
                            </x-row-element>
                            <div x-cloack x-show="index>0">
    
                                <x-row-element>
                                    <button type="button" class="btn btn-danger" @click="delCajon(cajon.id)">
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                    </button>
                                </x-row-element>
                            </div>
                        </div>
                        <div class="loader" x-cloak x-show="loading"></div>
                        <div class="row" style="overflow-y: auto;max-height: 25rem;">
                            <template x-for="activo in activos" :key="activo.id">
                            <x-row-element>
                                    <label >
                                        <input type="checkbox" :name="`activo[]`" :id="`act_${activo.id}`" :value="activo.id" x-model="cajon.acts">
                                        <span x-text="activo.descripcion"></span>
                                    </label>
                            </x-row-element>
                            </template>
                        </div>
                    </fieldset>
                    <hr>
                </div>
            </template>
            <div class="row">
                <x-row-element>
                    <button type="button" class="btn btn-success" @click="addCajon()">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                    </button>
                </x-row-element>
            </div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<button class="btn btn-primary" type="button" @click="enviar()">Guardar</button>
                    <button class="btn btn-primary" >Guardar</button>
					<a href="{{ URL::previous() }}"><button class="btn btn-danger" type="button" >Cancelar</button></a>
				</div>
			</div>
		</form>
	</div>
</div>
@vite(['public/css/spinner.css'])
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('mantenimiento', () => ({loading: false,
            activos:[],users:[],manual:false,suc:'',emp:'',titulo:'',carrito:[{id:1,name:'',acts:[]}],count:1,errors:[],
            addCajon(){
                this.count++;
                this.carrito.push({id:this.count,name:'',acts:[]});
            },
            delCajon(id){
                this.carrito=this.carrito.filter(cajon=>cajon.id!=id);
            },
			async getActivos(event){
                this.loading=true;
                this.carrito=[{id:1,name:'',acts:[]}];
				const val=event.target.value;
				const urlBase = @js(route('service.getActivosBySuc', ['giro' => $tipo, 'sucursal' => '__SUCURSAL__']));
            	const url = urlBase.replace('__SUCURSAL__', val);
				await this.getEmpleados(val);
				const response=await fetch(url).finally(()=>{
                    this.loading=false;
                });
				response.ok
				?this.activos=await response.json()
				:console.error(`Error ${response.status}: ${response.statusText}`);
			},
            async getEmpleados(sucursal){
                const urlBase = @js(route('service.empleados.suc', ['sucursal' => '__SUCURSAL__']));
            	const url = urlBase.replace('__SUCURSAL__', sucursal);
				const response=await fetch(url);
				response.ok
				?this.users=await response.json()
				:console.error(`Error ${response.status}: ${response.statusText}`);
            },
            async enviar(){
                this.errors=[];
                this.loading=true;
                const csrfToken = document.querySelector('input[name="_token"]').value;
                console.log(JSON.stringify({titulo:this.titulo,sucursal:this.suc, user:this.emp,carrito: this.carrito}));
                
                const response=await fetch(@js(route('carritos.store',$tipo)),{
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({titulo:this.titulo,sucursal:this.suc, user:this.emp,carrito: this.carrito})
                }).finally(()=>{this.loading=false;});
                
				response.ok
				?console.log(await response.json())
                /* window.location.href=@js(route('carritos',$tipo)) */
				:this.error(await response.json());
			},
            error(list){
                Object.entries(list.errors).forEach(([clave,error]) => {
                    this.errors.push(error[0]);
                });
                console.log(list.errors,this.errors);
            }
        }))
    })
</script>
@endsection