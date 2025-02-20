@props(['margin'=>''])
<div class="{{$margin}}">
    <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8 py-6 bg-white shadow-sm rounded-lg text-gray-900">
        {{ $slot }}
    </div>
</div>