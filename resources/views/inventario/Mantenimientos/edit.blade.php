@extends('layouts.admin')
@section('contenido')
<div class="row" >
	<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
		<h3>Editar mantenimiento: {{$mante->id}}</h3>
		@if(count($errors)>0)
			<div class="alert alert-danger">
				<ul>
					@foreach($errors ->all() as $error)
						<li>{{$error}}</li>
					@endforeach
				</ul>
			</div>
		@endif
		<form action="{{route('mante.update',$mante->id)}}" method="post" autocomplete="off" enctype="multipart/form-data">
			@method('PATCH')
			@csrf
			<div class="row">
				<x-row-element>
					<label for="tipo">Tipo de mantenimiento</label>
						<select name="tipo" class="form-control"  required="">
							<option value="" hidden>Seleccione una tipo</option>
                            <option value="1" @selected($mante->tipo==1)>Correctivo</option>
                            <option value="2" @selected($mante->tipo==2)>Preventivo</option>
						</select>
				</x-row-element>
				<x-row-element>
					<label for="proveedor">Proveedor</label>
						<input type="text" name="proveedor" maxlength="100" class="form-control" placeholder="proveedor"  value="{{$mante->proveedor}}">
				</x-row-element>
				<x-row-element>
					<label for="fecha">Fecha del mantenimiento</label>
						<input type="date" name="fecha" class="form-control" required value="{{$mante->fecha}}">
				</x-row-element>
				<x-row-element>
					<label for="pdfs">Pdfs</label>
						<input type="file" name="pdfs[]" class="form-control" accept="application/pdf" multiple>
				</x-row-element>
				<x-row-element>
					<label for="foto">Evidencias</label>
						<input type="file" name="foto[]" multiple="multiple" class="form-control" accept="image/*">
				</x-row-element>
				<x-row-element>
					<label for="comentario">Comentarios/observaciones</label>
						<input type="text" name="comentario" maxlength="900" class="form-control" placeholder="comentario" value="{{$mante->comentarios}}">
				</x-row-element>
			</div>
			<div class="row">
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<div class="form-group">
						<button class="btn btn-primary" type="submit" >Actualizar</button>
						<a href="/admin/inventario/Herramientas"><button class="btn btn-danger" type="button" >Cancelar</button></a>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
<div class="" x-data="manteEdit">
	<div class="box-footer clearfix" x-cloack x-show="selected.length>0">
		<button type="button" class="pull-left btn btn-danger pr-2" @click="await delArchivos()"> Eliminar imagenes/pdfs seleccionados <i class="fa fa-times"></i></button>
	</div>
	<div class="alert alert-warning" role="alert" x-cloack x-show="loading">Procesando	...</div>	
	<x-panel title="Imágenes">
		<template x-for="img in imgs" :key="img.id">
			<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
				<div class="form-group">
					<div class="" style="margin-bottom: 1em; margin-right: 2em;">
						<div class="img_galery">
							<label class="checkeablee">
								<input type="checkbox" name="archivo[]" class="chk_img_del_equip" :value="img.id" x-model="selected" />
								<img class="upd_img figure-img img-fluid rounded img-thumbnail"  height="100px" width="100px" :src="'{{asset('storage')}}/'+img.path"/>
							</label>
						</div>
					</div>
				</div>
			</div>
		</template>
	</x-panel>
	<x-panel title="Pdfs">
		<template x-for="pdf in pdfs" :key="pdf.id">
			<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
				<div class="thumbnail overflow-hidden">
					<div class="caption">
						<input type="checkbox" name="archivo[]" class="chk_img_del_equip" :value="pdf.id" x-model="selected" />
						<a :href="'{{asset('storage')}}/'+pdf.path" target="_blank" class="rounded">
							<img class="upd_img" height="100px" width="100px" src="{{asset('img/pdf.svg')}}"/>
							<span x-text="pdf.name"></span>
						</a>
					</div>
				</div>
			</div>
		</template>
	</x-panel>
</div>
<style>
	.overflow-hidden{
		overflow: hidden;
	}
</style>
<script>
	document.addEventListener('alpine:init', () => {
		Alpine.data('manteEdit', () => ({
			imgs:@js($mante->imagenes),pdfs:@js($mante->pdfs),mante:@js($mante->id),selected:[],loading:false,response:[],
			async delArchivos(){
				
				const csrfToken = document.querySelector('input[name="_token"]').value;
			  	this.loading=true;
				//preparamos la solicitud fetch
			  const response=await fetch(@js(route('service.mante.delArchivos',$mante->id)),{
				method: 'DELETE',
                headers: {
					'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({items: this.selected})
			  }).finally(()=>{this.loading=false;});
			  response.ok
			  ?this.response= await response.json()
			  :console.error(`Error ${response.status}: ${response.statusText}`);
			  this.selected=[];
			  this.imgs=this.response.imgs;
			  this.pdfs=this.response.pdfs;
			},
		}))
	})
  </script>
@endsection 