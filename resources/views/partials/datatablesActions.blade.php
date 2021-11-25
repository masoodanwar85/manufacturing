@if (isset($startButton))
	{!! $startButton !!}
@endif
@can($viewGate)
    <a class="btn btn-xs btn-primary" href="{{ route($crudRoutePart . '.show', $row->$primaryKey) }}">
        View
    </a>
@endcan
@can($editGate)
    <a class="btn btn-xs btn-info" href="{{ route($crudRoutePart . '.edit', $row->$primaryKey) }}">
        Edit
    </a>
@endcan
@can($deleteGate)
    <form action="{{ route($crudRoutePart . '.destroy', $row->$primaryKey) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this?');" style="display: inline-block;">
        <input type="hidden" name="_method" value="DELETE">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="submit" class="btn btn-xs btn-danger" value="Delete">
    </form>
@endcan
