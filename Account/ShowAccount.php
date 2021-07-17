<?php
require_once 'KJavaScript.php';
\KKur\KJavaScript::__JS();
require_once 'Index.js';
require_once 'ShowAccount.js';
\KKur\KJavaScript::JS__();

require_once 'Module/Account/Account.php';

if (isset($_SESSION['UserID'])) {
    $profile = \KKur\Account::getProfile();

    $nickname = $profile['Nickname'];

    if (!empty($nickname)) {
        $showAccountName = $nickname;
    } else {
        $showAccountName = $profile['Username'];
    }

    if(isset($_SESSION['UserID'])) {
        echo "
            <script type='text/javascript'>
              showAccount('$showAccountName');
            </script>
        ";
    }
}

