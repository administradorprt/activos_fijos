<?php

namespace App\Http\Controllers;

use App\Models\Carrito;
use App\Models\ManteActivo;
use Illuminate\Http\Request;

class UrlsPublicasController extends Controller
{
  public function publicShow(Request $request,ManteActivo $mante){
    //validamos que la ruta esté firmada
    if(! $request->hasValidSignature()){
      abort(401);
    }
    $mante->load('activo','lastMante');
    return view('pages.public.publicShow',compact('mante'));
  }
  public function historico(Request $request,ManteActivo $activo) {
    //validamos que la ruta esté firmada
    if(!$request->hasValidSignature()){
      abort(401);
    }
    $activo->load([
      'lastMante'=>fn($last)=>$last->with('imagenes','pdfs'),
      'mantenimientos'=>fn($mantos)=>$mantos->select('id','mante_activo_id','fecha')->orderBy('fecha','DESC')
    ]);
    return view('pages.public.historial-mantenimiento',compact('activo'));
  }
  public function showCarritoPublic(Request $request,Carrito $carrito) {
    if(! $request->hasValidSignature()){
      abort(401);
    }
    $carrito->load('cajones');
    return view('pages.public.carritoShow', compact('carrito'));
  }
}
