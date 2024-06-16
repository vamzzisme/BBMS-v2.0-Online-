<?php
// starting session
session_start();
// if session name is 'login' then on log out we are killing the session
if (isset($_SESSION['login'])) {
    session_destroy();
    // since we created two sessions (see signin.php/line 84 and 85)
    // we are killing both the sessions on log out
    unset($_SESSION['login']);
    unset($_SESSION['user_name']);
    unset($_SESSION['user_id']);
    // redirect to index.php
    header("Location: index.php");

    // on logout we also delete the cookies we had created for id and nicknaem
    if (isset($_COOKIE['_uid_']) && isset($_COOKIE['_unn_'])) {
        setcookie('_uid_', '', time() - 86400, '/', '', '', true);
        setcookie('_unn_', '', time() - 86400, '/', '', '', true);
    }
    // redirect to index.php
    header('Location: index.php');
}
