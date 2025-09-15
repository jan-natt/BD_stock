@props(['messages' => null])

@if($messages)
    @foreach($messages as $message)
        <p {{ $attributes->merge(['class' => 'text-sm text-red-600']) }}>{{ $message }}</p>
    @endforeach
@endif
