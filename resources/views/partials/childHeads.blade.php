<li>{{ $childHead->headName }}</li>
@if ($childHead->heads)
    <ul>
        @foreach ($childHead->heads as $childAccountHead)
            @include('admin.purchase.childHeads', ['childHead' => $childAccountHead])
        @endforeach
    </ul>
@endif
