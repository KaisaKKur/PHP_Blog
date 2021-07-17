<?php
session_start();

require_once 'Framework/Head.html';

require_once 'KCSS.php';
\KKur\KCSS::__CSS();
require_once 'Module/Account/LogIn_SignOut_SignUp.css';
\KKur\KCSS::CSS__();

require_once 'Module/Account/LogIn.html';

require_once 'Module/Account/Account.php';

if (\KKur\Account::logIn()) {
	header("Location: http://localhost/");
}

