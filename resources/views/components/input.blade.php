@props(['name'])

<input name="{{ $name }}" {{$attributes->merge(['class'=>'border border-gray-400 px-2 py-1 rounded-lg read-only:bg-gray-50 read-only:text-gray-500'])}}>
