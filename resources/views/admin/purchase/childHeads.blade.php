<li>{{ $childHead->headName }}</li>
@if ($childHead->heads)
    <ul>
        @foreach ($childHead->heads as $childAccountHead)
            @include('partials.childHeads', ['childHead' => $childAccountHead])
        @endforeach
    </ul>
@endif
