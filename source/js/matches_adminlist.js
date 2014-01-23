function cancelMatch (matchId)
{
    if (confirm('Cancel match?')) {
        submitPostForm({
            command : 'cancel',
            id      : matchId
        });
    }
}

function recalkMatch (matchId)
{
    if (confirm('Update factors?')) {
        submitPostForm({
            command : 'update-factor',
            id      : matchId
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