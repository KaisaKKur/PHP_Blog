<?php
session_start();

require_once 'KJavaScript.php';
\KKur\KJavaScript::__JS();
require_once 'Index.js';
\KKur\KJavaScript::JS__();

if (!isset($_SESSION['UserID'])) {
    echo "
        <script type='text/javascript'>
          goTo403();
        </script>
    ";
}

require_once 'Framework/Head.html';

require_once 'KCSS.php';
\KKur\KCSS::__CSS();
require_once 'Module/Account/LogIn_SignOut_SignUp.css';
\KKur\KCSS::CSS__();

require_once 'Module/Account/SignOut.html';

require_once 'Module/Account/Account.php';

if (KKur\Account::signOut()) {
    session_destroy();
    echo "
        <script type='text/javascript'>
          goToIndex();
        </script>
    ";
}

