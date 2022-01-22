
<table border="1">
    <thead>
        <tr>
            <td>Staff</td>
            @foreach ($monthDates as $monthDays)
                <td>{{ $monthDays->Date }}</td>
            @endforeach
        </tr>
    </thead>
    <tbody>

    </tbody>
</table>
