function cancelBet (betId)
{
    if (confirm('Cancel BET?')) {
        submitPostForm({
            command : 'cancel-bet',
            betid   : betId
        });
    }
}

function cancelBalance (balanceId)
{
    if (confirm('Cancel BALANCE?')) {
        submitPostForm({
            command     : 'cancel-balance',
            balanceid   : balanceId
        });
    }
}

function submitPostForm (data){
    var html = '<form method="post" name="commandForm">';

    for (var key in data) {
        var val = data[key];
        html += '<input type="hidden" name="'+key+'" value="'+val+'" />';
    }

    html += '</form>';

    $("body").append (html);
    document.commandForm.submit ();
}