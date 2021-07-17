
function showAccount(showAccountName) {
    var protocol = window.location.protocol;
    var hostname = window.location.hostname;
    var root = protocol + "//" + hostname + "/";
    $(document).ready(function () {
        $("#account").html("\
          <div class='dropdown'>\
            <button class='btn btn-light dropdown-toggle' type='button' id='dropdown-menu-button' data-bs-toggle='dropdown' aria-expanded='false'>" + showAccountName + "</button>\
            <ul class='dropdown-menu' aria-labelledby='dropdown-menu-button'>\
              <li><a class='dropdown-item' href='" + root + "Account/Profile.php'>Profile</a></li>\
              <li><a class='dropdown-item' href='" + root + "Blog/Editor.php'>Blog</a></li>\
              <li><a class='dropdown-item' href='" + root + "Account/SignOut.php'>Sign out</a></li>\
            </ul>\
          </div>\
        ");
    });
}

