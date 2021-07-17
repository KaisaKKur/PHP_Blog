<?php
session_start();

require_once 'Framework/Head.html';

require_once 'KJavaScript.php';
\KKur\KJavaScript::__JS();
require_once 'Index.js';
require_once 'Profile.js';
\KKur\KJavaScript::JS__();

if (!isset($_SESSION['UserID'])) {
    echo "
        <script type='text/javascript'>
          goTo403();
        </script>
    ";
}

require_once 'Framework/Header.html';
require 'ShowAccount.php';

require_once 'KCSS.php';
\KKur\KCSS::__CSS();
require_once 'Framework/Blog.css';
\KKur\KCSS::CSS__();

require_once 'Module/Account/Account.php';
require_once 'Module/Account/Profile.html';

showProfile();

if (\KKur\Account::updateProfile()) {
    showProfile();
    require 'ShowAccount.php';
}

if (\KKur\Account::updatePassword()) {
}

if (\KKur\Account::delete()) {
    session_destroy();
    echo "
        <script type='text/javascript'>
            var protocol = window.location.protocol;
            var hostname = window.location.hostname;
            var root = protocol + '//' + hostname + '/';
            window.location.href = root;
        </script>
    ";
}

require_once 'Framework/Footer.html';
require_once 'Framework/Foot.html';



/* Function
*/
function showProfile() {
    $profile = \KKur\Account::getProfile();

    $nickname = $profile['Nickname'];
    $email = $profile['Email'];
    $phoneNumber = $profile['PhoneNumber'];
    $birthday = $profile['Birthday'];
    $sex = $profile['Sex'];

    $value = "Nickname: \"$nickname\", Email: \"$email\", PhoneNumber: \"$phoneNumber\", Birthday: \"$birthday\", Sex: \"$sex\"";

    echo "
        <script type='text/javascript'>
          var val = {" . $value . "};
          showProfile(val);
        </script>
    ";
}

