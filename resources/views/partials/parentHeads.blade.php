<ul>
	@foreach ($accountHeads as $accountHead)
		<li>{{ $accountHead->headName }}</li>
		<ul>
			@foreach ($accountHead->childrenAccountHeads as $childAccountHead)
				@include('partials.childHeads', ['childHead' => $childAccountHead])
			@endforeach
		</ul>
	@endforeach
</ul>
