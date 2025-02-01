<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MaquinariaEquipoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'descripcion' => 'required|max:1000',
            'marca' => 'required|max:50',
            'serie' => 'required',
            'modelo'=> 'required',
            'color'=> 'required',
            'costo'=> 'required|numeric',
            'nombre_provedor'=> 'required',
            'fecha_compra'=> 'required|date',
            'fecha_vida_util_inicio'=> 'date|nullable',
            'fecha_depreciacion_inicio'=> 'date|nullable',
            'tipo' => 'required',
            'foto.*'=> 'mimes:jpg,bmp,png,jpeg',
            'pdf'=>'mimes:pdf',
            'area_destinada'=>'required|numeric',
            'puesto'=>'required|numeric',
            'nombre_responsable'=>'required|numeric',
            'precio_venta'=>'numeric|nullable'
        ];
    }
}
