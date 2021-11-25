<script>
    $(function () {
        var totalDebits = 0;
        var totalCredits = 0;
        $('#{{ $tableID }} tbody tr td:nth-child({{ $debitColumn }})').each(function(i,elem){
            var jQElem = $(elem);
            if (!isNaN(parseInt(jQElem.text()))) {
                totalDebits += parseInt(jQElem.text());
            }
        });
        $('#{{ $tableID }} tbody tr td:nth-child({{ $creditColumn }})').each(function(i,elem){
            var jQElem = $(elem);
            if (!isNaN(parseInt(jQElem.text()))) {
                totalCredits += parseInt(jQElem.text());
            }
        });
        var finalBalance = 0;
        $('#{{ $tableID }} tbody tr:first td:nth-child({{ $balanceColumn }})').html(totalDebits-totalCredits);
        var trs = $('#{{ $tableID }} tbody tr:not(:first)').toArray().reverse();
        $(trs).each(function(i,row) {
            var jQRow = $(row);
            var debitValue = parseInt(jQRow.find('td:nth-child({{ $debitColumn }})').text());
            var creditValue = parseInt(jQRow.find('td:nth-child({{ $creditColumn }})').text());
            if (!isNaN(debitValue)) {
                finalBalance{{$debitOperator}}=debitValue;
            } else {
                finalBalance{{$creditOperator}}=creditValue;
            }
            jQRow.find('td:nth-child({{ $balanceColumn }})').html(finalBalance);
        });
    });
</script>
