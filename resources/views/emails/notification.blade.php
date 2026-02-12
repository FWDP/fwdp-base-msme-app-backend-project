<h2>{{ $title  }}</h2>

<p>{{ $message  }}</p>

@if(!empty($data))
    <br>
    <pre>{{ json_encode($data, JSON_PRETTY_PRINT) }}</pre>
@endif
