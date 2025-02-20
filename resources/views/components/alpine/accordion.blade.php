@props(['label'])
<div class="flex flex-col border rounded-md overflow-hidden border-prt-purple" x-data="{collapse:false}">
    <div class="flex items-center justify-between cursor-pointer font-semibold text-balance text-base bg-gray-200 text-gray-500 px-1 py-1" @click="collapse=!collapse">
        <h2>
            {{$label}}
        </h2>
        <figure class="transition-all duration-300" :class="collapse&&'rotate-90'">
            <x-icons.caret-arrow class="size-4"/>
        </figure>
    </div>
    <div x-cloak x-show="collapse" class="px-2">
        {{$slot}}
    </div>
</div>