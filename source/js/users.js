function startChangePassowrd (){
    $("#change-password-form").show ();
}

function cancelPasswordChanging (){
    $("#change-password-form").hide ();
    $("div.miniform div.errorMessage").remove ();

    $("#ChangePassword_old_password").val('');
    $("#ChangePassword_password").val('');
    $("#ChangePassword_password_confirm").val('');
}

function startChangeEmail (){
    $("#change-email-form").show();
}

function cancelChangeEmail (){
    $("#change-email-form").hide();
}