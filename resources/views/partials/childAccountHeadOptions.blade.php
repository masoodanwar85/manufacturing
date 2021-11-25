@if ($childHeadOption->childrenAccountHeads)
	<?php
		$isChildExists = false;
		if (count($childHeadOption->childrenAccountHeads)) {
			$isChildExists = true;
		}
	?>
	<option value="{{ $childHeadOption->headID }}"
		{!! $isChildExists ? 'style="color:red;" disabled' : 'style="color:green;"' !!}
		{!! $value == $childHeadOption->headID ? 'selected' : '' !!}
		>
		@for ($i = 1; $i < $level; $i++)
			---
		@endfor
		>
		{{ $childHeadOption->headName }}
	</option>
	<?php
		if (count($childAccountHead->childrenAccountHeads)) {
			$level++;
		}
	?>
	@foreach ($childHeadOption->childrenAccountHeads as $childAccountHead)
		<?php
		 	$isChildExists = false;
			if (count($childAccountHead->childrenAccountHeads)) {
				$isChildExists = true;
			}
		?>
		@include('partials.childAccountHeadOptions', ['childHeadOption' => $childAccountHead, 'level' => $level, 'isChildExists' => $isChildExists])
	@endforeach
@endif
