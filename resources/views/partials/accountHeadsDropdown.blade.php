<?php
	$level = 0;
?>
<select name="{{$name}}" class="form-control {{ $additionalClass ?? '' }}" {{ $isRequired == true ? 'required' : '' }}>
	<option value=""></option>
	@foreach ($accountHeads as $accountHead)
		<?php
			$level = 1;
		?>
		<option value="{{ $accountHead->headID }}"
			{!! ($accountHead->childrenAccountHeads) ? 'style="color:red;" disabled' : 'style="color:green;"' !!}
			{!! $value == $accountHead->headID ? 'selected' : '' !!}
			>
			{{ $accountHead->headName }}
		</option>
		@foreach ($accountHead->childrenAccountHeads as $childAccountHead)
			<?php
				$level = 2;
			?>
			@include('partials.childAccountHeadOptions', ['childHeadOption' => $childAccountHead, 'level' => $level, 'isChildExists' => true])
		@endforeach
	@endforeach
</select>
