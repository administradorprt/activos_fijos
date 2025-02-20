@props(['value','required'=>false])

<label {{ $attributes->merge(['class' => '']) }}>
    @isset($value)
        <span class="block font-medium text-sm {{$required?'before:content-["*"] before:text-prt-red-letter':''}}">{{$value}}</span>
    @endisset
    {{$slot}}
</label>