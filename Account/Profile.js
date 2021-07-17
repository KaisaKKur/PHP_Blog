
function showProfile(profile) {
    console.debug(profile);
    $(document).ready(function () {
        $("#nickname").attr("value", profile["Nickname"]);
        $("#email").attr("value", profile["Email"]);
        $("#phone-number").attr("value", profile["PhoneNumber"]);
        $("#birthday").attr("value", profile["Birthday"]);
        if (profile["Sex"] == "") {
            $("#sex").val("");
        } else {
            if (profile["Sex"] == true) {
                $("#sex").val("true");
            } else {
                $("#sex").val("false");
            }
        }
    });
}

