@php
    $habilitado = $habilitado ?? true;
@endphp
<button 
    {{ $attributes->merge(['class' => 'btn btn-primary']) }}
    @if(!$habilitado) disabled @endif
>
    {{ $slot }}
</button>