@props(['name','required'=>false])

<select name="{{ $name }}" @required($required) {{$attributes->merge(['class'=>'border border-gray-400 py-1 rounded-lg'])}}>
    {{$slot}}
</select>
